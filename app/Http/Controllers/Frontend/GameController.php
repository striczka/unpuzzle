<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class GameController
 * @package App\Http\Controllers\Frontend
 */
class GameController extends Controller {

	/**
	 * @var Game
	 */
//	private $cart;
	//
	//	public function __construct(Game $cart)
	//	{
	//		$this->cart = $cart;
	//	}

    /**
     * @param Request $request
     * @return array
     */
    public function getGame(Request $request) {
		return view('frontend.game');
	}
    public function checkCode(Request $request) {
        $pass = $request->get("code");
      //  $crypt = bcrypt($code);
//        $hashed = Hash::make($code);
        $codes = Code::all();
        foreach($codes as $code){
            if (Hash::check($pass, $code->code)) return $code->product->questions;
        }
        //$codeString = Code::where("code",$crypt)->get();
        //if(!empty($codeString)) return $codeString;
        return null;
        //return $hashed;
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

			Game::instance($sessionStockKey)->add(
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

		return ['count' => $this->calcProductsInGame(), 'total' => $this->calcTotalPrice()];
	}

    /**
     * @return array
     */
    public function getContent() {
        return [
			'content' => Game::content(),
			'stockProducts' => $this->getStockSets(),
			'len' => $this->calcProductsInGame(),
			'total' => $this->calcTotalPrice(),
		];
	}

    /**
     * @param Request $request
     */
    public function updateItem(Request $request) {
		$product = $request->get('product');
        $instance = $request->get('instance');
        $product = Game::instance($instance)->get($product['rowid']);
		Game::instance($instance)
                    ->update($product['rowid'], $request->get('qty'));

        $productsInSet = Game::instance($instance)->search(['options' => ['main_in_set' => $product->id]]);

        if($productsInSet){
            foreach($productsInSet as $rowId){
                Game::instance($instance)->update($rowId, $request->get('qty'));
            }
        }
	}

    /**
     * @param Request $request
     * @return array
     */
    public function deleteItem(Request $request) {
        $rowId = $request->get('rowid');

        $product = Game::get($rowId);

//        dd(session('stocks'));

        if(!$product){
            foreach(session('stocks') as $instance){
                $product = Game::instance($instance)->get($rowId);
                if($product) break;
            }
        }

        if($product->options->main_in_set == $product->id) {
            Game::instance($product->options->instance)->destroy();
            $request->session()->forget('stocks.'.$product->options->stock);
        } else {
            Game::remove($product->rowid);
        }

		return [true];
	}

    /**
     * @return mixed
     */
    public function calcProductsInGame() {
		$count = 0;
        if(session('stocks')) {
            foreach (session('stocks') as $stockInstance) {
                $count += Game::instance($stockInstance)->count();
            }
        }
        return Game::instance('main')->count() + $count;
	}


    /**
     * @return mixed
     */
    public function calcTotalPrice()
    {
        $stockProductsTotal = [];
        if(session('stocks')) {
            foreach (session('stocks') as $stockInstance) {
                $stockProductsTotal[] = Game::instance($stockInstance)->total();
            }
        }
        $sum = Game::instance('main')->total() + array_sum($stockProductsTotal);
        return $sum;
    }

    public function getStockSets()
    {
        $products = [];
        if(session('stocks')){
            foreach (session('stocks') as $stockInstance) {
                $products[] = Game::instance($stockInstance)->content();
            }
        }
       return $products;
    }


    public function destroyGame()
    {
        Game::destroy();
        if(session('stocks')){
            foreach (session('stocks') as $stockInstance) {
                Game::instance($stockInstance)->destroy();
            }
        }
        session()->forget('stocks');
    }



}

