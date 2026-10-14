<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
	    'event_id',
	    'customer_id',
	    'event_name',
	    'properties',
	    'occurred_at',
	];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
