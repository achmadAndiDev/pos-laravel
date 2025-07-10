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

class SalesCalculationController extends Controller
{
    /**
     * Display sales calculation page
     */
    public function index(Request $request)
    {
        $outlets = Outlet::where('status', 'active')->get();
        
        // Default date range (current month)
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $outletId = $request->get('outlet_id');
        
        // Get sales data
        $salesData = $this->calculateSales($startDate, $endDate, $outletId);
        
        return view('admin.sales-calculation.index', compact(
            'outlets', 
            'salesData', 
            'startDate', 
            'endDate', 
            'outletId'
        ));
    }

    /**
     * Calculate sales data
     */
    private function calculateSales($startDate, $endDate, $outletId = null)
    {
        // Base query for completed sales
        $salesQuery = Sale::with(['saleItems.product', 'outlet', 'customer'])
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if ($outletId) {
            $salesQuery->where('outlet_id', $outletId);
        }

        $sales = $salesQuery->get();

        // Initialize data
        $totalSales = $sales->count();
        $totalRevenue = 0;
        $totalTax = 0;
        $totalDiscount = 0;
        $totalItems = 0;
        $salesByProduct = [];
        $salesByOutlet = [];
        $salesByCustomer = [];
        $dailySales = [];
        $paymentMethods = [];

        foreach ($sales as $sale) {
            $totalRevenue += $sale->total_amount;
            $totalTax += $sale->tax_amount;
            $totalDiscount += $sale->discount_amount;

            // Payment methods
            if (!isset($paymentMethods[$sale->payment_method])) {
                $paymentMethods[$sale->payment_method] = [
                    'method' => $sale->payment_method,
                    'count' => 0,
                    'total_amount' => 0,
                ];
            }
            $paymentMethods[$sale->payment_method]['count']++;
            $paymentMethods[$sale->payment_method]['total_amount'] += $sale->total_amount;

            // Sales by outlet
            $outletKey = $sale->outlet_id;
            if (!isset($salesByOutlet[$outletKey])) {
                $salesByOutlet[$outletKey] = [
                    'outlet' => $sale->outlet,
                    'sales_count' => 0,
                    'total_revenue' => 0,
                    'total_items' => 0,
                    'avg_transaction' => 0,
                ];
            }
            $salesByOutlet[$outletKey]['sales_count']++;
            $salesByOutlet[$outletKey]['total_revenue'] += $sale->total_amount;

            // Sales by customer
            if ($sale->customer_id) {
                $customerKey = $sale->customer_id;
                if (!isset($salesByCustomer[$customerKey])) {
                    $salesByCustomer[$customerKey] = [
                        'customer' => $sale->customer,
                        'sales_count' => 0,
                        'total_revenue' => 0,
                        'total_items' => 0,
                        'avg_transaction' => 0,
                    ];
                }
                $salesByCustomer[$customerKey]['sales_count']++;
                $salesByCustomer[$customerKey]['total_revenue'] += $sale->total_amount;
            }

            // Daily sales
            $dateKey = $sale->sale_date->format('Y-m-d');
            if (!isset($dailySales[$dateKey])) {
                $dailySales[$dateKey] = [
                    'date' => $sale->sale_date,
                    'sales_count' => 0,
                    'total_revenue' => 0,
                    'total_items' => 0,
                    'avg_transaction' => 0,
                ];
            }
            $dailySales[$dateKey]['sales_count']++;
            $dailySales[$dateKey]['total_revenue'] += $sale->total_amount;

            // Process sale items
            foreach ($sale->saleItems as $item) {
                $totalItems += $item->quantity;

                // Sales by product
                $productKey = $item->product_id;
                if (!isset($salesByProduct[$productKey])) {
                    $salesByProduct[$productKey] = [
                        'product' => $item->product,
                        'quantity_sold' => 0,
                        'total_revenue' => 0,
                        'sales_count' => 0,
                        'avg_price' => 0,
                    ];
                }
                $salesByProduct[$productKey]['quantity_sold'] += $item->quantity;
                $salesByProduct[$productKey]['total_revenue'] += $item->total_price;
                $salesByProduct[$productKey]['sales_count']++;

                // Add to outlet items
                $salesByOutlet[$outletKey]['total_items'] += $item->quantity;

                // Add to customer items
                if ($sale->customer_id) {
                    $salesByCustomer[$customerKey]['total_items'] += $item->quantity;
                }

                // Add to daily items
                $dailySales[$dateKey]['total_items'] += $item->quantity;
            }
        }

        // Calculate averages
        foreach ($salesByProduct as &$product) {
            $product['avg_price'] = $product['quantity_sold'] > 0 ? $product['total_revenue'] / $product['quantity_sold'] : 0;
        }

        foreach ($salesByOutlet as &$outlet) {
            $outlet['avg_transaction'] = $outlet['sales_count'] > 0 ? $outlet['total_revenue'] / $outlet['sales_count'] : 0;
        }

        foreach ($salesByCustomer as &$customer) {
            $customer['avg_transaction'] = $customer['sales_count'] > 0 ? $customer['total_revenue'] / $customer['sales_count'] : 0;
        }

        foreach ($dailySales as &$daily) {
            $daily['avg_transaction'] = $daily['sales_count'] > 0 ? $daily['total_revenue'] / $daily['sales_count'] : 0;
        }

        // Sort data
        uasort($salesByProduct, function($a, $b) {
            return $b['quantity_sold'] <=> $a['quantity_sold'];
        });

        uasort($salesByOutlet, function($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        uasort($salesByCustomer, function($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        ksort($dailySales);

        return [
            'summary' => [
                'total_sales' => $totalSales,
                'total_revenue' => $totalRevenue,
                'total_tax' => $totalTax,
                'total_discount' => $totalDiscount,
                'total_items' => $totalItems,
                'avg_transaction' => $totalSales > 0 ? $totalRevenue / $totalSales : 0,
                'avg_items_per_sale' => $totalSales > 0 ? $totalItems / $totalSales : 0,
            ],
            'by_product' => array_values($salesByProduct),
            'by_outlet' => array_values($salesByOutlet),
            'by_customer' => array_values($salesByCustomer),
            'daily' => array_values($dailySales),
            'payment_methods' => array_values($paymentMethods),
        ];
    }

    /**
     * Export sales data to CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $outletId = $request->get('outlet_id');

        $salesData = $this->calculateSales($startDate, $endDate, $outletId);

        $filename = "sales_report_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($salesData) {
            $file = fopen('php://output', 'w');
            
            // Summary section
            fputcsv($file, ['RINGKASAN PENJUALAN']);
            fputcsv($file, ['Total Penjualan', $salesData['summary']['total_sales']]);
            fputcsv($file, ['Total Pendapatan', 'Rp ' . number_format($salesData['summary']['total_revenue'], 0, ',', '.')]);
            fputcsv($file, ['Total Item Terjual', $salesData['summary']['total_items']]);
            fputcsv($file, ['Rata-rata Transaksi', 'Rp ' . number_format($salesData['summary']['avg_transaction'], 0, ',', '.')]);
            fputcsv($file, ['Rata-rata Item per Penjualan', number_format($salesData['summary']['avg_items_per_sale'], 2)]);
            fputcsv($file, []);

            // Product sales section
            fputcsv($file, ['PENJUALAN PER PRODUK']);
            fputcsv($file, ['Kode Produk', 'Nama Produk', 'Qty Terjual', 'Total Pendapatan', 'Jumlah Transaksi', 'Harga Rata-rata']);
            
            foreach ($salesData['by_product'] as $product) {
                fputcsv($file, [
                    $product['product']->code,
                    $product['product']->name,
                    $product['quantity_sold'],
                    'Rp ' . number_format($product['total_revenue'], 0, ',', '.'),
                    $product['sales_count'],
                    'Rp ' . number_format($product['avg_price'], 0, ',', '.')
                ]);
            }
            fputcsv($file, []);

            // Outlet sales section
            fputcsv($file, ['PENJUALAN PER OUTLET']);
            fputcsv($file, ['Nama Outlet', 'Jumlah Penjualan', 'Total Pendapatan', 'Total Item', 'Rata-rata Transaksi']);
            
            foreach ($salesData['by_outlet'] as $outlet) {
                fputcsv($file, [
                    $outlet['outlet']->name,
                    $outlet['sales_count'],
                    'Rp ' . number_format($outlet['total_revenue'], 0, ',', '.'),
                    $outlet['total_items'],
                    'Rp ' . number_format($outlet['avg_transaction'], 0, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get sales data for AJAX requests
     */
    public function getData(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $outletId = $request->get('outlet_id');

        $salesData = $this->calculateSales($startDate, $endDate, $outletId);

        return response()->json($salesData);
    }
}