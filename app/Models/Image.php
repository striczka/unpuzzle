<?php

namespace App\Models;


class Image extends Eloquent
{
	protected $fillable = ['product_id','title','alt','path','is_thumb'];

	protected function product()
	{
		return $this->belongsTo('App\Models\Products');
	}
}
