<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransaction extends Model
{
    //
    use SoftDeletes;
    protected $fillable =[
        'user_id',
        'refno',
        'narration',
        'record_type_id',
        'amount',
    ];
}
