@inject('cartProvider', '\App\ViewDataProviders\CartDataProvider')
@inject('settingsProvider', '\App\ViewDataProviders\SettingsDataProvider')

@section('seo')
    <title>{{ 'Корзина' }}</title>
    <meta name="description" content=""/>
    <meta name="description" content=""/>
@endsection

@extends('frontend.layout')
@section('content')


    <section class="breadcrumbs">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li class="active">Корзина</li>
            </ol>
        </div>
    </div>
</section>

<section class="content" id="cart">
    <div class="container" v-el="cartContent" style="display: none;">

        <h4 v-if="!len > 0">Корзина пуста</h4>
        <div class="row"  v-show="len > 0">
            {{--<h1>@{{ len == 0 }}</h1>--}}

            <div>
                <div class="basket-row col s12 no-padding">
                    <div class="col m1 hide-on-small-and-down">Фото</div>
                    {{--<div class="col s1">№</div>--}}
                    <div class="col m1 hide-on-small-and-down">Код</div>
                    <div class="col s5 m4">Название</div>
                    <div class="col s3 m2 center">Кол-во</div>
                    <div class="col s3 center m3">Всего</div>
                </div>
                <!--Товар в корзине-->

                {{--@foreach(cart->getContent() as product)--}}
                {{--stockProducts--}}


            <div v-repeat="productsSet in stockProducts" class="col s12 no-padding _set row">
                <h5>Акционное предложение</h5>
                <div class="basket-item col s12 no-padding" v-repeat="product in productsSet">

                    <div class="col m1 hide-on-small-and-down item-image">
                        <a href="/@{{ product.options.categorySlug }}/@{{ product.options.productSlug }}">
                            <img v-attr="src: product.options.thumbnail" class="responsive-img" v-if="product.options.thumbnail"/>
                            <img src="/frontend/images/default.png" class="responsive-img" v-if="!product.options.thumbnail"/>
                        </a>
                    </div>

                    <div class="col m1 hide-on-small-and-down">
                        <p>@{{ product.options.article }}</p>
                    </div>
                    <div class="col s5 m4">
                        <p><a href="/@{{ product.options.categorySlug }}/@{{ product.options.productSlug }}">@{{ product.name }}</a></p>
                        <p>@{{ product.options.excerpt }}</p>
                    </div>
                    <div class="col s3 m2 center">

                        <span class="quantity" v-show="product.id != product.options.main_in_set">@{{ product.qty }}</span>

                        <input type='number'
                               value="@{{ product.qty }}"
                               v-on="input: updateItem(product, this)"
                               debounce="500"
                               class="item-quantity"
                               v-show="product.id == product.options.main_in_set">

                        <div class="options" v-show="product.id == product.options.main_in_set">
                            <button type="submit" v-on="click: deleteItem(product.rowid)"><i class="fa fa-trash deeppurple"></i></button>
                        </div>
                    </div>
                    <div class="col s3 m3 center">
                        <p v-show="product.subtotal > 0">
                            @{{ product.subtotal }} <span>грн</span>
                        </p>
                        <p v-show="product.subtotal == 0">
                            <span style="color:indianred;font-size:16px">В подарок!</span>
                        </p>

                     </div>
                    </div>
                </div>
                <div class="basket-item col s12 no-padding" v-repeat="product in cart">
                    <div class="col m1 hide-on-small-and-down item-image">
                        <a href="/@{{ product.options.categorySlug }}/@{{ product.options.productSlug }}">
                            <img v-attr="src: product.options.thumbnail" class="responsive-img" v-if="product.options.thumbnail"/>
                            <img src="/frontend/images/default.png" class="responsive-img" v-if="!product.options.thumbnail"/>
                        </a>
                    </div>

                    {{--<div class="col s1"><p>1</p></div>--}}
                    <div class="col m1 hide-on-small-and-down">
                        <p>@{{ product.options.article }}</p>
                    </div>
                    <div class="col s5 m4">
                        <p><a href="/@{{ product.options.categorySlug }}/@{{ product.options.productSlug }}">@{{ product.name }}</a></p>
                        <p>@{{ product.options.excerpt }}</p>
                    </div>
                    <div class="col s3 m2 center">

						<input type='number'
                            value="@{{ product.qty }}"
                            v-on="input: updateItem(product, this)"
                            debounce="500"
                            class="item-quantity"
                            v-attr='disabled: product.options.in_set_with'>

                        <div class="options">
                            <button type="submit" v-on="click: deleteItem(product.rowid)"><i class="fa fa-trash deeppurple"></i></button>
                        </div>
                    </div>
                    <div class="col s3 m3 center">
                        <p v-show="product.subtotal > 0">
                            @{{ product.subtotal }} <span>грн</span>
                        </p>
                        <p v-show="product.subtotal == 0">
                            <span style="color:indianred;font-size:16px">В подарок!</span>
                        </p>

                    </div>
                </div>
            </div>
            <!--конец товара в корзине. ха ха ха-.-->
            <div class="col s12 no-padding grey">
                <div class="col s9 right-align" id="order_subtotal"><p class="bold uppercase">Всего:</p></div>
                <div class="col s3 center" id="order_subtotal_basket"><p class="bold deeppurple">@{{ total }} грн</p></div>
            </div>
            <!--Registration - DON'T show if user authorized on the site-->

            <div class="row">
                @if(!Auth::check())
                <div class="col s12 m6 cart-authentication">
                    <ul class="tabs">
                        <li class="tab col s3"><a class="active waves-effect waves-light" href="#register">Новый покупатель</a></li>
                        <li class="tab col s3"><a class="waves-effect waves-light" href="#login">Постоянный клиент</a></li>
                        <li class="tab col s3"><a class="waves-effect waves-light" href="#ones">Разовая покупка</a></li>
                    </ul>
                    <div id="register" class="col s12 no-padding">
                        <form id="order-form" role="form" method="POST" action="{{ url('/auth/register') }}">
                            @include('frontend.partials.registration_fields')
                        </form>
                    </div>
                    <div id="login" class="col s12 no-padding">
                        <section class="order-cont">
                            <form role="form" method="POST" action="{{ url('/auth/login') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <p class="formField">
                                    <label for="login-email" class="label label_order col s12 m6 l4">Электронная почта:</label>
                                    <input id="login-email" autocomplete="off" maxlength="100" class="input input_l input_order login-email col s12 m6 l7" tabindex="1" name="email" type="text" value="">
                                </p>
                                <p class="formField">
                                    <label for="login-password" class="label label_order col s12 m6 l4">Пароль:</label>
                                    <input id="login-password" autocomplete="off" maxlength="100" class="input input_l input_order login-password col s12 m6 l7" tabindex="2" name="password" type="password" value="">
                                </p>
                                <div class="col s12 no-padding">
                                    <button id="user_login" class="btn waves-effect waves-light user_login" tabindex="3"><span class="btn-in">Войти</span></button>
                                </div>
                                <ol class="col s12 remember">
                                    {{--<li><a href="#" class="order-forgot-login-link">Я забыл логин</a></li>--}}
                                    <li><a href="#" class="order-forgot-pwd-link">Забыли пароль?</a></li>
                                </ol>
                            </form>
                        </section>
                    </div>
                    <div id="ones" class="col s12 no-padding">
                        <form id="order-form" role="form" method="POST" action="/buy">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<p class="formField">
									<label for="order-name" class="col s12 m4 l4">ФИО:<span class="red-text"> *</span></label>
									<input class="col s12 m6 l7" id="order-name" placeholder="введите имя, фамилию и отчество" tabindex="1" name="name" type="text" value="" required>
								</p>
								<p class="formField">
									<label for="order-telephone" class="col s12 m4 l4">Телефон:<span class="red-text"> *</span></label>
									<input class="col s12 m6 l7" id="order-telephone" placeholder="введите номер телефона" tabindex="3" name="phone" type="text"value="" required>
								</p>
								<p class="formField">
									<label for="order-email" class="col s12 m4 l4">Электронная почта:<span class="red-text"> *</span></label>
									<input class="col s12 m6 l7" id="order-email" placeholder="введите ваш email" tabindex="4" name="email" type="text" value="" required>
								</p>
								<p class="formField">
									<label for="order-address" class="col s12 m4 l4">Город:</label>
									<input class="col s12 m6 l7" id="order-city" placeholder="введите город" tabindex="6" name="city" type="text" value="" required>
								</p>
								{{--<p class="formField">--}}
									{{--<label for="order-address" class="col s12 m4 l4">Адрес:</label>--}}
									{{--<input class="col s12 m6 l7" id="order-address" placeholder="введите адрес" tabindex="7" name="address" type="text" value="">--}}
								{{--</p>--}}

                            <div class="col s12 m6 shipping">
                                <label for="form_shipping" class="green-text bold uppercase">Способ оплаты:</label>
                                @foreach($cartProvider->getPaymentMethods() as $key => $method)
                                    <p>
                                        {!! Form::radio('payment', $value = $method->id, $checked = null, ['class' => 'with-gap', 'id' => 'payment'.$key,'name' => 'payment' ]) !!}
                                        {{--<input class="with-gap" name="payment" form="buy" type="radio" id="payment{{ $key }}" value="{{ old('payment', $method->id) }}" />--}}
                                        <label for="payment{{ $key }}">{{ $method->title }}</label>
                                    </p>
                                @endforeach
                            </div>

                            <div class="col s12 m6 payment">
                                <label for="form_payment" class="green-text bold uppercase">Способ доставки:</label>
                                @foreach($cartProvider->getShipmentMethods() as $key => $method)
                                    <p>
                                        {!! Form::radio('shipment', $value = $method->id, $checked = null, ['class' => 'with-gap', 'id' => 'shipping'.$key, 'name' => 'shipment' ]) !!}
                                        {{--<input class="with-gap" name="shipment" form="buy"  type="radio" id="shipping{{ $key }}" value="{{ old('shipment', $method->id) }}"/>--}}
                                        <label for="shipping{{ $key }}">{{ $method->title }}</label>
                                    </p>
                                @endforeach
                            </div>
                            <div id="agreed_div" class=" clearfix">
                                <br/>
                                <input  name="checked" id="agreed_field" value="1" type="checkbox"  class="terms-of-service" autocomplete="off">
                                <label for="agreed_field">
                                    <p class="no-margin">Я согласен с условиями обслуживания
                                        <a class="modal-trigger" href="#otc">(Условия обслуживания *)</a>
                                    </p>
                                </label>
                            </div>

                            <input type="hidden" name="ones" value="1"/>

                            <div class="col s12 no-padding">
                                <br/>
                                <button id="" class="btn waves-effect waves-light user_login" tabindex="3">
                                    <span class="btn-in">Оформить покупку</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--Shipping & Payment-->
                @else
                <div class="col s12 m6 shipping">
                    <label for="form_shipping" class="green-text bold uppercase">Способ оплаты:</label>
                    @foreach($cartProvider->getPaymentMethods() as $key => $method)
                        <p>
                            {!! Form::radio('payment', $value = $method->id, $checked = null, ['class' => 'with-gap', 'id' => 'payment'.$key, 'form' => 'buy','name' => 'payment' ]) !!}
                            {{--<input class="with-gap" name="payment" form="buy" type="radio" id="payment{{ $key }}" value="{{ old('payment', $method->id) }}" />--}}
                            <label for="payment{{ $key }}">{{ $method->title }}</label>
                        </p>
                    @endforeach
                </div>
                <div class="col s12 m6 payment">
                    <label for="form_payment" class="green-text bold uppercase">Способ доставки:</label>
                        @foreach($cartProvider->getShipmentMethods() as $key => $method)
                            <p>
                                {!! Form::radio('shipment', $value = $method->id, $checked = null, ['class' => 'with-gap', 'id' => 'shipping'.$key, 'form' => 'buy','name' => 'shipment' ]) !!}
                                {{--<input class="with-gap" name="shipment" form="buy"  type="radio" id="shipping{{ $key }}" value="{{ old('shipment', $method->id) }}"/>--}}
                                <label for="shipping{{ $key }}">{{ $method->title }}</label>
                            </p>
                        @endforeach
                </div>
                <!--End of dat shet-->
                @endif
            </div>
            <div class="col clearing s12"></div>
            @if(Auth::check())
            <div class="col s12 m6 note">
                <div id="customer_note_input">
                    <label for="customer_note_field" class="green-text bold uppercase">Примечание:</label>
                    {{--{!! Form::textarea('') !!}--}}
                    <textarea id="customer_note_field" form="buy" name="note">{{ old('note') }}</textarea>
                </div>
            </div>
            <div class="col s12 m6">

                <div id="onepage_info_above_button">

                    <form action="/buy" method="POST" id="buy" >
                        {!! csrf_field() !!}
                        <!-- show TOS and checkbox before button -->
                        <div id="agreed_div" class="right-align clearfix">
                            <input  name="checked" id="agreed_field" value="1" type="checkbox"  class="terms-of-service" autocomplete="off">
                            <label for="agreed_field">
                                <p class="no-margin">Я согласен с условиями обслуживания
                                    <a class="modal-trigger" href="#otc">(Условия обслуживания *)</a>
                                </p>
                            </label>
                        </div>
                            <div class="formField" id="agreed_input">
                        </div>
                        <!-- end show TOS and checkbox before button -->
                        <div class="last-step">
                            <button type="submit" class="waves-effect waves-light btn confirm">Оформить заказ</button>
                        </div>
                    </form>
                </div>


                @endif
            </div>
                @if (count($errors) > 0)
                    <div class="col s6">
                        <ul style="padding-top: 20px;">
                            @foreach ($errors->all() as $error)
                                <li style="color: indianred">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            <div class="col s12 offset-top-30px"></div>
        </div>


        <input type="hidden" value="{{ csrf_token() }}" v-model="token"/>
        {{--<pre>--}}
            {{--@{{ $data | json}}--}}
        {{--</pre>--}}
    </div>
<div id="otc" class="modal">
    <div class="modal-content">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
        <p>{!! $settingsProvider->getAgreement() !!}</p><!--тег p можно убрать-->
    </div>
</div>
</section>

@endsection

@section('bottom-scripts')
    {!! Html::script("admin/assets/js/vue.js") !!}

    <script>
        new Vue({

            el: "#cart",
            data: {
                cart: {},
                token: null,
                len: 0,
                total: 0,
                stockProducts: null
            },

            ready: function(){
                var vue = this;
                vue.getContent();
//                setInterval(function(){
//                    vue.getContent();
//                }, 2000);

                $(this.$$.cartContent).show();
            },

            methods: {
                getContent: function(){
                    var vue = this;
                    $(this.$$.cartContent).css('opacity', '.4');
                    $.post("/cart/get_content", {_token: this.token }).done(function(cart){
                        vue.stockProducts = cart.stockProducts;
                        vue.cart = cart.content;
                        vue.len = cart.len;
                        vue.total = cart.total;

                        var cart = $("#_cart");
                        cart.find('.qty').html(vue.len);
                        cart.find('.qty-items').html(vue.len);
                        cart.find('._sum').html(vue.total);

                        $(vue.$$.cartContent).css('opacity', '1');
                    });
                },
                deleteItem: function(id) {
                    var vue = this;
                    $.post('/cart/delete_item', { _token: this.token, rowid: id})
                        .done(function(){
                                vue.getContent()
                            })
                },

                updateItem: function(product, _qty){

                    var vue = this,
                        qty = $(_qty.$el).find('.item-quantity');

//                    console.log(product.options.instance);

                    qty.css('border-color', '#7cb342');
                    if(qty.val().match(/^[0-9]{1,3}$/) && qty.val() > 0){
                        $.post('/cart/update_item', {
                            _token: this.token,
                            product: product,
                            qty: qty.val(),
                            instance: product.options.instance
                        }).done(function(){
                            vue.getContent();
                        })
                    } else if(qty.val().match(/^\d{0}$/)){
                        qty.css('border-color', 'red');
                    } else {
                        $(_qty.$el).find('.item-quantity').val(product.qty);
                    }
                }
            }



        })
    </script>


@endsection
