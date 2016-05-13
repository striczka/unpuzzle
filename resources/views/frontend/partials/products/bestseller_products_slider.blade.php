@inject('productsProvider', 'App\ViewDataProviders\ProductsDataProvider')
    @foreach($productsProvider->bestsellerProducts as $product)

        @include('frontend.partials.products.product_template')

    @endforeach

