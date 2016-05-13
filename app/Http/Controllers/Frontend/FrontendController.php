<?php namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Requests\BuyRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Code;
use App\Models\CustomerGroup;
use App\Models\FilterValue;
use App\Models\LifeComplex;
use App\Models\Metro;
use App\Models\Order;
use App\Models\OrderedProduct;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use App\Services\AccommodationComposer;
use App\Services\BuyService;
use App\Services\FilterService;
use App\Services\FiltersService;
use App\Services\ProductService;
use App\StaticPage;
use Cache;
use Carbon\Carbon;
use Faker\Factory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Session;

/**
 * Class FrontendController
 * @package App\Http\Controllers\Frontend
 */
class FrontendController extends BaseController
{
	/**
	 * @var ProductService
	 */
	private $productService;

	/**
	 * @param ProductService $productService
	 */
	function __construct(ProductService $productService)
	{
		$this->productService = $productService;
		$this->middleware('pageHasFilter', ['only' => 'catalog']);
		$this->middleware('guest', ['only' => ['login', 'registration']]);
		$this->middleware('frontAuth', ['only' => ['cabinet']]);

		parent::__construct();
	}

	/**
	 * @param Slider $slider
	 * @return \Illuminate\View\View
	 *
	 * Show index page with slider
	 */
	public function index(Slider $slider)
	{
		return view('frontend.index')->withSliders($slider->show()->orderBy('order','desc')->get());
	}


	/**
	 * @param Request $request
	 * @param FilterService $filterService
	 * @param $categorySlug
	 * @return \Illuminate\View\View
	 * @internal param FiltersService $filtersService
	 *
	 * Show products by category
	 */
	public function catalog(Request $request, FilterService $filterService, $categorySlug)
	{
		// Ajax request is used when
		// paginate or filter products

		$category = Category::where('slug', $categorySlug)->with('filters')->first();
        if(!$category) abort(404);

		$products = Product::where('category_id', $category->id)
				->ordered($request)
				->visible()
				->withRelations()
				->paginate(15);
		if($request->ajax()){
			return $filterService->getFilteredProducts($request);
		}

		return view('frontend.catalog', compact('products', 'category'));
	}

	public function ourQuests(Request $request, FilterService $filterService)
	{
		$category = Category::first();
		$products = Product::visible()
			->paginate(9);

		if($request->ajax()){
			$view = view("frontend.partials.products.product_array")->with("products", $products)->render();
			return Response::json($view);
		}
		return view('frontend.our-quests', compact('products',"category"));
	}
	/**
	 * @param $categorySlug
	 * @param $productSlug
	 * @param Request $request
	 * @return \Illuminate\View\View
	 *
	 * Show single product view
	 */
	public function product($categorySlug, $productSlug, Request $request)
	{
		$product = Product::whereRaw("products.slug = '$productSlug'" )->whereHas('category', function($category) use($categorySlug){
			$category->where('slug', $categorySlug);
		})
		->visible()
		->with(
            'relevantSale', 'images', 'category','relatedProducts',
            'rates.users','reviews.user', 'filterValuesWithFilters',
            'stocks.orderedProducts'
        )
		->first();

        if(!$product) abort(404);

		// Ajax request is used
		// for assess product
		if($request->ajax()){
			$product->rates()->create(['rate' => $request->get('val')]);
			$request->session()->push('rated', $product->id );

			return $product->rates()->avg('rate');
		}

		// need for reviews
		$productReviewId = $product->id;
		$banner = Banner::where("area", "mailing-block")->first();
		return view('frontend.product', compact('product','productReviewId','banner'));
	}


	/**
	 * @param Request $request
	 * @param ProductService $productService
	 * @return \Illuminate\View\View
	 * @internal param FilterService $filterService
	 */
	public function newProducts(Request $request, ProductService $productService)
	{

		// Ajax request is used when
		// paginate or filter products
		if($request->ajax()){
            $products = Product::where('is_new', true)
                ->ordered($request)
                ->withRelations()
                ->visible()
                ->original()
                ->paginate(15);
            return $productService->ajaxResponse($products);
        }

		// As we return 'frontend.catalog' view
		// just display custom page heading
		// instead of category title
		$header = 'Новинки';
		return view('frontend.catalog', compact('products', 'header'));
	}

	/**
	 * @param Request $request
	 * @param ProductService $productService
	 * @return \Illuminate\View\View
	 *
	 * Show products with discount
	 */
	public function saleProducts(Request $request, ProductService $productService)
	{
		$products = Product::where(function($query){
			$query->where('discount', '>', 0)->orHas('relevantSale');
		})
		->withRelations()
		->ordered($request)
		->visible()
        ->original()
		->paginate(15);

		// Ajax request is used when
		// paginate or filter products
		if($request->ajax()) return $productService->ajaxResponse($products);

		// As we return 'frontend.catalog' view
		// just display custom page heading
		// instead of category title
		$header = 'Скидки';

		return view('frontend.catalog', compact('products', 'header'));
	}


	/**
	 * @param Request $request
	 * @param ProductService $productService
	 * @return array|\Illuminate\View\View
	 */
	public function search(Request $request, ProductService $productService)
	{

		$products = Product::where(function($products) use($request){

			$products->where('title', 'LIKE', '%'.$request->get('search').'%')
					 ->orWhere('article', 'LIKE', '%'.$request->get('search').'%');
		})
		->withRelations()
		->ordered($request)
		->visible()
        ->original()
		->paginate(15);


		// Ajax request is used when
		// paginate search result
		if($request->ajax()) return $productService->ajaxResponse($products);


		// As we return 'frontend.catalog' view
		// just display custom page heading
		// instead of category title
		$header = 'Результаты поиска';

		return view('frontend.catalog', compact('products', 'header'));
	}

	/**
	 * @return \Illuminate\View\View
	 * Show contacts page
	 */
	public function contacts()
	{
		return view('frontend.contacts');
	}

    /**
     * @param Request $request
     * @return \Illuminate\View\View Show static 'service' page
     * Show static 'service' page
     */
	public function staticPage(Request $request)
	{
		$slug = trim($request->getRequestUri(), '/');
		$page = StaticPage::where('slug', $slug)->first();
		if(!$page) abort(404);
		
 		return view('frontend.static', compact('page'));
	}


	/**
	 * @return \Illuminate\View\View
	 * Show cart view
	 */
	public function cart()
	{
		return view('frontend.cart');
	}

	/**
	 * @return \Illuminate\View\View
	 * Show Login form
	 */
	public function login()
	{
		return view('frontend.login');
	}

	/**
	 * @return \Illuminate\View\View
	 * Show registration form
	 */
	public function registration()
	{
		return view('frontend.registration');
	}

	/**
	 * @return \Illuminate\View\View
	 * Show users cabinet if logged
	 */
	public function cabinet()
	{
		$user = Auth::user()->load('orders');
		return view('frontend.cabinet', compact('user'));
	}

	/**
	 * @return \Illuminate\View\View
	 * Show "forgot password" form
	 */
	public function password()
	{
		return view('frontend.password');
	}

	/**
	 * @param BuyRequest|Request $request
	 * @param BuyService $buyService
	 * @return \Illuminate\Http\RedirectResponse
	 *
	 * Purchase products
	 * If user is not logged and users sole purchase - creates new user with role_id = User::GUEST_ID
	 */
	public function buy(BuyRequest $request, BuyService $buyService)
	{

		if(!Auth::check() && !$request->has('ones'))
			return redirect()->back()->withErrors('Вы должны быть зарегистрированы, либо оформлять заказ как разовую покупку');

		// If it's sole purchase we apply custom
		// validation rules for this request.
		if($request->has('ones') && $buyService->validate($request)->fails())
			return redirect()->back()->withErrors($buyService->validate($request))->withInput();

		$order = $buyService->registerOrder($request);
		$current = Carbon::now("Europe/Kiev");
		$trialExpires = $current->addDays(180);
		foreach( (array) session('stocks') + ['main'] as $instance) {
			foreach (Cart::instance($instance)->content() as $boughtProduct) {

				$product = Product::find($boughtProduct->id);
				Code::create([
					'product_id' => $product->id,
					'code' => "code",
					"deleted_at" => $trialExpires
				]);
			}
		}
        destroyCart();

		return view('frontend.thank_you', compact('order'));

	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 *
	 * Update user data from cabinet
	 *
	 */
	public function updateUserData(Request $request)
	{
		Auth::user()->update($request->only('phone','email','password', 'country','city', 'address', 'name'));
		return redirect()->back();
	}

	/**
	 * @param $orderId
	 * @return \Illuminate\View\View
	 *
	 * Show order info in users cabinet
	 */
	public function showOrder($orderId)
	{
		$order = Order::with('products.original.category', 'products.original.thumbnail')->find($orderId);
		return view('frontend.order', compact('order'));
	}


	public function service(){
		return view('frontend.service' );
	}
}
