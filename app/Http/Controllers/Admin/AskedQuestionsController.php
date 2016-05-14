<?php

namespace App\Http\Controllers\Admin;

use App\Models\AskedQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Filter;

/**
 * Class AskedQuestionsController
 * @package App\Http\Controllers\Admin
 */
class AskedQuestionsController extends AdminController
{
	/**
	 * @var AskedQuestion
	 */
	protected $askedQuestion;


	/**
	 * @param Category $askedQuestion
	 * @param Filter $filter
	 */
	public function __construct(AskedQuestion $askedQuestion)
	{
		$this->askedQuestion = $askedQuestion;

		parent::__construct();
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$askedQuestions = $this->askedQuestion->orderBy('order')->get();

		return view('admin.asked-questions.index',compact('askedQuestions'));
	}

	/**
	 * @return mixed
	 */
	public function create()
	{
		return view('admin.asked-questions.create');
	}

	/**
	 * @param CreateRequest $request
	 * @param FilterService $filterService
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request)
	{

		$askedQuestion = $this->askedQuestion->create($request->all());


		if((int)$request->get('button')) {
			return redirect()->route('dashboard.asked-questions.index')->withMessage('');
		}

		return redirect()->route('dashboard.asked-questions.edit',$askedQuestion->id);
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\View\View
	 */
	public function edit(Request $request, $id)
	{
		$askedQuestion = $this->askedQuestion->findOrFail($id);

		return view('admin.asked-questions.edit', compact('askedQuestion'));
	}

	/**
	 * @param UpdateRequest $request
	 * @param FilterService $filterService
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, $id)
	{
		$askedQuestion = $this->askedQuestion->findOrFail($id);

		$askedQuestion->update($request->all());
		if((int)$request->get('button')) {
			return redirect()->route('dashboard.asked-questions.index')->withMessage('');
		}

		return redirect()->route('dashboard.asked-questions.edit',$id);

	}

	/**
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id)
	{
		$this->askedQuestion->findOrFail($id)->delete();
		return redirect()->route('dashboard.asked-questions.index');
	}

	/**
	 * @return array
	 */
	public function order()
	{
		foreach(\Request::get('serialized') as $catKey => $askedQuestion) {
			$this->askedQuestion->find($askedQuestion['id'])->update(['order' => $catKey]);
        }
		return ['success' => true];
	}


}
