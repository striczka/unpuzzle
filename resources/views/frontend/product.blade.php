@extends('frontend.layout')


@section('seo')
    <title>{{ $product->meta_title ?: $product->title }}</title>
    <meta name="description" content="{{ $product->meta_description ?: $product->excerpt }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords ?: $product->title }}"/>
@endsection



@section('content')
<section class="breadcrumbs">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li><a href="/{{ $product->category->slug }}">{{ $product->category->header }}</a></li>
                <li class="active">{{ $product->title }}</li>
            </ol>
        </div>
    </div>
</section>
<?php $pid = $product->id ?>

<section class="content">
    <h3 class="content-h3"><span class="red-text">{{ $product->title }}</span></h3>
    <div class="container">
        <div class="row">
            <div class="left linksBlock">
                @if($product->prevProductSlug())
                    <a class="link left" href="/{{ $product->category->slug.'/'.$product->prevProductSlug() }}">← Prev quest</a>
                @endif

                @if($product->nextProductSlug())
                    <a class="link right" href="/{{ $product->category->slug.'/'.$product->nextProductSlug() }}">Next quest →</a>
                @endif
            </div>

            <div class="col s12 m12 l9 mainProduct no-padding">
                {{--{{ $product->id }}--}}
                <div class="col listLightbox no-padding">
                    <div class="slider-for-product">
                        @if(count($product->thumbnail) && file_exists(public_path($product->thumbnail->first()->path)))
                            @foreach($product->images as $key => $image)
                                <div><img class="example-image" src="{{ $image->path }}" alt="{{ $product->title }}"/></div>
                            @endforeach
                        @else
                            <img class="example-image" src="/frontend/images/default.png" alt="{{ $product->title }}"/>
                        @endif
                        @if(hasGift($product))
                            <div class="appointment"><img src="/frontend/images/present.png" /></div>
                        @endif
                    </div>
                        @if(count($product->thumbnail) && file_exists(public_path($product->images->first()->path)))
                            <div class='slider-nav-product'>
                                @foreach($product->images as $key => $image)
                                    <div><img class="example-image" src="{{ $image->path }}" alt="{{ $product->title }}"/></div>
                                @endforeach
                            @else
                            @endif
                            
                        </div>
                        @if($key >= 4)
                            <span class="button" onclick="$('.slick-next').click();"></span>
                        @endif
                    {{--<ul class="listLightbox">
                        <li>
                            @if(count($product->thumbnail) && file_exists(public_path($product->thumbnail->first()->path)))
                                 <a class="example-image-link bigImage" href="{{ $product->thumbnail->first()->path }}" data-lightbox="example" data-title="{{ $product->title }}">
                                    <img class="example-image" src="{{ $product->thumbnail->first()->path }}" alt="{{ $product->title }}"/>
                                 </a>
                            @else
                                <a class="example-image-link bigImage" href="#" data-title="{{ $product->title }}">
                                    <img class="example-image" src="/frontend/images/default.png" alt="{{ $product->title }}"/>
                                </a>
                            @endif
                            @if(hasGift($product))
                                <div class="appointment"><img src="/frontend/images/present.png" /></div>
                            @endif
                        </li>
                        @if(count($product->thumbnail) && file_exists(public_path($product->images->first()->path)))

                            @foreach($product->images as $key => $image)
                                <li><a class="example-image-link" href="{{ $image->path }}" data-lightbox="example-set" data-title="{{ $product->title }}"><img class="example-image" src="{{ $image->path }}" alt=""/></a></li>
                            @endforeach

                        @else


                        @endif
                    </ul> --}}
                </div>
                <div class="col single-item-info">
                    <h4>{{ $product->title }}</h4> 
                    {{--<p class="single-item-sku">Артикул: <span class="violet-text">{{ $product->article }}</span></p>--}}
                    {{--
                    @if(isset($product->brand->title))
                     <p class="brand uppercase no-margin">Бренд: <span>{{ $product->brand->title }}</span></p>
                    @endif --}}
                    {{--
                    <div id="rating_3" class="item-rating left">
                        <input type="hidden" name="vote-id" value="5" id=""/>
                        <input type="hidden" name="val" value="{{ round($product->rates()->avg('rate'))}}">
                    </div> --}}
                    {{--
                    <div class="col s6 clearleft wrap-price">
                        <div class="pricesBlock" style="width:100%;float:left">
                            <div class="col s12 yellow item-prices">
                                @if($product->hasDiscount())
                                    <p class="center item-old-price no-margin">{{ $product->getPrice() }} грн</p>
                                    <p class="center item-new-price no-margin">{{ $product->getNewPrice() }} грн</p>
                                @else
                                    <p class="center item-new-price no-margin">{{ $product->getPrice() }} грн</p>
                                @endif

                                <div class="addtocart-button-item center-align waves-effect waves-light ">
                                    <input type="submit"
                                           name="addtocart"
                                           class="addtocart-button buy animProduct"
                                           data-productId="{{ $product->id }}"--}}
                                            {{--data-productPrice="{{ $product->getPrice() }}"--}}
                                           {{--value="{{ productInCart($product) ? 'В корзине' : 'Купить' }}"
                                           title="купить">
                                </div>
                            </div>
                        </div>
                    </div>--}}
                    <div class="col s12 no-padding">
                    
                    {{-- {{ dd($product->filterValuesWithFilters->sortBy('filter.category.pivot.order')->toArray()) }}--}}
                        @foreach($product->sortedValues($product->category_id) as $field)
                            {{--{{ dd($field->toArray()) }}--}}
                            @if($field->filter->isVisibleForCategory($product->category_id))
                                <div class="col aspect no-padding s12">
                                    {{--{{ dd($field->filter->toArray()) }}--}}
                                    {{--<p class="title bold uppercase">Основные</p>--}}
                                    <p class="col s12 no-padding">{{ $field->filter->title }}: <span>{{ $field->value }}</span></p>
                                    {{--<p class="col s12 no-padding">{{ $field->value }}</p>--}}
                                </div>
                            @endif
                        @endforeach
                    </div>
                    {{--
                    <div class="col s6 wrapper-buttons">
                        <div class="col s12 bordered buttons">
                            <div class="col s6 item-option center no-padding">
                                <button type="button" class="{{ $product->flash_view ? 'object-3d' : '_disabled' }} " id="object-3d" href="#">
                                    <img src="/frontend/images/button-3d.png" class="responsive-img" alt="object-3d"/>
                                    <span>товар в 3D</span>
                                </button>
                            </div>
                            <div class="col s6 item-option center no-padding">
                                <a class="instruction {{ $product->pdf ? '' : '_disabled' }} " href="/{{ $product->pdf }}" target="_blank">
                                    <img src="/frontend/images/button-instruction.png" class="responsive-img" alt="instruction"/>
                                    <span>инструкция</span>
                                </a>
                            </div>
                            <div class="col s6 item-option center no-padding">
                                <a class="{{ $product->video ? 'modal-trigger' : '_disabled' }} " title="{{ $product->video ? 'modal-trigger' : 'Нет видео' }}" id="video-trigger" href="#video">
                                    <img src="/frontend/images/button-camera.png" class="responsive-img" alt="video"/>
                                    <span>видео</span>
                                </a>
                            </div>
                            <div class="col s6 item-option center no-padding">
                                <a class="print" href="#" onclick="printsite();">
                                    <img src="/frontend/images/button-printer.png" class="responsive-img" alt="print"/>
                                    <span>распечатать</span>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col s12 wrapper-buttons">
                        <div class="col clearleft no-padding short-desc s12">
                            <p class="no-margin">{{ $product->pack }}</p>
                        </div>
                        <div class="col s12 no-padding productExcerpt">
                            {!!  $product->body !!}
                        </div>
                        {{--
                        <div class="col clearleft short-desc s12 no-margin">
                        @if ($product->available)
                            <p class="availability green-text bold no-margin"><img src="/frontend/images/available.png" alt=""/>Есть в
                                наличии</p>
                        @else
                            <p class="availability bold red-text no-margin"><i class="red-text fa fa-remove"></i> Нет в наличии</p>
                        @endif
                        </div>--}}
                    </div>
                </div>
            {{--
            <div class="col features">

                <div class="col s6 no-padding">
                    <h6><span class="green-text">&gt;&gt;</span> Бесплатная сборка</h6>

                    <p>непосредственно <span class="green-text italic">высоко- квалифицирован­ными специалистами</span>
                        компании «HouseFit» в городах Одесса и Киев.</p>
                </div>
                <div class="col s6 no-padding">
                    <h6><span class="green-text">&gt;&gt;</span> Отгрузка товара</h6>

                    <p>Отгрузка товара на тран­спортную фирму произво­дится в течении одного рабочего дня с момента
                        оформления заказа.</p>
                </div>
                <div class="col s6 no-padding">
                    <h6><span class="green-text">&gt;&gt;</span> Доставка в городах Одесса и Киев</h6>

                    <p>Теперь не нужно думать о доставке! Мы товар доста­вим сами и самое главное - <span
                                class="green-text italic">совершенно бесплатно</span>!</p>
                </div>
                <div class="col s6 no-padding">
                    <h6><span class="green-text">&gt;&gt;</span> Гарантия</h6>

                    <p>На все товары предостав­ляется фирменная гаран­тия и обслуживание <span
                                class="green-text italic">непо­средственно от произво­дителя.</span></p>
                </div>
                <div class="col s6 no-padding">
                    <h6><span class="green-text">&gt;&gt;</span> Доставка в регионах</h6>

                    <p>Для других регионов <span class="green-text italic">бесплатная доставка</span> на региональный
                        склад.</p>
                </div>
                <div class="col s6 no-padding">
                    <h6><span class="green-text">&gt;&gt;</span> Документы</h6>

                    <p>Полный комплект докумен­тов: паспорт, гарантийный талон, товарный чек.</p>
                </div>
            </div> --}}

            <div class="sampleQuestion col no-padding">
                <div>
                    <span>Sample question:</span>
                    <h5>Catalonia Square</h5>
                </div>
                    <p>You are now at the central square of Barcelona. Amazingly, what now is considered the center of the city only 150 years ago was a rural area just outside the city walls. The actual square was built in the beginning of 20th century following the design and the idea of the 19th century to connect the old town with the modern city. The square is famous for its 2 fountains which are beautifully illuminated during the night and numerous sculptures – works of famous Catalonian sculptors.</p>
                    <span class="question">Find a sculpture with a ship. What is its name?</span>
            </div>


            @include('frontend.partials.products.stock')
        </div>
        <div class="col s12 l3 asideFormProd no-padding">
            <form>
                <h4><span>{{ $product->title }}</span></h4>
                <table>
                    <tr>
                        <td>{{ $product->getPrice()}} <span>{{ $product->brand->title }}</span></td>
                        <td>
                            <span>quest's type:</span>
                            <br>or
                        </td>
                    </tr>
                </table>
                <div class="label">
                    <input id='team1' type="radio" name="team" value='1' checked>
                    <label for="team1">1 team</label>
                    <!--<div class="delimiter"></div>-->
                    <input id='team2' type="radio" name="team" value='2'>
                    <label for="team2">2 teams</label>
                </div>
                <div class="entryField">
                    <p>Enter the information:</p>
                    <input type='text' name="name" placeholder="you name">
                    <input type='email' name="email" placeholder="you email">
                    <textarea  name="comment" placeholder="you comment"></textarea>
                    <div class="lang">
                        <input type='radio' id="en" value='en' name="lang" checked>
                        <label for="en" data-text='en'></label>
                        <input type='radio' id="rus" value='rus' name="lang" checked>
                        <label for="rus" data-text='rus'></label>
                        <input type='radio' id="es" value='es' name="lang" checked>
                        <label for="es" data-text="es"></label>
                    </div>
                </div>
                <button type="submit" class="waves-effect waves-light">buy now</button>
            </form>
        </div>
            <div class="col s12 full-desc no-padding">
                <h5>About Quest “<span>{{ $product->title }}</span>”:</h5>
                <div class="full-descBody">
                   {!! $product->body !!}
                </div>
            </div>
            {{--
            <div class="col s12 m3 right clearleft">
                <div class="review-button center-align waves-effect waves-light">--}}
                    {{--@if(Auth::check())--}}
                        {{--<a href="#review" class="review-button white-text green uppercase modal-trigger" title="купить">
                            <input type="submit" name="add_review" class="review-button white-text green uppercase" value="оставить отзыв" title="купить">
                        </a>--}}
                        @if (session('message'))
                            {{--<div style="color: rgba(0, 128, 0, 0.76)">
                                <b>{{ session('message') }}</b>
                            </div>--}}
                        @endif
                    {{--@endif--}}
              {{--  </div>
            </div>--}}
            {{--
            <div class="col s12 m9 right reviews">
                @if(count($product->reviews))
                  <h3>Отзывы</h3>
                @endif
                @forelse($product->reviews as $review)
                    <div class="col s12 review">
                        <div class="review-img col s1">
                            @if($review->user->thumbnail && is_file(public_path($review->user->thumbnail)))
                                <img class="responsive-img circle" src="{{ url($review->user->thumbnail) }}" alt="{{ $review->user->name }}"/>
                            @else
                                <img class="responsive-img circle" src="/frontend/images/user.png" alt="{{ $review->user->name }}"/>
                            @endif
                        </div>
                        <div class="col s11 text-review">
                            <span class="author">{{ $review->user->name }}</span><span> | </span><span class="review-date">{{  $review->created_at->format('d.m.Y') }}</span>
                            <p class="no-margin">{{ $review->body }}</p>
                        </div>
                    </div>
                @empty
                <!-- When reviews is empty, fill it if you want, and remove check for reviews count -->
                @endforelse
            </div> --}}
        </div>
    </div>
</section>

<div id="video" class="modal">
    <div class="modal-content">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
        <div class="video-container">
            {!! $product->video !!}
        </div>
    </div>
</div>
<div id="application" class="modal">
    <div class="modal-content">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
        <div class="input-field col s12 center-align">
            <input placeholder="введите ваше имя" id="name" type="text" class="validate">
            <input placeholder="номер телефона" id="phone" type="text" class="validate">
            <input placeholder="email" id="email" type="text" class="validate">
            <input placeholder="примечание" id="message" type="text" class="validate">
            <button class="btn waves-effect waves-light" type="submit" name="action">Отправить  <i class="fa fa-envelope"></i></button>
        </div>
    </div>
</div>
<div id="object-3d" class="modalTest">
    <div class="modal-content center-align">
        <a href="#!" class="objClose modal-close waves-effect btn-flat"><i class="fa fa-close"></i></a>
            {{--<script src="/frontend/js/3dtour.js"></script>--}}
            {{--<div id="container">--}}
                {{--<div id="panoDIV" style="height:470px">--}}
                    {{--<script>--}}
                        {{--embedpano({target:"panoDIV",swf:"/{{ $product->flash_view }}",wmode:"direct"});--}}
                    {{--</script>--}}

                {{--</div>--}}
            {{--</div>--}}
       <object class="flashObject" width="550" height="400">
           <param name="movie" value="/{{ $product->flash_view }}">
           <embed src="/{{ $product->flash_view }}" width="550" height="400" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
       </object>
    </div>
    <div class="object-hover"></div>
</div>
<section class="related">
    <div class="container">
        <div class="row">
            <div class="col s12 product no-padding">
                @if(count($product->relatedProducts))
                <h3>Смотрите также</h3>
                    <div class="related-products">

                    @foreach($product->relatedProducts as $product)

                        @include('frontend.partials.products.product_template')

                    @endforeach

                </div>
                @endif
            </div>
        </div>
    </div>
    <!--/Menu-->
    <input type="hidden" value="{{ csrf_token() }}" id="token"/>
</section>
<!--  Scripts-->

    @if(Auth::check())
        <input type="hidden" value="{{ in_array($pid, null !== Session('rated') ? Session('rated') : []) }}" id="check"/>
    @else
        <input type="hidden" value="1" id="check"/>
    @endif

@endsection

@section('bottom-scripts')
    @include('frontend.partials.scripts.add_to_cart')
@endsection


@section('rate')

    <script>
//        $("input.addtocart-button-hover").click(function(){
//            $('#sold').openModal();
//        });

        console.log(!!$("#check").val());

        $('#rating_3').rating({
            fx: 'full',
            image: '/frontend/images/stars2.png',
            loader: '/frontend/images/ajax-loader.gif',
            url: location.href, /*обработка результатов голосования*/
            type: 'GET',
            readOnly: !!$("#check").val(),
            callback: function(responce){
                this._data.val = Math.round(responce);
                this.set();
                this.vote_success.fadeOut(2000);
            }
        });

    /* слайдер */
    $('.slider-for-product').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav-product'
    });
    $('.slider-nav-product').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for-product',
        focusOnSelect: true,
    });

    </script>
@endsection