<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPER_ADMIN,
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // Create Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'phone' => '081234567891',
            'is_active' => true,
        ]);

        // Create Staff Pembelian
        $staffPembelian = User::create([
            'name' => 'Staff Pembelian',
            'email' => 'pembelian@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::STAF_PEMBELIAN,
            'phone' => '081234567892',
            'is_active' => true,
        ]);

        // Create Staff Penjualan
        $staffPenjualan = User::create([
            'name' => 'Staff Penjualan',
            'email' => 'penjualan@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::STAF_PENJUALAN,
            'phone' => '081234567893',
            'is_active' => true,
        ]);

        // Assign outlets if they exist
        $outlets = Outlet::all();
        
        if ($outlets->count() > 0) {
            // Admin can access all outlets
            $admin->outlets()->sync($outlets->pluck('id')->toArray());
            
            // Staff only get first outlet
            if ($outlets->count() >= 1) {
                $staffPembelian->outlets()->sync([$outlets->first()->id]);
                $staffPenjualan->outlets()->sync([$outlets->first()->id]);
            }
        }

        $this->command->info('Users created successfully!');
        $this->command->info('Super Admin: superadmin@example.com / password');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Staff Pembelian: pembelian@example.com / password');
        $this->command->info('Staff Penjualan: penjualan@example.com / password');
    }
}
