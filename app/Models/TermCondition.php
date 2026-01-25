<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermCondition extends Model
{
    protected $table = 'terms_conditions';

    protected $fillable = [
        'term',
        'active',
        'sort_order',
    ];
}
