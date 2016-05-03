<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{

    protected $fillable = ['product_id', 'question_id', 'code', 'active','activated'];
    protected $hidden = ['code', 'remember_token'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    protected function setCodeAttribute($code)
    {
        if(empty($code)) {
            unset($this->attributes['code']);
        } else {
            $this->attributes['code'] = bcrypt($code);
        }
    }

}
