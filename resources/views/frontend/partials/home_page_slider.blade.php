@if(isset($sliders))
    <section class="banners">
            @foreach($sliders as $key=>$slider)
            <div class="box">
                <a class="banner-img"
                   href="{{ $slider->link ? : '#'}}">
                    <img style="max-width: 100%;"
                         src="{{ url($slider->thumbnail) }}" alt="{{ $slider->alt }}" />
                </a>
                <div class="container banner-main-text wow animated fadeInUp" data-wow-delay="0.5s">
                    {!!$slider->caption!!}
                    @if($slider->title)
                        <a class="banner-link right-align wow animated fadeInUp"
                           data-wow-delay="0.9s"
                           href="{{ $slider->link ? : '#'}}">
                            {{ $slider->title }}
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
    </section>
@endif