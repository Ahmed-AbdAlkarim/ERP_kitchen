<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number',
        'customer_id',
        'issue_date',
        'expiry_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'status',
        'notes',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function salesInvoice()
    {
        return $this->hasOne(SalesInvoice::class, 'quotation_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

}

