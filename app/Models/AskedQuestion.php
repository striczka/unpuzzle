<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AskedQuestion extends Model
{
    protected $fillable = ['text', 'answer', 'order'];
	public $timestamps = false;

}
