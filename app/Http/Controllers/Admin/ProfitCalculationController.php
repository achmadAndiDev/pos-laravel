<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfitCalculationController extends Controller
{
    /**
     * Display profit calculation page
     */
    public function index(Request $request)
    {
        $outlets = Outlet::where('status', 'active')->get();
        
        // Default date range (current month)
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $outletId = $request->get('outlet_id');
        
        // Get profit data
        $profitData = $this->calculateProfit($startDate, $endDate, $outletId);
        
        return view('admin.profit-calculation.index', compact(
            'outlets', 
            'profitData', 
            'startDate', 
            'endDate', 
            'outletId'
        ));
    }

    /**
     * Calculate profit data
     */
    private function calculateProfit($startDate, $endDate, $outletId = null)
    {
        // Base query for completed sales
        $salesQuery = Sale::with(['saleItems.product', 'outlet'])
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if ($outletId) {
            $salesQuery->where('outlet_id', $outletId);
        }

        $sales = $salesQuery->get();

        // Initialize data
        $totalRevenue = 0;
        $totalCost = 0;
        $totalTax = 0;
        $totalDiscount = 0;
        $grossProfit = 0;
        $netProfit = 0;
        $profitByProduct = [];
        $profitByOutlet = [];
        $dailyProfit = [];

        foreach ($sales as $sale) {
            $saleRevenue = $sale->subtotal;
            $saleCost = 0;
            $saleGrossProfit = 0;

            // Calculate cost and gross profit per sale item
            foreach ($sale->saleItems as $item) {
                $itemCost = $item->product->purchase_price * $item->quantity;
                $itemRevenue = $item->total_price;
                $itemGrossProfit = $itemRevenue - $itemCost;

                $saleCost += $itemCost;
                $saleGrossProfit += $itemGrossProfit;

                // Profit by product
                $productKey = $item->product->id;
                if (!isset($profitByProduct[$productKey])) {
                    $profitByProduct[$productKey] = [
                        'product' => $item->product,
                        'quantity_sold' => 0,
                        'revenue' => 0,
                        'cost' => 0,
                        'gross_profit' => 0,
                        'margin_percentage' => 0,
                    ];
                }

                $profitByProduct[$productKey]['quantity_sold'] += $item->quantity;
                $profitByProduct[$productKey]['revenue'] += $itemRevenue;
                $profitByProduct[$productKey]['cost'] += $itemCost;
                $profitByProduct[$productKey]['gross_profit'] += $itemGrossProfit;
                
                if ($profitByProduct[$productKey]['cost'] > 0) {
                    $profitByProduct[$productKey]['margin_percentage'] = 
                        ($profitByProduct[$productKey]['gross_profit'] / $profitByProduct[$productKey]['cost']) * 100;
                }
            }

            // Profit by outlet
            $outletKey = $sale->outlet_id;
            if (!isset($profitByOutlet[$outletKey])) {
                $profitByOutlet[$outletKey] = [
                    'outlet' => $sale->outlet,
                    'sales_count' => 0,
                    'revenue' => 0,
                    'cost' => 0,
                    'gross_profit' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'net_profit' => 0,
                ];
            }

            $profitByOutlet[$outletKey]['sales_count']++;
            $profitByOutlet[$outletKey]['revenue'] += $saleRevenue;
            $profitByOutlet[$outletKey]['cost'] += $saleCost;
            $profitByOutlet[$outletKey]['gross_profit'] += $saleGrossProfit;
            $profitByOutlet[$outletKey]['tax_amount'] += $sale->tax_amount;
            $profitByOutlet[$outletKey]['discount_amount'] += $sale->discount_amount;
            $profitByOutlet[$outletKey]['net_profit'] += ($saleGrossProfit - $sale->tax_amount + $sale->discount_amount);

            // Daily profit
            $dateKey = $sale->sale_date->format('Y-m-d');
            if (!isset($dailyProfit[$dateKey])) {
                $dailyProfit[$dateKey] = [
                    'date' => $sale->sale_date,
                    'sales_count' => 0,
                    'revenue' => 0,
                    'cost' => 0,
                    'gross_profit' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'net_profit' => 0,
                ];
            }

            $dailyProfit[$dateKey]['sales_count']++;
            $dailyProfit[$dateKey]['revenue'] += $saleRevenue;
            $dailyProfit[$dateKey]['cost'] += $saleCost;
            $dailyProfit[$dateKey]['gross_profit'] += $saleGrossProfit;
            $dailyProfit[$dateKey]['tax_amount'] += $sale->tax_amount;
            $dailyProfit[$dateKey]['discount_amount'] += $sale->discount_amount;
            $dailyProfit[$dateKey]['net_profit'] += ($saleGrossProfit - $sale->tax_amount + $sale->discount_amount);

            // Add to totals
            $totalRevenue += $saleRevenue;
            $totalCost += $saleCost;
            $totalTax += $sale->tax_amount;
            $totalDiscount += $sale->discount_amount;
            $grossProfit += $saleGrossProfit;
        }

        // Calculate net profit
        $netProfit = $grossProfit - $totalTax + $totalDiscount;

        // Sort data
        uasort($profitByProduct, function($a, $b) {
            return $b['gross_profit'] <=> $a['gross_profit'];
        });

        uasort($profitByOutlet, function($a, $b) {
            return $b['gross_profit'] <=> $a['gross_profit'];
        });

        ksort($dailyProfit);

        return [
            'summary' => [
                'total_sales' => $sales->count(),
                'total_revenue' => $totalRevenue,
                'total_cost' => $totalCost,
                'total_tax' => $totalTax,
                'total_discount' => $totalDiscount,
                'gross_profit' => $grossProfit,
                'net_profit' => $netProfit,
                'gross_margin_percentage' => $totalCost > 0 ? ($grossProfit / $totalCost) * 100 : 0,
                'net_margin_percentage' => $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0,
            ],
            'by_product' => array_values($profitByProduct),
            'by_outlet' => array_values($profitByOutlet),
            'daily' => array_values($dailyProfit),
        ];
    }

    /**
     * Export profit data to Excel/CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $outletId = $request->get('outlet_id');
        $format = $request->get('format', 'csv'); // csv or excel

        $profitData = $this->calculateProfit($startDate, $endDate, $outletId);

        if ($format === 'csv') {
            return $this->exportToCsv($profitData, $startDate, $endDate);
        }

        // For future Excel export implementation
        return response()->json(['error' => 'Excel export not implemented yet'], 400);
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($profitData, $startDate, $endDate)
    {
        $filename = "profit_report_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($profitData) {
            $file = fopen('php://output', 'w');
            
            // Summary section
            fputcsv($file, ['RINGKASAN LABA RUGI']);
            fputcsv($file, ['Total Penjualan', $profitData['summary']['total_sales']]);
            fputcsv($file, ['Total Pendapatan', 'Rp ' . number_format($profitData['summary']['total_revenue'], 0, ',', '.')]);
            fputcsv($file, ['Total Biaya', 'Rp ' . number_format($profitData['summary']['total_cost'], 0, ',', '.')]);
            fputcsv($file, ['Laba Kotor', 'Rp ' . number_format($profitData['summary']['gross_profit'], 0, ',', '.')]);
            fputcsv($file, ['Total Pajak', 'Rp ' . number_format($profitData['summary']['total_tax'], 0, ',', '.')]);
            fputcsv($file, ['Total Diskon', 'Rp ' . number_format($profitData['summary']['total_discount'], 0, ',', '.')]);
            fputcsv($file, ['Laba Bersih', 'Rp ' . number_format($profitData['summary']['net_profit'], 0, ',', '.')]);
            fputcsv($file, ['Margin Kotor (%)', number_format($profitData['summary']['gross_margin_percentage'], 2) . '%']);
            fputcsv($file, ['Margin Bersih (%)', number_format($profitData['summary']['net_margin_percentage'], 2) . '%']);
            fputcsv($file, []);

            // Product profit section
            fputcsv($file, ['LABA PER PRODUK']);
            fputcsv($file, ['Kode Produk', 'Nama Produk', 'Qty Terjual', 'Pendapatan', 'Biaya', 'Laba Kotor', 'Margin (%)']);
            
            foreach ($profitData['by_product'] as $product) {
                fputcsv($file, [
                    $product['product']->code,
                    $product['product']->name,
                    $product['quantity_sold'],
                    'Rp ' . number_format($product['revenue'], 0, ',', '.'),
                    'Rp ' . number_format($product['cost'], 0, ',', '.'),
                    'Rp ' . number_format($product['gross_profit'], 0, ',', '.'),
                    number_format($product['margin_percentage'], 2) . '%'
                ]);
            }
            fputcsv($file, []);

            // Outlet profit section
            fputcsv($file, ['LABA PER OUTLET']);
            fputcsv($file, ['Nama Outlet', 'Jumlah Penjualan', 'Pendapatan', 'Biaya', 'Laba Kotor', 'Laba Bersih']);
            
            foreach ($profitData['by_outlet'] as $outlet) {
                fputcsv($file, [
                    $outlet['outlet']->name,
                    $outlet['sales_count'],
                    'Rp ' . number_format($outlet['revenue'], 0, ',', '.'),
                    'Rp ' . number_format($outlet['cost'], 0, ',', '.'),
                    'Rp ' . number_format($outlet['gross_profit'], 0, ',', '.'),
                    'Rp ' . number_format($outlet['net_profit'], 0, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get profit data for AJAX requests
     */
    public function getData(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $outletId = $request->get('outlet_id');

        $profitData = $this->calculateProfit($startDate, $endDate, $outletId);

        return response()->json($profitData);
    }
}