<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Characteristic;
use App\Models\CharacteristicValue;
use App\Models\Image;
use App\Models\Product;
use App\Models\Stock;
use App\ProductRate;
use App\Services\FilesHandler;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Requests;
Use App\Http\Requests\Product\CreateRequest;
Use App\Http\Requests\Product\UpdateRequest;
use Illuminate\Queue\RedisQueue;
use Illuminate\Support\Facades\Response;
use Mockery\CountValidator\Exception;

/**
 * Class ProductsController
 * @package App\Http\Controllers\Admin
 */
class ProductsController extends AdminController
{
	/**
	 * @var ProductService
	 */
	protected $productService;
	/**
	 * @var Product
	 */
	protected $product;
	/**
	 * @var FilesHandler
	 */
	protected $filesHandler;

	/**
	 * @param Product $product
	 * @param FilesHandler $filesHandler
	 * @param ProductService $productService
	 */
	public function __construct(Product $product, FilesHandler $filesHandler, ProductService $productService)
    {
        $this->product = $product;
		$this->filesHandler = $filesHandler;
	    $this->productService = $productService;
        parent::__construct();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Response
	 */
    public function index(Request $request)
    {

	    if($request->ajax()){

		    $search = $request->get('search');
		    $products = $this->product
			    ->whereNotIn('id', !empty($request->get('selected')) ? $request->get('selected') : [0])
			    ->with('category')
			    ->where('category_id', $request->get('categoryId') ?: 'LIKE','%')
			    ->whereRaw(getDiscountValue($request))
			    ->where(function($prods) use($search){
		    		$prods->where('title', 'LIKE', '%'.$search.'%')
		    			->orWhere('article', 'LIKE', '%'.$search.'%')
		    			->orWhere('clone_of', $search);
			    })
			    ->orderBy($request->get('sortBy') ?: 'id', 'ASC')
			    ->where('category_id', $request->get('categoryId') ?: 'LIKE', '%')
			    ->with('thumbnail')
			    ->paginate($request->get('paginate') ?: 20);

		    return $products;
	    }
        return view('admin.products.index',compact('products'));
    }

//	public function getProducts(Request $request)
//	{
//		$products = $this->product->with('category')
//			->where('category_id', $request->get('categoryId') ?: 'LIKE','%')
//
//			->paginate();
//		return $products;
//	}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Category $categories)
    {
        return view('admin.products.create');
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateProductRequest|CreateRequest|Request $request
	 * @param ProductService $productService
	 * @return Response
	 */
    public function store(CreateProductRequest $request, ProductService $productService)
    {
	    $request = $this->filesHandler->saveFile($request, "pdf/");

        $product = $this->product->create($request->all());

	    $this->productService->syncImages($product, $request->get('imagesIds'));
	    $request['filters'] = $productService->prepareFiltersRequest($request->get('filters'));
	    $product->filters()->sync($request->get('filters') ?: []);

	    $idsList = explode(',', $request->get('selectedProductsIds'));
	    $product->rates()->sync([ProductRate::create(['rate' => $request->get('rating')])->id]);



	    $product->relatedProducts()->sync($request->get('selectedProductsIds') ? $idsList : []);

	    if((int)$request->get('button')) {
            return redirect()->route('dashboard.products.index')->withMessage('');
        }

        return redirect()->route('dashboard.products.edit',$product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $categories
     * @param  int $id
     * @return Response
     */
    public function edit(Category $categories, $id)
    {
		$product = $this->product
			->with('images','category','category.filters','characteristicsValues','category.strain.values','filters')
			->withTrashed()
			->findOrFail($id);
//	    dd($product);
        return view('admin.products.edit',compact('product'));
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param UpdateProductRequest|Request $request
	 * @param  int $id
	 * @return Response
	 */
	// TODO: Refactor this crap
	public function update(UpdateProductRequest $request, $id, ProductService $productService)
    {
	    $request = $this->filesHandler->saveFile($request);
	    $product = $this->product->withTrashed()->findOrFail($id);

	    $request['filters'] = $productService->prepareFiltersRequest($request->get('filters'));

	    $product->update($request->all());
//		dd($request->get('filters'));
		$product->filters()->sync($request->get('filters') ?: []);

	    $this->productService->syncImages($product, $request->get('imagesIds'));

	    $product->rates()->sync([ProductRate::create(['rate' => $request->get('rating')])->id]);


        if((int)$request->get('button')) {
            return redirect()->route('dashboard.products.index');
        }

        return redirect()->route('dashboard.products.edit',$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {

        $product  = $this->product->findOrFail($id);

        $product->delete();

        if($request->has('redirect')) {
            return redirect()->to($request->get('redirect'));
        }

        return redirect()->route('dashboard.products.index');
    }


	public function copyProduct($id)
	{
		$product = Product::find($id);
        if($product->clone_of) $product = Product::find($product->clone_of);
        
        $copy = new Product();
        $copy->fill($product->toArray());
		$copy->clone_of = $product->id;
        $copy->save();

        // prepare filters data for sync
		$filters = [];
		foreach($product->filters->lists('pivot') as $pivot) {
			$filters[$pivot->filter_id]['product_id'] = $copy->id;
			$filters[$pivot->filter_id]['filter_id'] = $pivot->filter_id;
			$filters[$pivot->filter_id]['filter_value_id'] = $pivot->filter_value_id;
		}

		// prepare stock data for sync
        $stocks = [];
        foreach ($product->stocks as $stock) {
        		$stocks[$stock->id]['is_main'] = $stock->pivot->is_main;
        		$stocks[$stock->id]['stock_price'] = $stock->pivot->stock_price;
        }
       
		$copy->images()->sync($product->images->lists('id')->toArray());
		$copy->relatedProducts()->sync($product->relatedProducts->lists('id')->toArray());
		$copy->filters()->sync($filters);
        $copy->stocks()->sync($stocks);

		return view('admin.products.edit')->with('product', $copy);
	}


    // Trash

	/**
	 * @return \Illuminate\View\View
	 */
	public function trash()
    {
        $products = $this->product->onlyTrashed()->paginate();

        return view('admin.products.trash',compact('products'));
    }


	/**
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function restoreFromTrash($id)
    {
		Product::withTrashed()->find($id)->restore($id);
	    return redirect()->back();
    }

	/**
	 * Destroy product from trash bean
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroyFromTrash($id)
    {
	    $product = Product::withTrashed()->find($id);
	    $product->relImages()->delete();
        $product->clones()->forceDelete();
	    $product->characteristicsValues()->delete();
	    $product->forceDelete();
	    return redirect()->back();
    }

	/**
	 * @param $id
	 * @return mixed
	 */
	public function remove($id)
    {
        $product = $this->product->onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return redirect()->route('dashboard.products.trash')->withMessage('');
    }

    // Drafts
	/**
	 * @return \Illuminate\View\View
	 */
	public function drafts()
    {
        $products = $this->product->isDraft(1)->paginate();

        return view('admin.products.drafts',compact('products'));

    }

	/**
	 * @param $productId
	 */
	public function removePDF($productId)
	{
		$product = Product::find($productId);

		if($product) {
			if(file_exists(public_path($product->pdf)) && is_file(public_path($product->pdf))){
				$path = public_path($product->pdf);
				unlink($path);
			}
			$product->pdf = null;
			$product->save();
		}

	}

	public function removeFlash($productId)
	{
		$product = Product::find($productId);

		if($product) {
			if(file_exists(public_path($product->flash_view)) && is_file(public_path($product->flash_view))){
				$path = public_path($product->flash_view);
				unlink($path);
			}
			$product->flash_view = null;
			$product->save();
		}
	}


	/**
	 * @param Request $request
	 * @return array
	 */
	public function massDelete(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->delete();
		return [true];
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function massDropDiscount(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->update(['discount' => 0]);
		return [true];
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function massActivate(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->update(['active' => 1]);
		return [true];
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function massDeactivate(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->update(['active' => 0]);
		return [true];
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function massMarkAsBestseller(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->update(['is_bestseller' => 1]);
		return [true];
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function massUnmarkAsBestseller(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->update(['is_bestseller' => 0]);
		return [true];
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function massMarkAsNew(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->update(['is_new' => 1]);
		return [true];
	}

	/**
	 * @param Request $request
	 * @return array
	 */
	public function massUnmarkAsNew(Request $request)
	{
		Product::whereIn('id', $request->get('ids'))->update(['is_new' => 0]);
		return [true];
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function getProducts(Request $request)
	{

		$products = $this->product
		->whereNotIn('id', !empty($request->get('selected')) ? $request->get('selected') : [0])
		->with('category')
		->where('category_id', $request->get('categoryId') ?: 'LIKE','%')
		->orderBy($request->get('sortBy') ?: 'id', 'ASC')
		->where('category_id', $request->get('categoryId') ?: 'LIKE', '%')
        ->where(function($prod) use($request){
            $prod->where('title', 'LIKE', '%'. $request->get('search') .'%')
                ->orWhere('article', 'LIKE', '%'. $request->get('search') .'%');
        })
        ->where('clone_of', 0)
		->with('thumbnail')
		->paginate($request->get('paginate') ?: 20);

		return $products;
	}


	/**
	 * @param Request $request
	 */
	public function syncRelatedProducts(Request $request)
	{
		$product = Product::find($request->get('productId'));
		if($product){
			$product->relatedProducts()->sync($request->get('ids') ?: []);
		}
	}


	/**
	 * @param Request $request
	 * @return array
	 */
	public function getRelatedProducts(Request $request)
	{
		$product = Product::find($request->get('productId'));
		if($product){
			return $product->relatedProducts->load('category','thumbnail');
		}
		return [];
	}


	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function getProductsForSale(Request $request)
	{
//		dd($request->all());
		$products= Product::whereNotIn('id', !empty($request->get('selected')) ? $request->get('selected') : [0])
					->searchable($request)
					->with('thumbnail','category')
					->paginate($request->get('paginate') ?: 20);

		return $products;
	}


	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function getProductsBySale(Request $request)
	{

//		dd($request->get('selected'));

		if($request->get('saleId')){
			$paginatedProducts = $this->product
				->bySale($request)
				->searchable($request)
				->with('thumbnail','category')
				->paginate($request->get('paginate') ?: 20);


			$productsIds = Product::bySale($request)->lists('id');

			return ['paginatedProducts' => $paginatedProducts->toArray(), 'productsIds' => $productsIds];

		}
	}

	
    public function getStockProducts(Request $request)
    {
        $stock = Stock::with('uniqueProducts.category', 'uniqueProducts.thumbnail')->find($request->get('stockId'));
        if($stock){
            return $stock->uniqueProducts;
        }

        return [];
    }

}
