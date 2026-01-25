<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractDetail extends Model
{
    protected $fillable = [
        'contract_id',
        'title',
        'value',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
