<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'customer_id',
        'quotation_id',
        'delivery_date',
        'notes',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function details()
    {
        return $this->hasMany(ContractDetail::class);
    }
}
