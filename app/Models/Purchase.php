<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'outlet_id',
        'supplier_name',
        'supplier_phone',
        'supplier_address',
        'purchase_date',
        'invoice_number',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relationship to outlet
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Relationship to purchase items
     */
    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Scope for completed purchases
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for draft purchases
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for purchases by outlet
     */
    public function scopeByOutlet($query, $outletId)
    {
        return $query->where('outlet_id', $outletId);
    }

    /**
     * Scope for purchases by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }

    /**
     * Generate purchase code
     */
    public static function generateCode(): string
    {
        $date = Carbon::now()->format('Ymd');
        $prefix = 'PO-' . $date . '-';
        
        $lastPurchase = self::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();
        
        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->code, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate totals from items
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->purchaseItems->sum('total_price');
        
        $this->update([
            'subtotal' => $subtotal,
            'total_amount' => $subtotal + $this->tax_amount - $this->discount_amount,
        ]);
    }

    /**
     * Complete purchase and update stock
     */
    public function complete(): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        try {
            \DB::transaction(function () {
                // Update product stocks
                foreach ($this->purchaseItems as $item) {
                    $product = $item->product;
                    $product->addStock($item->quantity);
                    
                    // Update product purchase price if needed
                    $product->update([
                        'purchase_price' => $item->unit_price
                    ]);
                }

                // Update purchase status
                $this->update(['status' => 'completed']);
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Cancel purchase
     */
    public function cancel(): bool
    {
        if ($this->status === 'completed') {
            return false; // Cannot cancel completed purchase
        }

        return $this->update(['status' => 'cancelled']);
    }

    /**
     * Check if purchase can be edited
     */
    public function canBeEdited(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if purchase can be completed
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'draft' && $this->purchaseItems->count() > 0;
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-yellow-lt',
            'completed' => 'bg-green-lt',
            'cancelled' => 'bg-red-lt',
            default => 'bg-gray-lt'
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }
}
