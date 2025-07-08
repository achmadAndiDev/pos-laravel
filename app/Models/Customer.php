<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'birth_date',
        'gender',
        'status',
        'total_points',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'total_points' => 'decimal:2',
        'status' => 'string',
        'gender' => 'string',
    ];

    /**
     * Scope for active customers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Accessor for full customer name
     */
    public function getFullNameAttribute(): string
    {
        return $this->code . ' - ' . $this->name;
    }

    /**
     * Accessor for age
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    /**
     * Accessor for full gender name
     */
    public function getGenderNameAttribute(): ?string
    {
        return match($this->gender) {
            'male' => 'Male',
            'female' => 'Female',
            default => null,
        };
    }

    /**
     * Mutator for customer code (auto uppercase)
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /**
     * Method to add points
     */
    public function addPoints(float $points): void
    {
        $this->increment('total_points', $points);
    }

    /**
     * Method to deduct points
     */
    public function deductPoints(float $points): bool
    {
        if ($this->total_points >= $points) {
            $this->decrement('total_points', $points);
            return true;
        }
        return false;
    }

    /**
     * Check if customer is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if customer has enough points
     */
    public function hasEnoughPoints(float $points): bool
    {
        return $this->total_points >= $points;
    }
}