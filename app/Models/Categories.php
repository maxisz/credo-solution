<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    

    protected $table = "categories";

    protected $fillable = [
        "title", "slug", "provider_id" , "description"
    ];



    public function offers()
    {
        return $this->hasMany(offers::class,'category_id');
    }
}
