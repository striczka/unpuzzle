@extends('frontend.layout')

@section('seo')
    @if(isset($category->meta_title) && !empty($category->meta_title))
        <title>{{ $category->meta_title }}</title>
    @else
        <title>{{ isset($category->title) ? $category->title : '' }}</title>
    @endif

    <meta name="description" content="{{ isset($category->meta_description) ?  $category->meta_description : ''}}"/>
    <meta name="keywords" content="{{ isset($category->meta_keywords) ?  $category->meta_keywords : ''}}"/>
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
                {{--<li><a href="index.html">Родитель</a></li>--}}
                <li class="active">{{ isset($category->title) ? $category->title : '' }}</li>
            </ol>
        </div>
    </div>
</section>

<section class="content category-content">
    <!--Simple Menu-->
    <div class="ourQuestsText">
        @if($category->header)
            <h3>{{ $category->header }}</h3>
        @endif

        @if($category->description)
            {!! $category->description !!}
        @endif
   </div>
    <h3 class="content-h3"><span class="red-text">{{ isset($category->title) ? $category->title : '' }}</span></h3>
    <div class="container">
        <div class="row">

            {{-- @include('frontend.partials.sidebar') --}}



            <div class="col s12 catalog no-padding">
                {{-- <h3>{{ isset($category->title) ? $category->title : '' }}</h3> --}}

               {{-- @include('frontend.partials.products.controls') --}}

                <div id="products">
                    @include('frontend.partials.products.product_array')
                </div>

                <div class="col s12 que-link">
                    <a href="/" id="load-more-quests">more quests</a>
                </div>
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
        });
        var page = 2;
        $(document).on('click', '#load-more-quests', function (e) {
            getPosts();
            e.preventDefault();
        });

        function getPosts() {
            $.ajax({
                url : '/our-quests/?page=' + page,
                dataType: 'json'
            }).done(function (data) {
                if(data){
                    page++;
                    $("#products").append(data);
                } else {
                    $("#load-more-quests").text("No more quests");
                }

            }).fail(function () {
                alert('Нельзя загрузить контент.');
            });
        }
    </script>
@endsection