<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class offers extends Model
{
    use HasFactory;

    protected $table = "offers";


    protected $fillable = ["name", "price", "category_id"];


    /**
     * Get the category that owns the offers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Categories::class,'category_id');
    }

}
