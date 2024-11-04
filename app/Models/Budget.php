<?php

namespace App\Models;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'total',
        'spend_amount',
        'remaining_amount',
        'alert',
        'usage',
        'expired_at'
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
