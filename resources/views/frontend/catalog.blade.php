@extends('frontend.layout')

@section('seo')
    @if(isset($category->meta_title) && !empty($category->meta_title))
        <title>{{ $category->meta_title }}</title>
    @else
        <title>{{ isset($category->title) ? $category->title : $header }}</title>
    @endif

    <meta name="description" content="{{ isset($category->meta_description) ?  $category->meta_description : ''}}"/>
    <meta name="keywords" content="{{ isset($category->meta_keywords) ?  $category->meta_keywords : ''}}"/>
@endsection

@section('content')
                <!-- Customize it as you want -->  
                  @if(isset($category->thumbnail) && is_file(public_path($category->thumbnail)))
                <div class="category-thumb container">


                        @if($category->thumb_link)
                            <a href="{{ url($category->thumb_link) }}">
                                <img src="{{ asset($category->thumbnail) }}" alt="{{ $category->thumb_alt }}"/>
				                    @if(isset($category->thumb_desc))
                        <span>{!! $category->thumb_desc !!}</span>
                    @endif
                            </a>
                        @else
                            <img src="{{ asset($category->thumbnail) }}" alt="{{ $category->thumb_alt }}"/>
				                    @if(isset($category->thumb_desc))
                        <span>{!! $category->thumb_desc !!}</span>
                    @endif
                        @endif  
              </div>
                    @endif


<section class="breadcrumbs ourQuests">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Главная</a></li>
                {{--<li><a href="index.html">Родитель</a></li>--}}
                <li class="active">{{ isset($category->title) ? $category->title : $header }}</li>
            </ol>
        </div>
    </div>
</section>

<section class="content">
    <!--Simple Menu-->
    <div class="ourQuestsText">
        <h3>Our quests</h3>
        <p>Do you know that you remember any information better when you find and investigate it by yourself rather than listening to somebody?</p>
        <p>Forget about tour guides who overload you with information. Forget about big tour groups that you need to follow and make your back and feet ache after hours of non-stop walking! Be independent!
        Turn your trip into a real adventure!</p>
        <p>UnPuzzle Barcelona is a perfect mix of finding your way in the city while learning some history and having fun! 
            We've prepared many routes around the city - choose the theme and the neighborhood that you want to discover!</p>
    </div>    
    <h3 class="content-h3"><span class="red-text">{{ isset($category->title) ? $category->title : $header }}</span></h3>
    <div class="container">
        <div class="row">

            {{-- @include('frontend.partials.sidebar') --}}



            <div class="col s12 catalog no-padding">
                {{-- <h3>{{ isset($category->title) ? $category->title : $header }}</h3> --}}

               {{-- @include('frontend.partials.products.controls') --}}

                <div id="products">
                    @if(!Request::has('filter'))

                        {{--@foreach($products as $product)--}}
                            {{--@include('frontend.partials.products.product_template')--}}
                        {{--@endforeach--}}

                    @endif
                </div>

                <div class="col s12 que-link">
                    <a href="/">more quests</a>
                </div>
                
                {{-- @include('frontend.partials.products.controls') --}}

                @if(isset($category))
                    @if($category->header)
                        <h1>{{ $category->header }}</h1>
                    @endif
                    @if($category->description)
                        <div class="col s12 shop-info sport-girl">
                            {!! $category->description !!}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <!--/Menu-->
</section>


@endsection

@section('filter_handler')

    @include('frontend.partials.scripts.filter_handler')

@endsection

@section('bottom-scripts')

    <div id="video" class="modal">
        <div class="modal-content">
            <a href="#!" class="modal-action modal-close waves-effect btn-flat "><i class="fa fa-close"></i></a>
            <div class="video-container"></div>
        </div>
    </div>
    @include('frontend.partials.scripts.add_to_cart')
    <script>
        $("body").on('click', '.video-review', function(e){
            e.preventDefault();
            var video = $(this).siblings('._video').html();
            $('#video').find('.video-container').html(video);
        })
    </script>
@endsection