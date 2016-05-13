<div class="col-xs-8">
     <div class="form-group">
        {!! Form::label('title', "Название бренда") !!}
        {!! Form::text('title', $value = null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="col-xs-4">
    <div class="form-group">
        <label for="thumbnail">Изображение для превью товара</label>
        <div width="50px">
            @if($brand->thumbnail)
                <img src="{!! asset($brand->thumbnail) !!}" alt=""/>
            @endif
        </div>
        <br>
        {!! Form::hidden('thumbnail',$brand->thumbnail, ['id'=>'thumbnail']) !!}
        <a href="{!! route('elfinder.popup',['thumbnail']) !!}" class="popup_selector btn btn-default" data-inputid="thumbnail">Выбрать Изображение</a>
    </div>
    <div class="form-group">
        <label for="card_thumbnail">Изображение для карточки товара</label>
        <div width="50px">
            @if($brand->card_thumbnail)
                <img src="{!! asset($brand->card_thumbnail) !!}" alt=""/>
            @endif
        </div>
        <br>
        {!! Form::hidden('card_thumbnail',$brand->card_thumbnail, ['id'=>'card_thumbnail']) !!}
        <a href="{!! route('elfinder.popup',['card_thumbnail']) !!}" class="popup_selector btn btn-default" data-inputid="card_thumbnail">Выбрать Изображение</a>
    </div>
</div>
