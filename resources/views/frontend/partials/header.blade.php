@inject('settingsProvider', '\App\ViewDataProviders\SettingsDataProvider')
<section class="header">
    <div class="container">
        <div class="row">
            @if(!Request::is('game'))
            <div class="no-padding logo-col left">
                <a id="logo-container" href="/" class="brand-logo">
                    <img class="responsive-img" src="/frontend/images/logo.png"/>
                </a>
            </div>
            <div class="contact-info left ">
                <div class="red-string red s12 no-padding col ">
                    <span class="lang right"><a href="/es">es</a></span>
                    <span class="lang right"><a href="/rus">rus</a></span>
                    <span class="lang right"><a href="/en">en</a></span>
                    <span class="right">
                        @if(array_get($settingsProvider->getSettings(),'footer_phone2'))
                            <a class="tel"
                               href="tel:{{ preg_replace('/[^\d+]+/','',
                               array_get($settingsProvider->getSettings(),'footer_phone2')) }}">
                            {{ array_get($settingsProvider->getSettings(),'footer_phone2') }} <span class="tel"> (whatsapp)
                            </span>
                            </a>
                        @endif
                    </span>
                    <span class="right">
                        @if(array_get($settingsProvider->getSettings(),'footer_phone2'))
                            <a class="tel"
                               href="tel:{{ preg_replace('/[^\d+]+/','',
                           array_get($settingsProvider->getSettings(),'footer_phone1')) }} ">
                            {{ array_get($settingsProvider->getSettings(),'footer_phone1') }} <span class="tel"> (call)
                            </span>
                            </a>
                        @endif
                    </span>
                    <span class="mail right">
                        @if(array_get($settingsProvider->getSettings(),'contact_email'))
                            <img src="/frontend/images/mail.png" alt="">
                            {{ array_get($settingsProvider->getSettings(),'contact_email') }}
                        @endif
                    </span>
                </div>

                @include('frontend.partials.nav_menu')
            </div>
            @endif
            {{--<div class="col s12 m4 l6 search-box">--}}
                {{--<form action="{{ route('search') }}" method="GET">--}}
                    {{--<input class="search-form" type="search" placeholder="" name="search" value="{{ Request::get('search') }}"/>--}}
                    {{--<div class="links">--}}
                        {{--<span><a href="#callorder" class="modal-trigger"><i class="fa fa-phone green-text"></i>обратный звонок</a></span>--}}
                        {{--<span><a href="#application" class="modal-trigger"><img src="/frontend/images/icon-mail.png" />оставить заявку</a></span>--}}
                        {{--@if(Auth::check())--}}
                            {{--<span><a href="{{ route('cabinet') }}"><img src="/frontend/images/icon-login.png" />Кабинет</a></span>--}}
                            {{--<span><a href="/auth/logout"> Выход</a></span>--}}
                        {{--@else--}}
                            {{--<span><a href="{{ route('login') }}"><img src="/frontend/images/icon-login.png" />Вход</a></span>--}}
                            {{--<span><a href="{{ route('register') }}"><img src="/frontend/images/icon-reg.png" />Регистрация</a></span>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
            {{--<div class="col s12 m4 l3 contacts">--}}
                    {{--@if(array_get($settingsProvider->getSettings(),'header_phone1'))--}}
                        {{--<p class="phone right-align"><img src="/frontend/images/tel.png" /> {{ array_get($settingsProvider->getSettings(),'header_phone1') }}</p>--}}
                    {{--@endif--}}
                    {{--@if(array_get($settingsProvider->getSettings(),'header_phone2'))--}}
                        {{--<p class="phone right-align"><img src="/frontend/images/tel.png" /> {{ array_get($settingsProvider->getSettings(),'header_phone2') }}</p>--}}
                    {{--@endif--}}

                    {{--@if(array_get($settingsProvider->getSettings(),'youtube'))--}}
                            {{--<span class="social right"><a href="{{ array_get($settingsProvider->getSettings(),'youtube') }}"><img src="/frontend/images/youtube.png" alt=""/></a></span>--}}
                    {{--@endif--}}
                    {{--@if(array_get($settingsProvider->getSettings(),'twitter'))--}}
                        {{--<span class="social right"><a href="{{ array_get($settingsProvider->getSettings(),'twitter') }}"><img src="/frontend/images/twitter.png" alt=""/></a></span>--}}
                    {{--@endif--}}
                    {{--@if(array_get($settingsProvider->getSettings(),'facebook'))--}}
                        {{--<span class="social right"><a href="{{array_get($settingsProvider->getSettings(),'facebook')}}"><img src="/frontend/images/facebook.png" alt=""/></a></span>--}}
                    {{--@endif--}}
                    {{--@if(array_get($settingsProvider->getSettings(),'vkontakte'))--}}
                            {{--<span class="social right"><a href="{{ array_get($settingsProvider->getSettings(),'vkontakte') }}"><img src="/frontend/images/vk.png" alt=""/></a></span>--}}
                    {{--@endif--}}
            {{--</div>--}}
        </div>
    </div>
</section>
