<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\WalletProfile;

class WalletProfile extends Model
{
    //
    protected $fillable =[
        'user_id',
        'first_name',
        'last_name',
        'phone_no',
        'Address',
        'pin',
        'bvn',
        'identification',
    ];

    public function users(){
        return $this->hasOne(User::class);
    }
}
