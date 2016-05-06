<div class="col s12 m4 item-inner no-padding right">
    <div class="card item itemAnim{{ $product->id }}">
        <div class="item-image waves-effect waves-block waves-light">

            @if(count($product->thumbnail) && file_exists(public_path($product->thumbnail->first()->path)))
                <img class="activator" src="{{ $product->thumbnail->first()->path or null }}">
            @else
                <img class="activator" src="/frontend/images/default.png">
            @endif

            {{--<div class="item-content">--}}

                {{--@if($product->hasDiscount())--}}
                    {{--<span class="old-price">{{ $product->getPrice() }}грн</span>--}}
                    {{--<span class="new-price">{{ $product->getNewPrice() }}грн</span>--}}
                {{--@else--}}
                    {{--<span class="price">{{ $product->getPrice() }}грн</span>--}}
                {{--@endif--}}

            {{--</div>--}}

            {{--@if($product->video)--}}
                {{--<span class="feature">--}}
                    {{--<img src="/frontend/images/feature.png" />--}}
                    {{--<span class="text-feature">видео</span>--}}
                {{--</span>--}}
            {{--@endif--}}
            {{--@if($product->flash_view)--}}
                {{--<span class="feature {{ $product->video ? 'offset-feature' : ''}}">--}}
                    {{--<img src="/frontend/images/button-3d-little.png" />--}}
                    {{--<span class="text-feature">товар в 3d</span>--}}
                {{--</span>--}}
            {{--@endif--}}

            {{--@if(getAppointment($product))--}}
                {{--<div class="appointment"><img src="/frontend/images/{{ getAppointment($product) }}" /></div>--}}
            {{--@endif--}}
        </div>{{--end--}}
        <div class="item-content">
            <div class="item-price">
                @if($product->hasDiscount())
                    {{--<span class="hover-old-price">{{ $product->getPrice() }} грн</span>--}}
                    <span class="hover-new-price">{{ $product->getNewPrice() }}<span class="myriad-bold">€</span></span>
                @else
                    <span class="hover-price">{{ $product->getPrice() }}<span class="myriad-bold">€</span></span>
                @endif
                <p class="note">for a team</p>
            </div>

            <div class="item-info left-align">
                <div class="main">
                    <p class="item-title">{!! mb_substr($product->title, 0, 30, 'UTF-8') !!}</p>
                    {{--<div class="col s8 no-padding">--}}
                    {{--<p class="sku">Код: <span>{{ $product->article }}</span></p>--}}
                    {{--</div>--}}
                    {{--<div class="col s4 no-padding">--}}
                    {{--<div class="rating_1">--}}
                    {{--<input type="hidden" name="vote-id" value="5"/>--}}
                    {{--<input type="hidden" name="val" value="{{ array_sum($product->rates->lists('rate')->all()) / ($product->rates->count() ?: 1) }}">--}}
                    {{--</div>--}}
                    {{--</div>--}}

                    {{--<p class="desc">{!! $product->excerpt !!}</p> --}}
                    <p class="desc">{!! $product->pack !!}</p>
                </div>
                {{--<div class="addtocart-button center-align">--}}
                {{--<input--}}
                {{--type="submit" name="addtocart"--}}
                {{--class="addtocart-button buy"--}}
                {{--value="{{ Cart::search(['id' => $product->id]) ? 'В корзине' : 'купить' }}"--}}
                {{--title="купить">--}}
                {{--</div>--}}
                <div class="additional">
                    <p>{!! $product->pack !!}</p>
                    {{--@foreach($product->sortedValues($product->category_id) as $field)--}}
                        {{--@if($field->filter->isVisibleForCategory($product->category_id))--}}
                            {{--<div class="col s6 boldy no-padding">{{ $field->filter->title }}:</div>--}}
                            {{--<p>{{ $field->value }}</p>--}}
                        {{--@endif--}}
                    {{--@endforeach--}}
                </div>
            </div>
        </div>
        <a class="item-link" href="/{{ $product->category->slug}}/{{ $product->slug}}"></a>
        {{--<div class="card-reveal cardAnim{{ $product->id }} no-padding">--}}
		{{--<div class="linkProduct" onclick="return location.href = '/{{ $product->category->slug}}/{{ $product->slug}}'">--}}

            {{--<div class="stable" >--}}
                {{--@if(count($product->thumbnail) && file_exists(public_path($product->thumbnail->first()->path)))--}}
                    {{--<img class="hover-item" src="{{ $product->thumbnail->first()->path or null }}">--}}
                {{--@else--}}
                    {{--<img class="hover-item" src="/frontend/images/default.png">--}}
                {{--@endif--}}
            {{--</div>--}}

            {{--<input type="hidden" value="{{ $product->id }}" class="_id"/>--}}
            {{--<div class="rating_2">--}}
                {{--<input type="hidden" name="vote-id" value="5"/>--}}
                {{--<input type="hidden" name="val" value="{{ array_sum($product->rates->lists('rate')->all()) / ($product->rates->count() ?: 1) }}">--}}
            {{--</div>--}}


            {{--<span class="outlook">--}}
                {{--<a href="/{{ $product->category->slug}}/{{ $product->slug}}">--}}
                    {{--<i class="fa fa-bars"></i> посмотреть товар--}}
                {{--</a>--}}
            {{--</span>--}}

            {{--@if($product->video)--}}
                {{--<a href="#video" class="modal-trigger video-review uppercase">видеообзор</a>--}}
                {{--<span class="_video" style="display:none;">{!!  $product->video !!}</span>--}}
            {{--@endif--}}

            {{--<span class="hover-item-title">{{ $product->title }}</span>--}}
            {{--<p class="hover-sku">Код: <span>{{ $product->article }}</span></p>--}}
            {{----}}
            {{--<ul class="col s12 collapsible no-padding" data-collapsible="accordion">--}}
                {{--<li>--}}
                    {{--<div class="collapsible-header open-info">...</div>--}}
                    {{--<div class="collapsible-body"><p>Дополнительная информация о товаре. Дополнительная информация о товаре. Дополнительная информация о товаре. Дополнительная информация о товаре. Дополнительная информация о товаре. Дополнительная информация о товаре. Дополнительная информация о товаре.</p></div>--}}
                {{--</li>--}}
            {{--</ul>--}}

			{{--</div>--}}
            {{--<div class="addtocart-box-hover">--}}
                {{--<input--}}
                        {{--type="submit"--}}
                        {{--name="addtocart"--}}
                        {{--data-productId="{{ $product->id }}"--}}
                        {{--class="addtocart-button-hover buy anim"--}}
                        {{--value="{{ Cart::search(['id' => $product->id]) ? 'В корзине' : 'добавить в корзину' }}"--}}
                        {{--title="добавить в корзину">--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
    <input type="hidden" id="token" value="{{ csrf_token() }}"/>
</div>



