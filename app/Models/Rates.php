<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rates extends Model
{
    use HasFactory;

    protected $table = "rates";
    protected $fillable = [
        "balance",
        "provider",
        "is_buying",
        "is_selling",
        "selling_rate",
        "buying_rate",
    ];
}
