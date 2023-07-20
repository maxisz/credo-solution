<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;

    //  "amount" => $if_r,
    // "tinypesa_account_id" => $data->account_id,
    // "tinypesa_request_id" => $data->request_id,

    protected $fillable= ["amount","tinypesa_request_id","tinypesa_account_id","tinypesa_payment_amount"];


    public function order()
    {
        return $this->hasOne(orders::class,'payment_id','id');
    }


}
