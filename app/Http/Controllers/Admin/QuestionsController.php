<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hint;
use App\Models\Product;
use App\Models\Question;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class QuestionsController extends AdminController
{
	public $productService;

	function __construct(ProductService $productService)
	{
		$this->productService = $productService;
		parent::__construct();
	}


	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
	    $questions = Question::all();
        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.questions.create');
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request|Request $request
	 * @return Response
	 */
    public function store(Request $request)
    {
	    $question = Question::create($request->all());
	    return redirect()->route('dashboard.questions.edit', $question->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
	    return view('admin.questions.edit', compact('question'));
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request|Request $request
	 * @param  int $id
	 * @return Response
	 */
    public function update(Request $request, $id)
    {

	    $question = Question::find($id);
		if($request->get('example') == 1) {
			$questions = Question::where("product_id", $request->get("product_id"))->get();
			foreach($questions as $s){
				$s->update(["example" => null]);
			}
		}
		$question->update($request->all());
		if((int)$request->get('button')) {
			return redirect()->route('dashboard.questions.index')->withMessage('');
		}
	    return redirect()->route('dashboard.questions.edit', $question->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
	    $question = Question::find($id);
	    $question->products()->update(['question_id' => null]);
        $question->delete();
	    return redirect()->back();
    }

	public function getHints($id){
		$hints = Hint::where("question_id", $id)->get();
		return $hints;
	}
	public function deleteHint($id){
		Hint::find($id)->delete();
		return "done";
	}
	public function createHint(Request $request, $id){
		$info = $request->get("info");
		$minOrder = Hint::where("question_id",$id)->max("order") ? : "1";
		$minOrder++;
		$order = $request->get("order") ? : $minOrder;
		$hint = Hint::create(["info" => $info, "order" => $order, "question_id" => $id]);
		return Response::json($hint);
	}
	public function changeHint(Request $request, $id){
		$info = $request->get("info");
		$order = $request->get("order");
		Hint::find($id)->update(["info" => $info, "order" => $order]);
		return Response::json("done");
	}


}

