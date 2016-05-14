@extends('frontend.layout')
@inject('quests', '\App\ViewDataProviders\ProductsDataProvider')

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
                    <li><a href="/">Главная</a></li>
                    <li><a href="/news">Новости</a></li>
                    <li class="active">{{ $article->title }}</li>
                </ol>
            </div>
        </div>
    </section>

   <section class="content">
        <h3 class="content-h3 textInherit"><span class="red-text ">{{ $article->title }}</span></h3>
        <!--Simple Menu-->
        <div class="container">
            <div class="row single-post-row">
                <div class="col s12 l9 text-page no-padding">
                    <div class="col s12 img-post no-padding">
                        <img src="{{ url($article->thumbnail) }}" class="responsive-img" alt="{{ $article->title }}">
                    </div>
                    <div class="col single-post s12 fadeIn animated wow relative" data-wow-delay="0.3s">
                        <div class="col s12 post-text">
                            {!! $article->content !!}
                        </div>
                        <div class="col date s12 no-padding">
                            <ul class="share">
                                <li>SHARE:</li>
                                <li><a href="http://vkontakte.ru/share.php?news/{{ $article->id }}/{{ $article->slug }}"></a></li>
                                <li><a href="http://www.facebook.com/sharer.php?news/{{ $article->id }}/{{ $article->slug }}"></a></li>
                                <li><a href="http://vkontakte.ru/share.php?news/{{ $article->id }}/{{ $article->slug }}"></a></li>
                            </ul>
                            {{--<span>{{ date('m',strtotime($article->published_at)) }} </span><span> {{ date('d \'y', strtotime($article->published_at)) }} </span>--}}
                            <span>{{ date('d\.m\.y', strtotime($article->published_at)) }}</span>
                        </div>
                    </div>
                </div>
                <aside class="aside-article col s12 l3 no-padding">
                    @if(count($quests->getQuestsForMenu())>0)
                    <div class="topAside">
                        <span class="span-h3">OUR QUESTS</span>
                        <ul>
                            @foreach($quests->getQuestsForMenu() as $quest)
                            <li><a href="/our-quests/{{$quest->link}}">{{$quest->title}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(count($quests->getArticlesForMenu())>0)
                    <div class="bottomAside">
                        <span class="span-h3">BLOG</span>
                        <ul>
                            @foreach($quests->getArticlesForMenu() as $quest)
                            <li><a href="">Go all the way from Catalonia Square</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
        <!--/Menu-->
    </section>
@endsection

@section('bottom-scripts')@endsection