<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'outlet_id',
        'product_category_id',
        'code',
        'name',
        'description',
        'barcode',
        'purchase_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'unit',
        'image',
        'status',
        'is_sellable',
        'weight',
        'notes',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
        'status' => 'string',
        'is_sellable' => 'boolean',
        'weight' => 'decimal:2',
    ];

    /**
     * Relationship to outlet
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Relationship to product category
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for sellable products
     */
    public function scopeSellable($query)
    {
        return $query->where('is_sellable', true);
    }

    /**
     * Scope for products by outlet
     */
    public function scopeByOutlet($query, $outletId)
    {
        return $query->where('outlet_id', $outletId);
    }

    /**
     * Scope for products with low stock
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    /**
     * Scope for out of stock products
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Accessor for full product name
     */
    public function getFullNameAttribute(): string
    {
        return $this->code . ' - ' . $this->name;
    }

    /**
     * Accessor for profit margin percentage
     */
    public function getMarginPercentageAttribute(): float
    {
        if ($this->purchase_price > 0) {
            return (($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100;
        }
        return 0;
    }

    /**
     * Accessor for profit amount per unit
     */
    public function getProfitAmountAttribute(): float
    {
        return $this->selling_price - $this->purchase_price;
    }

    /**
     * Accessor for stock status
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= $this->minimum_stock) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Accessor for formatted selling price
     */
    public function getFormattedSellingPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->selling_price, 0, ',', '.');
    }

    /**
     * Accessor for formatted purchase price
     */
    public function getFormattedPurchasePriceAttribute(): string
    {
        return 'Rp ' . number_format($this->purchase_price, 0, ',', '.');
    }

    /**
     * Mutator for product code (auto uppercase)
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /**
     * Method to add stock
     */
    public function addStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
    }

    /**
     * Method to reduce stock
     */
    public function reduceStock(int $quantity): bool
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Method to check if stock is sufficient
     */
    public function hasEnoughStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Check if product is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if product is sellable
     */
    public function isSellable(): bool
    {
        return $this->is_sellable;
    }

    /**
     * Check if product is available for sale
     */
    public function isAvailableForSale(): bool
    {
        return $this->isActive() && $this->isSellable() && $this->stock > 0;
    }
}