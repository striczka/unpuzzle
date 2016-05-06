<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Hint;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HintsController extends AdminController
{

    public function index()
    {
	    $hints = Hint::all();
        return view('admin.hints.index', compact('hints'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.hints.create');
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request|Request $request
	 * @return Response
	 */
    public function store(Request $request)
    {
	    $hint = Hint::create($request->all());
	    return redirect()->route('dashboard.hints.edit', $hint->id);
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
        $hint = Hint::find($id);
	    return view('admin.hints.edit', compact('hint'));
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

	    $request = $this->formatRequestDate($request);
	    $hint = Hint::find($id);
	    $hint->update($request->all());
	    return redirect()->route('dashboard.hints.edit', $hint->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
	    $hint = Hint::find($id);
        $hint->delete();
	    return redirect()->back();
    }

}

