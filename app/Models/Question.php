<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['product_id',"question", 'order', 'answer', 'info','published'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function codes()
    {
        return $this->hasMany(Code::class);
    }
    public function hints()
    {
        return $this->hasMany(Hint::class);
    }
}
