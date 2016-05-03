@inject('categoriesProvider', 'App\ViewDataProviders\CategoriesDataProvider')
    {{--<h3>Категории товаров</h3>--}}
    {{--<ul class="side-bar card">--}}
        {{--@foreach($categoriesProvider->getListForNav() as $category)--}}
            {{--<li @if(count($category->children))class="havechild"@endif>--}}
                {{--<img class="icon-cat" src="/frontend/images/{{ $category->icon }}" />--}}
                {{--<img class="icon-cat-hover" src="/frontend/images/{{ substr_replace($category->icon, 'icon-cat-hover', 0, 8) }}" />--}}
                {{--<span><a href="/{{ $category->slug }}">{{ $category->title }}</a></span>--}}
                {{--@if(count($category->children))--}}
                    {{--<ul class="sub-categories">--}}
                        {{--@foreach($category->children as $child)--}}
                            {{--<li><a href="/{{ $child->slug }}"><i class="fa fa-circle-o"></i> {{ $child->title }}</a></li>--}}
                        {{--@endforeach--}}
                    {{--</ul>--}}
                {{--@endif--}}
            {{--</li>--}}

        {{--@endforeach--}}

        {{--@include('frontend.partials.banner')--}}
    {{--</ul>--}}
