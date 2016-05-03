@inject('settingsProvider', '\App\ViewDataProviders\SettingsDataProvider')
@inject('categoriesProvider', '\App\ViewDataProviders\CategoriesDataProvider')
<footer class="page-footer">
    <div class="container">
        <div class="row">
            <div class="logo-wrap left">
                <a id="logo-container-footer" href="/" class="brand-logo">
                    <img class="responsive-img" src="/frontend/images/logo-footer.png"/>
                </a>
                <p>&copy; 2009—2015. All rights reserved</p>
            </div>
            <div class="nav nav-wrap left">
                <ul>
                    <li><a class="left" href="/about">home</a></li>
                    <li><a class="left" href="/service">our quests</a></li>
                    <li><a class="left" href="/news">how it works</a></li>
                    <li><a class="left" href="/sale">testimonials</a></li>
                    <li><a class="left" href="/new">blog</a></li>
                    <li><a class="left" href="/contacts">about us</a></li>
                </ul>
            </div>
            <div class="contact-wrap left">
                <ul class="right-align">
                    {{--@if(array_get($settingsProvider->getSettings(),'address'))--}}
                        {{--<li class="map">{{ array_get($settingsProvider->getSettings(),'address') }}</li>--}}
                    {{--@endif--}}
                    <li class="phone">
                        @if(array_get($settingsProvider->getSettings(),'footer_phone2'))
                            <a class=""
                               href="tel:{{ preg_replace('/[^\d+]+/','',array_get($settingsProvider->getSettings(),'footer_phone1')) }} ">
                                <span class="tel">{{ array_get($settingsProvider->getSettings(),'footer_phone1') }}</span>
                            </a>
                        @endif
                    </li>
                    <li class="phone">
                        @if(array_get($settingsProvider->getSettings(),'footer_phone2'))
                            <a class=""
                               href="tel:{{ preg_replace('/[^\d+]+/','',array_get($settingsProvider->getSettings(),'footer_phone2')) }}">
                               <span class="tel"> {{ array_get($settingsProvider->getSettings(),'footer_phone2') }}</span></a>
                        @endif
                    </li>
                    @if(array_get($settingsProvider->getSettings(),'contact_email'))
                    <li class="mail">e-mail: {{ array_get($settingsProvider->getSettings(),'contact_email') }}</li>
                    @endif
                    <li class="socials">
                        @if(array_get($settingsProvider->getSettings(),'youtube'))
                            <span class="link-icon"><a href="{{ array_get($settingsProvider->getSettings(),'youtube') }}">
                                    <img src="/images/soc-footer_03.png" alt="" width="23" height="22"></a></span>
                        @endif
                        @if(array_get($settingsProvider->getSettings(),'twitter'))
                            <span class="link-icon"><a href="{{ array_get($settingsProvider->getSettings(),'twitter') }}">
                                    <img src="/images/soc-footer_05.png" alt="" width="23" height="22"></a></span>
                        @endif
                        @if(array_get($settingsProvider->getSettings(),'facebook'))
                            <span class="link-icon"><a href="{{array_get($settingsProvider->getSettings(),'facebook')}}">
                                    <img src="/images/soc-footer_07.png" alt="" width="23" height="22">
                                </a>
                        </span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<section class="footer-copyright">
    <div class="container center-align">
        <p class="no-margin white-text">created by <a class="white-text" href="http://msstudio.com.ua/">Mobios</a></p>
    </div>
</section>