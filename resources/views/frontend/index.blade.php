@extends('frontend.layout')
@inject('bannerProvider', '\App\ViewDataProviders\BannerDataProvider')

@section('seo')
    <title>{{ 'UnPuzzle' }}</title>
    <meta name="description" content=""/>
    <meta name="keyword" content=""/>
@endsection

@section('content')

@include('frontend.partials.home_page_slider')
@if($bannerProvider->getBanner())
    @foreach($bannerProvider->getBanner() as $banner)
    <section class="{{$banner->area}}">
        @if($banner->area=="quests-block")
            <div class="container">
                <div class="row right-align">
                    @if($banner->title)
                        {!! $banner->area=='mailing-block' ? '<h4' : '<h3'!!} class="{{$banner->area=='map-block' ||
                        $banner->area=='quests-block' || $banner->area=='advantages'? 'red-text' : 'white-text'}}
                        center-align">
                        @if($banner->link )
                            <a rel="nofollow" href="{{ $banner->link }}" class="title-company-link">
                                {!! $banner->title !!}
                            </a>
                        @else
                            {!! $banner->title !!}
                        @endif
                        {!! $banner->area=='mailing-block' ? '</h4>' : '</h3>'!!}
                    @endif
                @include('frontend.partials.products.bestseller_products_slider')
                    <div class="col s12 m4 no-padding que-img">
                        @if($banner->thumbnail)
                        <img src="{{ url($banner->thumbnail) }}" alt="{{$banner->title}}">
                        @endif
                        <div class="caption">{!! $banner->caption !!}</div>
                    </div>
                    <div class="col s12 m8 que-link">
                        <a href="{{$banner->link}}">more quests</a>
                    </div>
                </div>
            </div>
        @else
        @if($banner->thumbnail)
        <img class="banner-image" src="{{ url($banner->thumbnail) }}" alt="{{$banner->title}}">
        @endif
        <div class="container">
            <div class="row">
                <div class="banner megamenu">
                    <div class="col no-padding s12 center-align">
                        @if($banner->title)
                            {!! $banner->area=='mailing-block' ? '<h4' : '<h3'!!} class="{{$banner->area=='map-block'
                            || $banner->area=='quests-block' || $banner->area=='advantages'? 'red-text' : 'white-text'}}">
                                @if($banner->link )
                                    <a rel="nofollow" href="{{ $banner->link }}" class="title-company-link">
                                        {!! $banner->title !!}
                                    </a>
                                @else
                                    {!! $banner->title !!}
                                @endif
                            {!! $banner->area=='mailing-block' ? '</h4>' : '</h3>'!!}
                        @endif
                        @if($banner->area=="review-block")
                                @inject('productsProvider', 'App\ViewDataProviders\ProductsDataProvider')
                                <div class="review-slider responsive autoplay center-align">
                                    @foreach($productsProvider->reviews as $review)
                                        <div class="review">
                                            <div class="photo-col">
                                                @if(count($review->user->thumbnail) && file_exists(public_path($review->user->thumbnail)))
                                                    <img src="{{ $review->user->thumbnail }}">
                                                @else
                                                    <img src="/frontend/images/default.png">
                                                @endif
                                            </div>
                                            <div class="body-col">
                                                <span class="left name">{{ $review->user->name }}</span>
                                                <span class="right date">{{$review->updated_at->format('d.m.Y')}}</span>
                                                <p class="left-align">{{$review->body}}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                        @elseif($banner->area=="mailing-block")

                                <form action="{!! route('mail.me') !!}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_view" value="contact"/>

                                    <input placeholder="your question" id="name" name="name" type="text" class="validate" required="required">
                                    {{--<input placeholder="номер телефона" id="phone" name="phone" type="text" class="validate" required="required">--}}
                                    <div class="col s12 m6 email-field">
                                        <input placeholder="your email" id="email"  name="email" type="text" class="validate email" required="required">
                                    </div>
                                    {{--<input placeholder="примечание" id="comment" name="comment" type="text" class="validate">--}}
                                    <div class="col s12 m6 submit-field">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">send your message
                                        <i class="fa fa-envelope"></i></button>
                                    </div>
                                </form>
                        @endif
                        <div class="card-title clearfix">{!! $banner->caption !!}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
    @endforeach
@endif
<!--<section class="breadcrumbs">-->
    <!--<div class="container">-->
        <!--<div class="row">-->
            <!--<nav>-->
                <!--<ul>-->
                    <!--<li>Главная</li>-->
                    <!--<li>Родитель</li>-->
                    <!--<li>Дочерняя страница</li>-->
                <!--</ul>-->
            <!--</nav>-->
        <!--</div>-->
    <!--</div>-->
<!--</section>-->
{{--<section class="content">--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}

           {{--@include('frontend.partials.sidebar')--}}

            {{--<div class="col s12 m12 l9 catalog no-padding">--}}

                {{--@include('frontend.partials.products.sale_products_slider')--}}
                {{--@include('frontend.partials.products.new_products_slider')--}}
                {{--@include('frontend.partials.products.bestseller_products_slider')--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</section>--}}
{{--<section class="advantages">--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<div class="col s12 part-one no-padding">--}}
                {{--<h3>Наши преимущества</h3>--}}
                {{--<div class="col s12 m6 l4">--}}
                    {{--<div class="card advantage-simple-block no-padding wow animated fadeInDown" data-wow-delay="0.5s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-1.png">--}}
                            {{--<p class="title-company">Housefit</p>--}}
                            {{--<h2 class="card-title uppercase bold">Бесплатная сборка</h2>--}}
                            {{--<h6>Бесплатная сборка</h6>--}}
                            {{--<p class="text">Непосредственно высококвалифицированными специалистами компании «HouseFit» в городах--}}
                                {{--Одесса и Киев.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="card wow animated fadeInLeft" data-wow-delay="0.7s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-2.png">--}}
                            {{--<p class="black-text bold">гарантия &</p>--}}
                            {{--<p class="shadow bold">сервис</p>--}}
                            {{--<a class="readmore uppercase bold double" href="#">фирменная гарантия <strong>2</strong> года</a>--}}
                            {{--<span class="note black-text right-align">непосредственно от производителя</span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col s12 m6 l4">--}}
                    {{--<div class="card wow animated fadeInDown" data-wow-delay="0.9s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-3.png">--}}
                            {{--<p class="black-text bold">2015</p>--}}
                            {{--<p class="shadow bold">best selling</p>--}}
                            {{--<a class="readmore uppercase bold" href="{{ route('sale') }}">акции&скидки</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="card wow animated fadeIn" data-wow-delay="1s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-4.png">--}}
                            {{--<p class="title-company">доставка в других</p>--}}
                            {{--<h2 class="card-title uppercase bold">регионах</h2>--}}
                            {{--<h6>Бесплатная доставка</h6>--}}
                            {{--<p class="text">Для других регионов бесплатная доставка на региональный склад.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col s12 m12 l4 pad-on-mobile">--}}
                    {{--<div class="card col s12 m6 l12 no-padding wow animated fadeInRight"data-wow-delay="1.3s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-5.png">--}}
                            {{--<p class="title-company">доставка в городах</p>--}}
                            {{--<h2 class="card-title uppercase bold">Одесса и Киев</h2>--}}
                            {{--<h6>Совершенно бесплатно!</h6>--}}
                            {{--<p class="text">Теперь не нужно думать о доставке! Мы товар доставим сами и самое главное - совершенно бесплатно.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="card col s12 m6 l12 no-padding wow animated fadeInUp pad-left-at-mobile" data-wow-delay="1.4s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-6.png" class="hide-on-med-only">--}}
                            {{--<img src="/frontend/images/advantages-6-mobile.png" class="show-on-medium">--}}
                            {{--<p class="black-text bold">последние</p>--}}
                            {{--<p class="shadow bold">новости</p>--}}
                            {{--<a class="readmore uppercase bold" href="{{ route('frontend.page') }}">подробнее...</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col s12 part-two no-padding">--}}
                {{--<div class="col s12 m6 l8">--}}
                    {{--<div class="card wow animated fadeInLeft"data-wow-delay="1.4s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-7.png" class="hide-on-med-and-down">--}}
                            {{--<img src="/frontend/images/advantages-7-mobile.png" class="show-on-medium-and-down">--}}
                            {{--<p class="black-text bold">Полный комплект.</p>--}}
                            {{--<p class="shadow bold">документы</p>--}}
                            {{--<a class="readmore uppercase bold" href="#">паспорт, гарантйиный талон, товарный чек</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col s12 m6 l4">--}}
                    {{--<div class="card wow animated fadeInRight" data-wow-delay="1.5s">--}}
                        {{--<div class="card-image">--}}
                            {{--<img src="/frontend/images/advantages-8.png">--}}
                            {{--<p class="title-company">Housefit</p>--}}
                            {{--<h2 class="card-title uppercase bold">Отгрузка товара</h2>--}}
                            {{--<p class="text">Отгрузка товара на транспортную фирму производится в течении одного рабочего дня с момента оформления заказа.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</section>--}}
{{--<section class="description">--}}
    {{--<div class="container">--}}

        {{--<!--   Icon Section   -->--}}
        {{--<div class="row">--}}
            {{--<h1>Спортивный интернет-магазин</h1>--}}
            {{--<div class="col s12 m6 shop-info">--}}
                    {{--<h4>40 лет опыта в создании тренажеров для домашнего фитнеса</h4>--}}
                    {{--<h5>Если вы уже осознали важность заботы о собственном теле и физическом здоровье, и теперь подбираете спортивное оборудование и тренажеры, которые помогут справится с поставленной задачей, к вашим услугам различные разделы на сайте интернет магазина спортивных товаров Housefit.</h5>--}}
                    {{--<p>Все виды фитнеса, аэробики и других популярных направлений тренировок проходят через различные этапы становления и востребованности. Что-то становится модным, о чем-то забывают в связи с переключением на что-то более эффективное. За несколько десятков лет работы на мировом рынке спорт-товаров торговая марка HouseFit не раз имела дело с новыми тенденциями и веяниями. И вместе с очередными фитнес-открытиями и новыми тенденциями мы менялись, совершенствовались и развивались.</p>--}}
                    {{--<p>В настоящее время популярность здорового образа жизни и активного отдыха растет. В связи с этим, спортивный интернет магазин HouseFit с радостью расширил ассортимент товаров в своем каталоге, предложив покупателям актуальное направление - <a href="http://housefit.ua/turizm">товары для туризма</a> и активного отдыха на природе.</p>--}}
                    {{--<p>На данный момент, наш спорт магазин в Одессе предлагает не только все популярные группы тренажеров для кардио-тренировок (топ-3 вида таких тренажеров - <a href="http://housefit.ua/housefit-velotrenazhery">велотренажер</a>, <a href="http://housefit.ua/begovie-dorojki">беговая дорожка</a> и <a href="http://housefit.ua/orbitreki">орбитрек</a>), силовое оборудование и инвентарь для активного отдыха (палатки, туристическая мебель, спальные мешки и др), но также и товары для не менее важной составляющей активного образа жизни - правильного отдыха и расслабления. Эта группа товаров в каталоге интернет магазина в Одессе включает различное оборудование для массажа (<a href="http://housefit.ua/massajnie-kresla">массажные кресла</a>, столы, массажеры различных частей тела).</p>--}}
            {{--</div>--}}

            {{--<div class="col s12 m6 shop-info">--}}
                    {{--<h4>HouseFit - профессиональные тренажеры для спортивных-клубов</h4>--}}
                    {{--<h5>Изначально главной специализацией компании HouseFit были тренажеры для домашнего использования. Главными преимуществами домашних тренажеров от HouseFit были и остаются легкость, прочность, инновационная конструкция и малогабаритность. Если к этому ряду прибавить доступную цену, становится понятно, почему в свое время именно наши тренажеры заслужили любовь населения и заняли прочные позиции на рынке тренажеров многих стран мира.</h5>--}}
                    {{--<p>С возвращением былой популярности фитнес-клубов и тренажерных залов, в компании отреагировали на рыночные тенденции. Растущий спрос на профессиональное оборудование стал причиной выхода отдельной линейки тренажеров от HouseFit, предназначенных для профессионального использования. Данная категория тренажеров в настоящий момент также представлена на сайте нашего интернет-магазина.</p>--}}
                    {{--<p>Помимо Одессы, <a href="http://housefit.ua/trenajeri">тренажеры для дома</a> HouseFit можно также купить в других городах Украины благодаря крупной сети официальных дилеров и фирменных магазинов. Контакты и адреса наших официальных представительств вы всегда можете найти на нашем сайте в разделе “Контакты”. </p>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</section>--}}


@endsection
@section('bottom-scripts')
    @include('frontend.partials.scripts.add_to_cart')

    <script type="text/javascript" src="/frontend/js/home.js"></script>
@endsection