<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'outlet_id',
        'customer_id',
        'sale_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /**
     * Relationship to outlet
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Relationship to customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relationship to sale items
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Scope for completed sales
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for draft sales
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for sales by outlet
     */
    public function scopeByOutlet($query, $outletId)
    {
        return $query->where('outlet_id', $outletId);
    }

    /**
     * Scope for sales by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    /**
     * Generate sale code
     */
    public static function generateCode(): string
    {
        $date = Carbon::now()->format('Ymd');
        $prefix = 'SO-' . $date . '-';
        
        $lastSale = self::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();
        
        if ($lastSale) {
            $lastNumber = (int) substr($lastSale->code, -3);
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
        $subtotal = $this->saleItems->sum('total_price');
        
        $this->update([
            'subtotal' => $subtotal,
            'total_amount' => $subtotal + $this->tax_amount - $this->discount_amount,
        ]);
    }

    /**
     * Complete sale and update stock
     */
    public function complete(): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        try {
            \DB::transaction(function () {
                // Update product stocks
                foreach ($this->saleItems as $item) {
                    $product = $item->product;
                    $product->reduceStock($item->quantity);
                }

                // Update sale status
                $this->update(['status' => 'completed']);

                // Add customer points if customer exists
                if ($this->customer_id) {
                    $points = floor($this->total_amount / 10000); // 1 point per 10,000 rupiah
                    if ($points > 0) {
                        $this->customer->addPoints($points);
                    }
                }
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Cancel sale
     */
    public function cancel(): bool
    {
        if ($this->status === 'completed') {
            return false; // Cannot cancel completed sale
        }

        return $this->update(['status' => 'cancelled']);
    }

    /**
     * Check if sale can be edited
     */
    public function canBeEdited(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if sale can be completed
     */
    public function canBeCompleted(): bool
    {
        return $this->status === 'draft' && $this->saleItems->count() > 0;
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
     * Get formatted paid amount
     */
    public function getFormattedPaidAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->paid_amount, 0, ',', '.');
    }

    /**
     * Get formatted change amount
     */
    public function getFormattedChangeAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->change_amount, 0, ',', '.');
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

    /**
     * Get payment method text
     */
    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Tunai',
            'card' => 'Kartu',
            'transfer' => 'Transfer',
            'e_wallet' => 'E-Wallet',
            default => 'Unknown'
        };
    }
}
