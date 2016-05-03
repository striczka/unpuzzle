@inject('productsProvider', 'App\ViewDataProviders\ProductsDataProvider')

<h3>Акции</h3>
<div class="slider related autoplay responsive" data-show="sale">
{{--    {{ $productsProvider->salesProducts->count() }}--}}
    @foreach($productsProvider->salesProducts as $product)

        @include('frontend.partials.products.product_template')

    @endforeach


</div>