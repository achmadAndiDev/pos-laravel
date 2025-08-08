<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard with filters
     */
    public function index(Request $request)
    {
        $outlets = Outlet::active()->get();
        
        // Default date range (current month)
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $outletId = $request->get('outlet_id');
        
        // Get dashboard data
        $dashboardData = $this->getDashboardData($startDate, $endDate, $outletId);
        
        return view('admin.dashboard', compact(
            'outlets',
            'dashboardData',
            'startDate',
            'endDate',
            'outletId'
        ));
    }

    /**
     * Get dashboard data based on filters
     */
    private function getDashboardData($startDate, $endDate, $outletId = null)
    {
        // Base queries
        $salesQuery = Sale::whereBetween('sale_date', [$startDate, $endDate]);
        $purchasesQuery = Purchase::whereBetween('purchase_date', [$startDate, $endDate]);
        $productsQuery = Product::query();
        $customersQuery = Customer::query();

        // Apply outlet filter
        if ($outletId) {
            $salesQuery->where('outlet_id', $outletId);
            $purchasesQuery->where('outlet_id', $outletId);
            $productsQuery->where('outlet_id', $outletId);
        }

        // Sales Statistics
        $totalSales = $salesQuery->count();
        $completedSales = (clone $salesQuery)->where('status', 'completed')->count();
        $draftSales = (clone $salesQuery)->where('status', 'draft')->count();
        $totalRevenue = (clone $salesQuery)->where('status', 'completed')->sum('total_amount');
        $todaySales = (clone $salesQuery)->whereDate('sale_date', Carbon::today())->count();

        // Purchase Statistics
        $totalPurchases = $purchasesQuery->count();
        $completedPurchases = (clone $purchasesQuery)->where('status', 'completed')->count();
        $totalPurchaseAmount = (clone $purchasesQuery)->where('status', 'completed')->sum('total_amount');

        // Product Statistics
        $totalProducts = $productsQuery->count();
        $activeProducts = (clone $productsQuery)->where('status', 'active')->count();
        $lowStockProducts = (clone $productsQuery)->whereRaw('stock <= minimum_stock')->count();

        // Customer Statistics
        $totalCustomers = $customersQuery->where('status', 'active')->count();

        // Recent Sales (last 10)
        $recentSalesQuery = Sale::with(['outlet', 'customer'])
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(10);
        
        if ($outletId) {
            $recentSalesQuery->where('outlet_id', $outletId);
        }
        
        $recentSales = $recentSalesQuery->get();

        // Top Products (by quantity sold)
        $topProductsQuery = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->where('sales.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.code',
                DB::raw('SUM(sale_items.quantity) as total_sold'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.code');

        if ($outletId) {
            $topProductsQuery->where('sales.outlet_id', $outletId);
        }

        $topProducts = $topProductsQuery->orderBy('total_sold', 'desc')->limit(5)->get();

        // Daily Sales Chart Data (last 7 days)
        $dailySalesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $salesCount = Sale::whereDate('sale_date', $date)
                ->where('status', 'completed');
            
            if ($outletId) {
                $salesCount->where('outlet_id', $outletId);
            }
            
            $dailySalesData[] = [
                'date' => $date->format('d/m'),
                'sales' => $salesCount->count(),
                'revenue' => $salesCount->sum('total_amount')
            ];
        }

        // Monthly comparison (current vs previous month)
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = $currentMonth->copy()->subDay();
        
        $currentMonthSales = Sale::whereBetween('sale_date', [$currentMonth, Carbon::now()])
            ->where('status', 'completed');
        $previousMonthSales = Sale::whereBetween('sale_date', [$previousMonth, $previousMonthEnd])
            ->where('status', 'completed');

        if ($outletId) {
            $currentMonthSales->where('outlet_id', $outletId);
            $previousMonthSales->where('outlet_id', $outletId);
        }

        $currentMonthRevenue = $currentMonthSales->sum('total_amount') ?? 0;
        $previousMonthRevenue = $previousMonthSales->sum('total_amount') ?? 0;
        
        $revenueGrowth = $previousMonthRevenue > 0 
            ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 
            : ($currentMonthRevenue > 0 ? 100 : 0);

        // Profit calculation (simplified)
        $totalCost = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->where('sales.status', 'completed')
            ->when($outletId, function($query) use ($outletId) {
                return $query->where('sales.outlet_id', $outletId);
            })
            ->sum(DB::raw('sale_items.quantity * COALESCE(products.purchase_price, 0)')) ?? 0;

        $grossProfit = $totalRevenue - $totalCost;

        return [
            'sales' => [
                'total' => $totalSales,
                'completed' => $completedSales,
                'draft' => $draftSales,
                'today' => $todaySales,
                'revenue' => $totalRevenue,
                'revenue_growth' => $revenueGrowth,
            ],
            'purchases' => [
                'total' => $totalPurchases,
                'completed' => $completedPurchases,
                'amount' => $totalPurchaseAmount,
            ],
            'products' => [
                'total' => $totalProducts,
                'active' => $activeProducts,
                'low_stock' => $lowStockProducts,
            ],
            'customers' => [
                'total' => $totalCustomers,
            ],
            'profit' => [
                'gross' => $grossProfit,
                'margin' => $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0,
            ],
            'recent_sales' => $recentSales,
            'top_products' => $topProducts,
            'daily_sales' => $dailySalesData,
        ];
    }
}