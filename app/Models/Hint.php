<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{

    protected $fillable = ['question_id', 'order', 'info', 'published'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
