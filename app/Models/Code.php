<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $fillable = ['product_id', 'question_id', 'code', 'active','activated',
        "deleted_at","disactivated_at","in_navigation"];

    protected $hidden = ['code', 'remember_token'];

    public function scopeActive($query){
        $query->where('deleted_at', '>=', Carbon::now())
              ->orWhere('deleted_at', '=', '0000-00-00 00:00:00');
    }
    public function product()
    {
        return $this->belongsTo(Product::class)
            ->with("questions")
            ->with("codes")
            ->with("thumbnail");
    }
    public function question()
    {
        return $this->belongsTo(Question::class)->with("hints");
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
