<?php

namespace App\ViewDataProviders;


use App\Models\PaymentMethod;
use App\Models\ShipmentMethod;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartDataProvider {


	public function getContent()
	{
		return Cart::content();
	}


	public function getTotal()
	{
		return Cart::total();
	}


	public function getPaymentMethods()
	{
		return PaymentMethod::all();
	}

	public function getShipmentMethods()
	{
		return ShipmentMethod::all();
	}
}