<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'code' => 'CUST001',
                'name' => 'John Doe',
                'phone' => '081234567890',
                'email' => 'john.doe@example.com',
                'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'birth_date' => '1990-05-15',
                'gender' => 'male',
                'status' => 'active',
                'total_points' => 150.00,
                'notes' => 'Customer VIP, sering berbelanja',
            ],
            [
                'code' => 'CUST002',
                'name' => 'Jane Smith',
                'phone' => '081234567891',
                'email' => 'jane.smith@example.com',
                'address' => 'Jl. Sudirman No. 456, Jakarta Selatan',
                'birth_date' => '1985-08-22',
                'gender' => 'female',
                'status' => 'active',
                'total_points' => 75.50,
                'notes' => 'Pelanggan setia, suka produk fashion',
            ],
            [
                'code' => 'CUST003',
                'name' => 'Ahmad Rahman',
                'phone' => '081234567892',
                'email' => 'ahmad.rahman@example.com',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta Barat',
                'birth_date' => '1992-12-10',
                'gender' => 'male',
                'status' => 'active',
                'total_points' => 200.25,
                'notes' => 'Customer baru, potensial untuk membership',
            ],
            [
                'code' => 'CUST004',
                'name' => 'Siti Nurhaliza',
                'phone' => '081234567893',
                'email' => 'siti.nurhaliza@example.com',
                'address' => 'Jl. Thamrin No. 321, Jakarta Pusat',
                'birth_date' => '1988-03-18',
                'gender' => 'female',
                'status' => 'inactive',
                'total_points' => 25.00,
                'notes' => 'Customer tidak aktif, perlu follow up',
            ],
            [
                'code' => 'CUST005',
                'name' => 'Budi Santoso',
                'phone' => '081234567894',
                'email' => null,
                'address' => 'Jl. Kuningan No. 654, Jakarta Selatan',
                'birth_date' => null,
                'gender' => 'male',
                'status' => 'active',
                'total_points' => 0.00,
                'notes' => null,
            ],
        ];

        foreach ($customers as $customer) {
            \App\Models\Customer::create($customer);
        }
    }
}
