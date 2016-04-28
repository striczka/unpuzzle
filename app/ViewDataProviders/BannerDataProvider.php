<?php

namespace App\ViewDataProviders;


use App\Models\Banner;
use Illuminate\Support\Facades\Cache;

class BannerDataProvider {

	protected $cacheLifetime = 0;
	public static $savedBanner = null;

	public function getBanner(){
				return Banner::show()->orderBy('order','desc')->get();
	}

}