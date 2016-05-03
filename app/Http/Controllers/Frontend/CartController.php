<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

/**
 * Class CartController
 * @package App\Http\Controllers\Frontend
 */
class CartController extends Controller {

	/**
	 * @var Cart
	 */
//	private $cart;
	//
	//	public function __construct(Cart $cart)
	//	{
	//		$this->cart = $cart;
	//	}

    /**
     * @param Request $request
     * @return array
     */
    public function addProduct(Request $request) {
		$product = Product::visible()->with('relevantSale', 'thumbnail', 'category')->find($request->get('productId'));

		Cart::add(
			$id = $product->clone_of ?: $product->id,
			$title = $product->title,
			$qty = 1,
			$price = str_replace(' ', '', $product->hasDiscount() ? $product->getNewPrice() : $product->getPrice()),
			$options = [
                'instance' => 'main',
				'excerpt' => $product->excerpt,
				'article' => $product->article,
				'thumbnail' => count($product->thumbnail) ? $product->thumbnail->first()->path : '',
				'categorySlug' => $product->category->slug,
				'productSlug' => $product->slug,
			]);

		return ['count' => $this->calcProductsInCart(), 'total' => $this->calcTotalPrice()];
	}

    /**
     * @param Request $request
     * @return array
     */
    public function addSetOfProducts(Request $request) {

		$stock = Stock::with('uniqueProducts.thumbnail', 'uniqueProducts.category', 'uniqueProducts.relevantSale')
                        ->findOrFail($request->get('stockId'));
		
        $products = $stock->uniqueProducts;
		$sessionStockKey = 'stock_' . $stock->id; //bcrypt('stock_' . $stock->id);

		foreach ($products as $product) {

			if (str_replace(' ', '', $product->getNewPrice()) > str_replace(' ', '', $product->getStockPrice())) {
				$price = str_replace(' ', '', $product->getStockPrice());
			} else {
				$price = str_replace(' ', '', $product->hasDiscount() ? $product->getNewPrice() : $product->getPrice());
			}

			Cart::instance($sessionStockKey)->add(
				$id = $product->clone_of ?: $product->id,
				$title = $product->title,
				$qty = 1,
				$price = $price,
				$options = [
                    'stock' => $stock->id,
                    'instance' => $sessionStockKey,
					'main_in_set' => $products->first()->id,
					'excerpt' => $product->excerpt,
					'article' => $product->article,
					'thumbnail' => count($product->thumbnail) ? $product->thumbnail->first()->path : '',
					'categorySlug' => $product->category->slug,
					'productSlug' => $product->slug,
				]);
		}

		session()->put('stocks.' . $stock->id, $sessionStockKey);

		return ['count' => $this->calcProductsInCart(), 'total' => $this->calcTotalPrice()];
	}

    /**
     * @return array
     */
    public function getContent() {
        return [
			'content' => Cart::content(),
			'stockProducts' => $this->getStockSets(),
			'len' => $this->calcProductsInCart(),
			'total' => $this->calcTotalPrice(),
		];
	}

    /**
     * @param Request $request
     */
    public function updateItem(Request $request) {
		$product = $request->get('product');
        $instance = $request->get('instance');
        $product = Cart::instance($instance)->get($product['rowid']);
		Cart::instance($instance)
                    ->update($product['rowid'], $request->get('qty'));

        $productsInSet = Cart::instance($instance)->search(['options' => ['main_in_set' => $product->id]]);

        if($productsInSet){
            foreach($productsInSet as $rowId){
                Cart::instance($instance)->update($rowId, $request->get('qty'));
            }
        }
	}

    /**
     * @param Request $request
     * @return array
     */
    public function deleteItem(Request $request) {
        $rowId = $request->get('rowid');

        $product = Cart::get($rowId);

//        dd(session('stocks'));

        if(!$product){
            foreach(session('stocks') as $instance){
                $product = Cart::instance($instance)->get($rowId);
                if($product) break;
            }
        }

        if($product->options->main_in_set == $product->id) {
            Cart::instance($product->options->instance)->destroy();
            $request->session()->forget('stocks.'.$product->options->stock);
        } else {
            Cart::remove($product->rowid);
        }

		return [true];
	}

    /**
     * @return mixed
     */
    public function calcProductsInCart() {
		$count = 0;
        if(session('stocks')) {
            foreach (session('stocks') as $stockInstance) {
                $count += Cart::instance($stockInstance)->count();
            }
        }
        return Cart::instance('main')->count() + $count;
	}


    /**
     * @return mixed
     */
    public function calcTotalPrice()
    {
        $stockProductsTotal = [];
        if(session('stocks')) {
            foreach (session('stocks') as $stockInstance) {
                $stockProductsTotal[] = Cart::instance($stockInstance)->total();
            }
        }
        $sum = Cart::instance('main')->total() + array_sum($stockProductsTotal);
        return $sum;
    }

    public function getStockSets()
    {
        $products = [];
        if(session('stocks')){
            foreach (session('stocks') as $stockInstance) {
                $products[] = Cart::instance($stockInstance)->content();
            }
        }
       return $products;
    }


    public function destroyCart()
    {
        Cart::destroy();
        if(session('stocks')){
            foreach (session('stocks') as $stockInstance) {
                Cart::instance($stockInstance)->destroy();
            }
        }
        session()->forget('stocks');
    }



}

