<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = ['title', 'content', 'meta_title', 'meta_keywords', 'meta_description'];
}
