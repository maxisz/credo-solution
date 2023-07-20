<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;


    protected $fillable = ["type","amount","offer_id","rate_id","payment_id","is_fullfilled","payment_id"];
    // $table->id();
    // $table->string("type"); // offer , airtime
    // $table->integer("amount")->nullable();
    // $table->integer("offer_id")->nullable();
    // $table->integer("rate_id")->nullable();
    // $table->integer("payment_id");
    // $table->string("is_fullfilled");
    // $table->timestamps();
     public function offer()
    {
        return $this->hasOne(offers::class,'id','offer_id');
    }
     public function rate()
    {
        return $this->hasOne(offers::class,'id','rate_id');
    }


}
