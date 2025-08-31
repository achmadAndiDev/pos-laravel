<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case STAF_PEMBELIAN = 'staf_pembelian';
    case STAF_PENJUALAN = 'staf_penjualan';

    /**
     * Get all role values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get role label
     */
    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::STAF_PEMBELIAN => 'Staf Pembelian',
            self::STAF_PENJUALAN => 'Staf Penjualan',
        };
    }

    /**
     * Get role description
     */
    public function description(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Akses penuh ke seluruh sistem',
            self::ADMIN => 'Akses penuh ke outlet yang ditugaskan',
            self::STAF_PEMBELIAN => 'Akses khusus untuk modul pembelian',
            self::STAF_PENJUALAN => 'Akses khusus untuk modul penjualan',
        };
    }

    /**
     * Check if role can access multiple outlets
     */
    public function canAccessMultipleOutlets(): bool
    {
        return match($this) {
            self::SUPER_ADMIN, self::ADMIN => true,
            self::STAF_PEMBELIAN, self::STAF_PENJUALAN => false,
        };
    }

    /**
     * Get permissions for this role
     */
    public function permissions(): array
    {
        return match($this) {
            self::SUPER_ADMIN => [
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'outlets.view', 'outlets.create', 'outlets.edit', 'outlets.delete',
                'products.view', 'products.create', 'products.edit', 'products.delete',
                'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
                'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
                'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.delete',
                'sales.view', 'sales.create', 'sales.edit', 'sales.delete',
                'reports.view', 'settings.manage'
            ],
            self::ADMIN => [
                'products.view', 'products.create', 'products.edit', 'products.delete',
                'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
                'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
                'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.delete',
                'sales.view', 'sales.create', 'sales.edit', 'sales.delete',
                'reports.view'
            ],
            self::STAF_PEMBELIAN => [
                'products.view',
                'purchases.view', 'purchases.create', 'purchases.edit',
                'reports.view.purchases'
            ],
            self::STAF_PENJUALAN => [
                'products.view',
                'customers.view', 'customers.create', 'customers.edit',
                'sales.view', 'sales.create', 'sales.edit',
                'reports.view.sales'
            ],
        };
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions());
    }
}