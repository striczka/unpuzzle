@extends('frontend.layout')

@section('seo')
    <title>{{ 'How it works' }}</title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
@endsection

@section('content')
        <!-- Customize it as you want -->
    @if(isset($category->thumbnail) && is_file(public_path($category->thumbnail)))
        <div class="category-thumb relative">
            @if($category->thumb_link)
                <a href="{{ url($category->thumb_link) }}">
                    <img src="{{ asset($category->thumbnail) }}" alt="{{ $category->thumb_alt }}"/>
                    @if(isset($category->thumb_desc))
                        <div class="container category-desc">
                            {!! $category->thumb_desc !!}
                        </div>
                    @endif
                </a>
            @else
                <img src="{{ asset($category->thumbnail) }}" alt="{{ $category->thumb_alt }}"/>
                @if(isset($category->thumb_desc))
                    <div class="container category-desc absolute">
                        {!! $category->thumb_desc !!}
                    </div>
                @endif
            @endif
        </div>
    @endif
<section class="breadcrumbs ourQuests">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">{{ $page->title }}</li>
            </ol>
        </div>
    </div>
</section>

@if(Request::is("about"))
    @inject('settingsProvider', '\App\ViewDataProviders\SettingsDataProvider')
    @if(array_get($settingsProvider->getSettings(),'agreement'))
    <section class="content category-content about-additional-info">
        @if($page->thumbnail)
            <img class="banner-image" src="{{ url($page->thumbnail) }}" alt="{{$page->title}}">
        @endif
        <div class="container">
            <div class="row">
                <div class="ourQuestsText">
                    {!! array_get($settingsProvider->getSettings(),'agreement') !!}
                </div>
            </div>
        </div>
    </section>
    @endif
        <section class="how-it-works-content">
        <div class="container">
            <div class="row">
                <div class="col s12 text-page no-padding center-align red-text">
                    <h3 class="staticPage"><span>Our team</span></h3>
                </div>
            </div>
        </div>
    </section>
@endif

<section class="content {{ Request::is("how-it-works") ? 'how-it-works-content' : 'about-content' }}">
    <!--Simple Menu-->

    <div class="container">
        <div class="row">
            <div class="col s12 {{ Request::is("how-it-works") ? 'colour-block photo-block' : 'about-block'}} no-padding">
                {!! $page->content !!}
            </div>
        </div>
    </div>
    @if(Request::is("how-it-works"))
    <div class="container">
        <div class='faq'>
            <div class="row">
                @if(isset($page->video)&&!empty($page->video))
                <div class='col s12 m5 l5 no-padding'>
                    <div class="video">
                        {{--<video controls="true" height="100%" width="100%">--}}
                            {{--<source src="/{{$page->video}}" type="video/mp4">--}}
                        {{--</video>--}}
                        <video id="my-video" class="video-js" controls preload="auto" width="470" height="360"
                               poster="/{{$page->thumbnail}}" data-setup="{}">
                            <source src="/{{$page->video}}" type="video/mp4">
                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a web browser that
                                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                            </p>
                        </video>
                    </div>
                </div>
                @endif
                @if(isset($askedQuestions))
                <div class='col s12 m7 l7 no-padding'>
                    <h4 class="faqH4">FAQ (FREQUENTLY ASKED QUESTIONS)</h4>
                    <ul>
                        @foreach($askedQuestions as $key => $askedQuestion)
                        <li>
                            <a date-qFaq='{{ $key++ }}' onclick="faq(this);">{{ $key++ }}. {{ $askedQuestion->text }}</a>
                            <div date-faq="{{ $key++ }}">{{ $askedQuestion->answer }}</div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="banerHIW">
        <div class="container">
            <div class="row">
                <div class="col s12 m4 l4 no-padding">
                    <div class="item">
                        <img src="/images/Location.png">
                        <h5>New places</h5>
                        <p>Visit cool locations,<br> completing quests</p>
                    </div>
                </div>
                <div class="col s12 m4 l4 no-padding">
                    <div class="item">
                        <img src="/images/Camera.png">
                        <h5>memorable moments</h5>
                        <p>Solve interesting puzzles,<br> make a funny photo</p>
                    </div>
                </div>
                <div class="col s12 m4 l4 no-padding">
                    <div class="item">
                        <img src="/images/logoBaner.png">
                        <h5>Very interesting</h5>
                        <p>Find out lots of interesting facts and<br> legends about the city!</p>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    @endif
    <!--/Menu-->
</section>
    <section class="{{$banner->area}}">
        @if($banner->thumbnail)
            <img class="banner-image" src="{{ url($banner->thumbnail) }}" alt="{{$banner->title}}">
        @endif
        <div class="container">
            <div class="row">
                <div class="banner megamenu">
                    <div class="col no-padding s12 center-align">
                        @if($banner->title)
                            <h4 class='white-text'>
                                @if($banner->link )
                                    <a rel="nofollow" href="{{ $banner->link }}" class="title-company-link">
                                        {!! $banner->title !!}
                                    </a>
                                @else
                                    {!! $banner->title !!}
                                @endif
                            </h4>
                        @endif

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
                        <div class="card-title clearfix">{!! $banner->caption !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script type="text/javascript">
    function faq(thisQ){
        var t = thisQ;
        if($(t).attr('class') != 'active'){
            $('div[date-faq]').slideUp('slow');
            $('a[date-qfaq]').removeClass('active');
            $(t).next().slideDown('slow');
            $(t).addClass('active');
        }else{
           /* $(t).next().fadeOut(); */
            $(t).next().slideUp('slow');
            $(t).removeClass('active');
        }
    }
</script>

@endsection

@section('bottom-scripts')
    <script src="/frontend/js/video.js"></script>
    <script>
        function getContent(e){
            var body = e.html();
            e.addClass("active");
            var outerHeight = 0, contents = e.children("p:not(:last-child)");
            contents.each(function() {
                console.log( $(this).height());
                outerHeight += $(this).height();
            });
            var digit = e.find(".round-digit"),
                    color = digit.css("background-color"),
                    top = e.find(".photo-sector-" + digit.text()).css("background-position-y"),
                    leftPre = e.find(".photo-sector-" + digit.text()).css("background-position-x"),
                    left = parseInt(leftPre) + 15,
                    d = (outerHeight + 17)*2;
            e.html("");
            e.append("<div class='active-content' style='border-color:" +
                    color +"; top: " + top + "; margin-left: -" + left + "px;width:" + d +
                    "px;height:" + d + "px'><span class='digit'>" + digit.text() + "</span>"+ body +"</div>");
        }
        $(function(){
            var ps = $(".photo-block td > p:not(:last-child)"),
                elems = $(".colour-block td");
            ps.each(function(){
                if($(this).find(".uppercase").length > 0){
                    console.log("here");
                }
                else{
                    $(this).hide();
                    $(this).find("span").css({"color":"#2b2b2b", "text-align":"center","line-height":"19px"});
                }
            });
            if($(window).width() < 360){
                elems.each(function(){
                    getContent($(this));
                });
            }
            else{
                elems.click(function(){
                    if($(this).hasClass("active")){
                        $(this).removeClass("active");
                        $(this).find(".digit").remove();
                        var exBody = $(this).find(".active-content").html();
                        $(this).html(exBody);
                    }
                    else{
                        getContent($(this));
                    }
                });
            }

            });
    </script>
@endsection