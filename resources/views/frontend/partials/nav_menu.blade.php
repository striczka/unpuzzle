@inject('categoriesProvider', '\App\ViewDataProviders\CategoriesDataProvider')
@inject('bannerProvider', '\App\ViewDataProviders\BannerDataProvider')
            <nav class="no-padding" role="navigation">
                <a href="#" data-activates="nav-mobile" class="button-collapse"><img src="/frontend/images/menu55.png" /></a>
                <ul id="nav-mobile" class="side-nav collapsible" data-collapsible="accordion">
                    <i class="drag-target close-button fa fa-times"></i>

                    <li><a href="/">home</a></li>
                    <li><a href="{{ route('our-quests') }}">our quests</a></li>
                    <li><a href="/how-it-works">how it works</a></li>
                    <li><a href="{{ route('new') }}">testimonials</a></li>
                    <li><a href="{{ route('frontend.page') }}">blog</a></li>
                    <li><a href="/about">about us</a></li>
                    {{--<li><a href="{{ route('service') }}">Сервис</a></li>--}}
                    {{--<li><a href="{{ route('contacts') }}">Контакты</a></li>--}}
                    {{--<li><a rel="nofollow" href="http://fitnesmarket.ua/">Магазин fitnesmarket</a></li>--}}
                </ul>
                <ul class="right top-nav hide-on-small-and-down">
                    <li><a href="/">home</a></li>
                    <li><a href="{{ route('our-quests') }}">our quests</a></li>
                    <li><a href="/how-it-works">how it works</a></li>
                    <li><a href="{{ route('new') }}">testimonials</a></li>
                    <li><a href="{{ route('frontend.page') }}">blog</a></li>
                    <li><a href="/about">about us</a></li>
                    {{--<li><a href="{{ route('service') }}">Сервис</a></li>--}}
                    {{--<li><a href="{{ route('contacts') }}">Контакты</a></li>--}}
                    {{--<li><a rel="nofollow" href="http://fitnesmarket.ua/">Магазин fitnesmarket</a></li>--}}
                </ul>
            </nav>
            {{--<div class="col s6 m2 l1 mini-cart-wrapper">--}}
                {{--<div class="mini-cart tabs-wrapper pin-top" id="_cart">--}}
                    {{--<a href="/cart" class="go-to-cart waves-effect waves-light">--}}
                        {{--<img src="/frontend/images/mini-cart.png"/>--}}
                    {{--</a>--}}
                    {{--<p>Кол-во <span class="qty">{{ cartItemsCount() }}</span> шт</p>--}}
                    {{--<div class="cart-content">--}}
                        {{--<div class="col s12 cart_filled" style="display: {{ cartItemsCount() ? 'block' : 'none' }}">--}}
                            {{--<strong>В корзине <span class="qty-items">{{  cartItemsCount() }}</span> товар/ов</strong>--}}
                            {{--<strong>На сумму--}}
                                {{--<span class="sum-payment">--}}
                                    {{--<span class="_sum">{{ cartTotalPrice() }}</span>--}}
                                    {{--<span class="currency"> грн</span>--}}
                                {{--</span>--}}
                            {{--</strong>--}}
                            {{--<a href="/cart" class="waves-effect waves-light btn">Перейти в корзину</a>--}}
                        {{--</div>--}}
                        {{--<!--Empty-->--}}
                        {{--<div class="cols4 cart_empty" style="display: {{ cartItemsCount() ? 'none' : 'block' }}">--}}
                            {{--<img src="/frontend/images/mini-cart-empty.png" class="left no-padding" />--}}
                        {{--</div>--}}
                        {{--<div class="col s8 cart_empty" style="display: {{ cartItemsCount() ? 'none' : 'block' }}">--}}
                            {{--<span class="left">В корзине ещё нет товаров</span>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}


<!--
<section class="slider2 hide-on-med-and-down">
    <ul class="slides">
        <li class="slide">
            <div class="box">
                <img class="responsive-img" src="images/slide-1.png" />
                <div class="container slide-content">
                    <p class="pre-title right-align">housefit</p>
                    <h4 class="">качество, которому доверяют</h4>
                    <p class="main-text right-align"><a href="#">свыше 17 лет на рынке, сотни тысяч покупателей по всей Украине</a></a></p>
                </div>
            </div>
        </li>
        <li class="slide">
            <div class="box">
                <img class="responsive-img" src="images/slide-2.png" />
                <div class="container slide-content">
                    <p class="pre-title right-align">housefit</p>
                    <h4>качество, которому доверяют</h4>
                    <p class="main-text right-align"><a href="#">свыше 17 лет на рынке, сотни тысяч покупателей по всей Украине</a></p>
                </div>
            </div>
        </li>
        <li class="slide">
            <div class="box">
                <img class="responsive-img" src="images/slide-3.png" />
                <div class="container slide-content">
                    <p class="pre-title right-align">housefit</p>
                    <h4>качество, которому доверяют</h4>
                    <p class="main-text right-align"><a href="#">свыше 17 лет на рынке, сотни тысяч покупателей по всей Украине</a></p>
                </div>
            </div>
        </li>
        <li class="slide">
            <div class="box">
                <img class="responsive-img" src="images/slide-4.png" />
                <div class="container slide-content">
                    <p class="pre-title right-align">housefit</p>
                    <h4>качество, которому доверяют</h4>
                    <p class="main-text right-align"><a href="#">свыше 17 лет на рынке, сотни тысяч покупателей по всей Украине</a></p>
                </div>
            </div>
        </li>
    </ul>
</section>
-->