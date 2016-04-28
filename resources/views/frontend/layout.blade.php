<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    @yield('seo')
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" media="print" href="/frontend/css/print.css">
	<link href="/frontend/css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
    @yield('top-scripts')
    <link rel="stylesheet" href="/css/additional.css">
</head>
<body>
    @include('frontend.partials.header')
    @yield('content')
    @include('frontend.partials.footer')
<div id="application" class="modal">
    <div class="modal-content">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
        <div class="input-field col s12 center-align">
        </div>
    </div>
</div>
<div id="callorder" class="modal">
    <div class="modal-content">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
        <div class="input-field col s12 center-align">
            <form action="{!! route('mail.me') !!}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_view" value="callback"/>
                <input required="required" placeholder="введите ваше имя" id="name_call" name="name" type="text" class="validate">
                <input required="required" placeholder="номер телефона" id="phone_call" name="phone" type="text" class="validate">
                <button class="btn waves-effect waves-light" type="submit" name="action">Заказать  <i class="fa fa-phone"></i></button>
            </form>
        </div>
    </div>
</div>
<div id="forgot" class="modal">
    <div class="modal-content">
        <form action="{{ url('password/email') }}" method="POST">
            {!! csrf_field() !!}
            <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
            <div class="input-field col s12 center-align">
                <input placeholder="введите ваш e-mail" id="name_call" type="text" name="email" class="validate">
                <button class="btn waves-effect waves-light" type="submit" name="action"> Выслать письмо <i class="fa fa-envelop"></i></button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/frontend/js/jquery-2.1.3.min.js"></script>
@yield('bottom-scripts')
@yield('rate')
@yield('filter_handler')
</body>
</html>
