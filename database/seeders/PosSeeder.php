<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\ProductCategory;
use App\Models\Product;

class PosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Outlets
        $outlets = [
            [
                'name' => 'Main Store',
                'code' => 'MS001',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'phone' => '021-12345678',
                'email' => 'main@mystore.com',
                'manager' => 'Budi Santoso',
                'status' => 'active',
            ],
            [
                'name' => 'Bekasi Branch',
                'code' => 'BK001',
                'address' => 'Jl. Ahmad Yani No. 456, Bekasi',
                'phone' => '021-87654321',
                'email' => 'bekasi@mystore.com',
                'manager' => 'Siti Nurhaliza',
                'status' => 'active',
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }

        // Seed Customers
        $customers = [
            [
                'code' => 'CUST001',
                'name' => 'Ahmad Wijaya',
                'phone' => '08123456789',
                'email' => 'ahmad@email.com',
                'address' => 'Jl. Merdeka No. 789',
                'birth_date' => '1985-05-15',
                'gender' => 'male',
                'status' => 'active',
                'total_points' => 150.00,
            ],
            [
                'code' => 'CUST002',
                'name' => 'Sari Dewi',
                'phone' => '08198765432',
                'email' => 'sari@email.com',
                'address' => 'Jl. Pahlawan No. 321',
                'birth_date' => '1990-08-22',
                'gender' => 'female',
                'status' => 'active',
                'total_points' => 75.50,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Seed Product Categories
        $categories = [
            [
                'name' => 'Food',
                'code' => 'FOOD',
                'description' => 'Food products category',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'name' => 'Beverages',
                'code' => 'BEV',
                'description' => 'Beverage products category',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'name' => 'Snacks',
                'code' => 'SNACK',
                'description' => 'Snack and light food category',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'name' => 'Electronics',
                'code' => 'ELEC',
                'description' => 'Electronic products category',
                'status' => 'active',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }

        // Seed Products
        $products = [
            // Products for Main Store
            [
                'outlet_id' => 1,
                'product_category_id' => 1, // Food
                'code' => 'FOOD001',
                'name' => 'Nasi Gudeg',
                'description' => 'Traditional Yogyakarta rice dish',
                'barcode' => '1234567890123',
                'purchase_price' => 15000.00,
                'selling_price' => 25000.00,
                'stock' => 50,
                'minimum_stock' => 10,
                'unit' => 'portion',
                'status' => 'active',
                'is_sellable' => true,
                'weight' => 300.00,
            ],
            [
                'outlet_id' => 1,
                'product_category_id' => 2, // Beverages
                'code' => 'BEV001',
                'name' => 'Iced Sweet Tea',
                'description' => 'Fresh iced sweet tea',
                'barcode' => '1234567890124',
                'purchase_price' => 3000.00,
                'selling_price' => 8000.00,
                'stock' => 100,
                'minimum_stock' => 20,
                'unit' => 'glass',
                'status' => 'active',
                'is_sellable' => true,
                'weight' => 250.00,
            ],
            [
                'outlet_id' => 1,
                'product_category_id' => 3, // Snacks
                'code' => 'SNACK001',
                'name' => 'Cassava Chips',
                'description' => 'Original flavor cassava chips',
                'barcode' => '1234567890125',
                'purchase_price' => 8000.00,
                'selling_price' => 15000.00,
                'stock' => 75,
                'minimum_stock' => 15,
                'unit' => 'pack',
                'status' => 'active',
                'is_sellable' => true,
                'weight' => 100.00,
            ],
            // Products for Bekasi Branch
            [
                'outlet_id' => 2,
                'product_category_id' => 1, // Food
                'code' => 'FOOD002',
                'name' => 'Ayam Geprek',
                'description' => 'Spicy smashed chicken with rice',
                'barcode' => '1234567890126',
                'purchase_price' => 18000.00,
                'selling_price' => 30000.00,
                'stock' => 40,
                'minimum_stock' => 8,
                'unit' => 'portion',
                'status' => 'active',
                'is_sellable' => true,
                'weight' => 350.00,
            ],
            [
                'outlet_id' => 2,
                'product_category_id' => 2, // Beverages
                'code' => 'BEV002',
                'name' => 'Orange Juice',
                'description' => 'Fresh orange juice without sugar',
                'barcode' => '1234567890127',
                'purchase_price' => 5000.00,
                'selling_price' => 12000.00,
                'stock' => 60,
                'minimum_stock' => 12,
                'unit' => 'glass',
                'status' => 'active',
                'is_sellable' => true,
                'weight' => 300.00,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}