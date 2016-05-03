@extends('frontend.layout')

@section('seo')
    <title>{{ $article->meta_title ?: $article->title }}</title>
    <meta name="description" content="{{ $article->meta_description ?: $article->excerpt }}"/>
    <meta name="keywords" content="{{ $article->meta_keywords ?: $article->title }}"/>
@endsection


@section('content')
    <section class="breadcrumbs">
        <div class="container">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="/">Home</a></li>
                    <li><a href="/news">Blog</a></li>
                    <li class="active">{{ $article->title }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <h3 class="content-h3"><span class="red-text">{{ $article->title }}</span></h3>
        <!--Simple Menu-->
        <div class="container">
            <div class="row single-post-row">
                <div class="col s12 m9 l9 text-page no-padding">
                    <div class="col single-post s12 fadeIn animated wow" data-wow-delay="0.3s">
                        
                        <div class="col date s12">
                            {{--<span>{{ date('m',strtotime($article->published_at)) }} </span><span> {{ date('d \'y', strtotime($article->published_at)) }} </span>--}}
                            <span>{{ $article->published_at->formatLocalized('%B %d %Y') }}</span>
                        </div>
                        <div class="col s12">
                            <img src="{{ url($article->thumbnail) }}" class="responsive-img" alt="{{ $article->title }}">
                        </div>
                        <div class="col s12">
                            {!! $article->content !!}
                        </div>
                    </div>
                </div>
                <aside class="aside-article col s12 m3 l3 no-padding">
                    <div class="topAside">
                        <span class="span-h3">OUR QUESTS</span>
                        <ul>
                            <li><a href="">Must see Barcelona</a></li>
                            <li><a href="">Be hipster: take the hipster test</a></li>
                            <li><a href="">Must see Barcelona</a></li>
                            <li><a href="">Be hipster: take the hipster test</a></li>
                            <li><a href="">Must see Barcelona</a></li>
                            <li><a href="">Be hipster: take the hipster test</a></li>
                        </ul>
                    </div>
                    <div class="bottomAside">
                        <span class="span-h3">BLOG</span>
                        <ul>
                            <li><a href="">Go all the way from Catalonia Square</a></li>
                            <li><a href="">Any day, any time</a></li>
                            <li><a href="">Open the door to the mysterious</a></li>
                            <li><a href="">Learn the cool facts</a></li>
                            <li><a href="">Go all the way from Catalonia Square</a></li>
                            <li><a href="">Any day, any time</a></li>
                            <li><a href="">Open the door to the mysterious</a></li>
                            <li><a href="">Learn the cool facts</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
        <!--/Menu-->
    </section>
@endsection

@section('bottom-scripts')@endsection