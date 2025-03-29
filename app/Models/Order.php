<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','total_price','status'];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class
        ];
    }

    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }

    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity', 'unit_price');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
