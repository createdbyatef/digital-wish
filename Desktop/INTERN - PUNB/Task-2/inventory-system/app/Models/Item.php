<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'item_name',
        'category',
        'stock_quantity',
        'unit_price',
        'min_threshold',
        'image_path',
    ];

    public function requests()
    {
        return $this->hasMany(InventoryRequest::class, 'item_id', 'item_id');
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class, 'item_id', 'item_id');
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_threshold;
    }
}
