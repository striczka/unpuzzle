<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\Hint;
use App\Models\Question;
use App\Models\Product;
use App\Services\SimplifyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

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
        $codes = Code::active()->with("product")->get();
        foreach($codes as $code){
            if (Hash::check($pass, $code->code)) {
                if($code->activated=="0"){
                    $current = Carbon::now("Europe/Kiev");
                    $trialExpires = $current->addDays(10);
                    $code->update(["deleted_at" => $trialExpires, "activated" => "1", "active" => "1"]);
                }
                if(count($code->question) > 0){
                    $usedCode = Code::find($code->id)->with("product")
                                    ->with("question")->first();

                    return \Response::json($usedCode);
                }
                else{
                    return \Response::json($code);
                }
            }
        }
        return null;
	}
    public function checkAnswer(Request $request, SimplifyService $simplifyService) {
        $answer = $request->get("answer");
        $id = $request->get("data-id");
        $code_id = $request->get("codeId");
        $val = Question::find($id);
        $rightAnswer = $val->answer;
        $answer = $simplifyService->simplify($answer);
        $rightAnswer = $simplifyService->simplify($rightAnswer);
        $code = Code::find($code_id);
        if($rightAnswer==$answer) {
            $nextQuestion = Question::where("order", ">", $val->order)->with("hints")->first();
            if($nextQuestion) {
                $code->update(["question_id"=>$nextQuestion->id]);
                return \Response::json($nextQuestion);
            }
            else{
                if($code->active == "1"){
                    $current = Carbon::now("Europe/Kiev");
                    $trialExpires = $current->addDays(1);
                    $code->update(["deleted_at" => $trialExpires, "active" => "0"]);
                }
                $code->update(["question_id" => "0"]);
                return Response::json($code->deleted_at);
            }
        }
        else{
            return \Response::json("");
        }
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
    public function getHint($id) {
        $hint = Hint::find($id);
        return Response::json($hint);
	}
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

