@extends('frontend.layout')

@section('seo')

    <title>{{ $page->meta_title ?: $page->title }}</title>
    <meta name="description" content="{{ $page->meta_description }}"/>
    <meta name="keywords" content="{{ $page->meta_keywords }}"/>

@endsection


@section('content')
<section class="breadcrumbs">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">{{ $page->title }}</li>
            </ol>
        </div>
    </div>
</section>

<section class="content">
    <!--Simple Menu-->
    <h3 class="staticPage"><span>{{ $page->title }}</span></h3>
    <div class="container">
        <div class="row">
            <div class="col s12 staticPage text-page no-padding">
               
                {!! $page->content !!}
            </div>
        </div>
    </div>
    <!--/Menu-->
</section>




<script type="text/javascript">
    /* перемещение на странице о нас 
    $('.tableAbout tr:nth-of-type(2) td').appendTo($('.tableAbout tr:nth-of-type(1)'));
*/
</script>
@endsection

