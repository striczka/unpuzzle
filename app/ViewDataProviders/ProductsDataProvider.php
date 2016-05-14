<?php

namespace App\ViewDataProviders;


use App\Models\Article;
use App\Models\Product;
use App\Models\Review;
use App\Services\ProductService;

class ProductsDataProvider {


	public static $saved = [];
	/**
	 * @var
	 */
	private $productService;

	/**
	 * @param ProductService $productService
	 */
	function __construct(ProductService $productService)
	{
		$this->productService = $productService;
	}


	public function __get($property)
	{
		isset(static::$saved[$property]) ?: static::$saved[$property] = $this->productService->{'get'. ucfirst($property)}();
		return static::$saved[$property];
	}


	public function getMaxPrice($categoryId)
	{
		if($categoryId){
			$price = Product::where('category_id', $categoryId)->visible()->max('price');
		} else {
			$price = Product::max('price');
		}

		return $price;
	}
	public function getQuestsForMenu()
	{
		$products = Product::visible()
			->paginate(5);

		return $products;
	}
	public function getArticlesForMenu()
	{
		$articles = Article::where('published_at','<=', date('Y-m-d'))->orderBy('published_at','desc')->paginate(5);

		return $articles;
	}


}