<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function prices()
    {
        return $this->hasMany('App\Models\Price');
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
        : static::where('name', 'like', '%'.$query.'%')
        ->orWhere('barcode', 'like', '%'.$query.'%');
    }
}
