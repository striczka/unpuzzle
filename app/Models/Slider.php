<?php namespace App\Models;

class Slider extends Eloquent
{
	protected $fillable = [
		'title',
		'subtitle',
		'caption',
		'thumbnail',
		'link',
		'show',
		'alt',
		'order'
	];

}
