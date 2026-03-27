<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    protected $table = 'requests';
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity_requested',
        'status',
        'remarks',
        'read_at',
        'request_date',
        'action_date',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'request_date' => 'datetime',
        'action_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}
