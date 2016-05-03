<?php

if( ! Request::is('dashboard*') and ! Request::is('auth*')){


	/* Add review on front page */
	post('add/review',['uses'=>'\App\Http\Controllers\Admin\ReviewsController@store','as'=>'add.review']);

	Route::group(['namespace' => '\App\Http\Controllers\Frontend'], function()
	{
		Route::get('rate', 'FrontendController@rateProduct');

		Route::get('/', 'FrontendController@index');

		get('/news',['uses'=>'InformationController@getPage','as'=>'frontend.page']);
		get('/news/{id}/{slug}',['uses'=>'InformationController@getArticle','as'=>'frontend.article']);

		/* send mail from site */
		post("mail/me",["uses"=>"MailController@mailMe",'as'=>'mail.me']);

		Route::get('check-code',["as"=>"check.code", "uses"=>'GameController@checkCode']);
		Route::get('game', 'GameController@getGame');
		Route::get('cart', 'FrontendController@cart');
		Route::post('buy', 'FrontendController@buy');
		Route::get('thank-you', 'FrontendController@thanks');

		Route::get('new', ['as' => 'new', 'uses' => 'FrontendController@newProducts']);
		Route::get('sale', ['as' => 'sale', 'uses' => 'FrontendController@saleProducts']);
		Route::get('contacts', ['as' => 'contacts', 'uses' => 'FrontendController@contacts']);
	
		Route::get('service', ['as' => 'service', 'uses' => 'FrontendController@staticPage']);
		Route::get('about', ['as' => 'about', 'uses' => 'FrontendController@staticPage']);

		Route::get('login', ['as' => 'login', 'uses' => 'FrontendController@login']);
		Route::get('registration', ['as' => 'register', 'uses' => 'FrontendController@registration']);
		Route::get('password', ['as' => 'password', 'uses' => 'FrontendController@']);
		Route::get('cabinet', ['as' => 'cabinet', 'uses' => 'FrontendController@cabinet']);
		Route::get('search', ['as' => 'search', 'uses' => 'FrontendController@search']);

		Route::post('user_update', ['as' => 'user_update', 'uses' => 'FrontendController@updateUserData']);
		Route::get('cabinet/orders/{order_id}', ['as' => 'order', 'uses' => 'FrontendController@showOrder']);

		Route::get('{categorySlug}', 'FrontendController@catalog');
		Route::get('{categorySlug}/{productSlug}', 'FrontendController@product');

		Route::post('add_to_cart', 'CartController@addProduct');
		Route::post('add_set_to_cart', 'CartController@addSetOfProducts');
		Route::post('cart/get_content', 'CartController@getContent');
		Route::post('cart/update_item', 'CartController@updateItem');
		Route::post('cart/delete_item', 'CartController@deleteItem');

	});

}
