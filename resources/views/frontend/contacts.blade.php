@extends('frontend.layout')

@section('seo')
    <title>{{ 'Контакты' }}</title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
@endsection

@section('content')
<section class="breadcrumbs">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li class="active">Контакты</li>
            </ol>
        </div>
    </div>
</section>

<section class="content">
    <!--Simple Menu-->
    <div class="container">
        <div class="row">
            <div class="col s12 text-page no-padding">
                <div class="col s12">
                    <h3>Контакты</h3>
                    <p class="col s12 deeppurple">Нажмите на флажок, чтобы узнать точный адрес и контактные данные.</p>
                    <div id="map">
                        <div id="map-container">
                            <script src="js/3dtour.js"></script>
                            {!! array_get($Settings,'map_code') !!}
                        </div>
                    </div>
                    <div class="col s12 feedback">
                        <h4>Обратная связь</h4>
                        <p class="col s12">Отправьте нам е-мейл. Все поля, помеченные *, обязательны для заполнения.</p>
                        <form action="{!! route('mail.me') !!}" id="contactForm" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_view" value="contact"/>
                            <div class="row">
                                <div class="col s12 m5">
                                    <div class="form-group">
                                        <input required="required" name="name" class="form-control col" id="name"
                                               placeholder="Ваше имя (*)" title="Name" value="" type="text">
                                    </div>
                                    <div class="form-group">
                                        <input required="required" name="email"
                                               class="form-control col validate-email" id="email"
                                               placeholder="Ваш email (*)" title="Email" value="" type="text">
                                    </div>
                                    <div class="form-group">
                                        <input class="input-text col form-control" name="phone" id="phone"
                                               placeholder="Ваш номер телефона" title="Telephone" value="" type="text">
                                    </div>
                                </div>
                                <div class="col s12 m7">
                                    <div class="form-group">
                                        <textarea required="required" name="comment" placeholder="Ваше сообщение (*)" id="comment" title="Comment"
                                                  class="form-control col input-text" cols="5"
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="buttons-set clearfix">
                                        <button class="btn waves-effect waves-light" type="submit" name="action">Отправить</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!--/Menu-->
</section>

@endsection