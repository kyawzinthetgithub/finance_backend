<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'icon'
    ];

    const CATEGORY_TYPE = [
        'income' => 'income',
        'expend' => 'expend'
    ];

    public function getWithType($type)
    {
        return $this->whereIn('type', self::CATEGORY_TYPE)->where('type', $type)->get();
    }
}
