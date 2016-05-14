@extends('frontend.layout')

@section('seo')
    <title>{{ 'Blog' }}</title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
@endsection


@section('content')
    <section class="breadcrumbs">
        <div class="container">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li class="active">Blog</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
    <!--Simple Menu-->
        <h3 class="content-h3"><span class="red-text">Blog</span></h3>
        <div class="container">
            <div class="row"><div class="col s12 text-page no-padding">
                    {{--<h3>{{ $page->title }}</h3>--}}
                    @foreach($articles as $key => $article)
                        
                        @if($key == 0)
                            <div class="post-first col s12 m12 l12 wow no-padding fadeInUp animated" data-wow-delay="0.{{ $key + 2 }}s">
                            <div class="post-row-center no-padding col s12 ">
                                <div class="hide-on-small-and-down">
                                    <div class="post-date">{{ date('d\.m\.y', strtotime($article->published_at)) }}</div>
                                </div>
                                <div class="post-media no-padding col s12 m6 l6">
                                    <div class="media-inner">
                                        <a href="/news/{{ $article->id }}/{{ $article->slug }}" title="{{ $article->title }}!">
                                            <img src="{{ url($article->thumbnail) }}" alt="{{ $article->title }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="post-content-outer col s12 m6 l6">
                                    <header class="single">
                                        <h4>
                                            <a href="/news/{{ $article->id }}/{{ $article->slug }}" title="{{ $article->title }}">{{ substr($article->title,0,44) }}</a>
                                        </h4>
                                    </header>
                                    <p>{!! $article->excerpt !!}</p>
                                    <a href="/news/{{ $article->id }}/{{ $article->slug }}" class="waves-effect waves-light btn btn-RM">Reed more</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <!--Post-->
                        <div class="post col s12 m12 l6 wow fadeInUp animated" data-wow-delay="0.{{ $key + 2 }}s">
                            <div class="post-row-center no-padding col s12 ">
                                <div class="hide-on-small-and-down">
                                    <div class="post-date">{{ date('d\.m\.y', strtotime($article->published_at)) }}</div>
                                </div>
                                <div class="post-media no-padding col s12 m6 l6">
                                    <div class="media-inner">
                                        <a href="/news/{{ $article->id }}/{{ $article->slug }}" title="{{ $article->title }}!">
                                            <img src="{{ url($article->thumbnail) }}" alt="{{ $article->title }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="post-content-outer col s12 m6 l6">
                                    <header class="single">
                                        <h4>
                                            <a href="/news/{{ $article->id }}/{{ $article->slug }}" title="{{ $article->title }}">{{ substr($article->title,0,44) }}</a>
                                        </h4>
                                    </header>
                                    <div class="articleExcerpt"><p>{!! $article->excerpt !!}</p> </div>
                                    <a href="/news/{{ $article->id }}/{{ $article->slug }}" class="waves-effect waves-light btn btn-RM">Reed more</a>
                                </div>
                            </div>
                        </div>
                        <!--End of Post-->
                         @endif
                    @endforeach

                    <div class="col s12 center arts_pag">
                        {!! $articles->render() !!}
                    </div>
                </div>
            </div>
        </div>
        <!--/Menu-->
    </section>

@endsection

@section('__bottom-scripts')
    <script>
        var post = 4; // - количество отображаемых новостей
        hidenews = "скрыть";
        shownews = "показать ещё";

        $(".more").html( shownews );
        $(".post:not(:lt("+post+"))").hide();

        $(".more").click(function (e){
            e.preventDefault();
            if( $(".post:eq("+post+")").is(":hidden") )
            {
                $(".post:hidden").show();
                $(".more").html( hidenews );
            }
            else
            {
                $(".post:not(:lt("+post+"))").hide();
                $(".more").html( shownews );
            }
        });
    </script>
@endsection