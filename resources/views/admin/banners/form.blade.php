@section('tiny')
        <!-- Add TinyMce support -->
<script src="{!! url('packages/tinymce/tinymce.min.js') !!}"></script>
<script type="text/javascript">
    tinymce.init({
        selector:"textarea.tiny",
        font_formats: "AAvanteBs=AAvanteBs;Aavanteheavy=aavanteheavy;AvenirPro Bold=AvenirNextLTProBold;Fregat Bold=FregatBold;Muli Italic=MuliItalic;" +
        "Muli Light=MuliLight;Muli Light Italic=MuliLightItalic;"+
        "Muli Regular=MuliRegular;MyriadPro=myriadpro;MyriadPro Bold=MyriadProBold",
        fontsize_formats: "8px 10px 12px 14px 15px 18px 20px 24px 27px 36px 49px",
        relative_urls: false,
        plugins : 'image,table,colorpicker,textcolor,code,fullscreen,link,media',
        toolbar: [
            "undo redo | bold italic | fontselect |  fontsizeselect | alignleft aligncenter alignright | outdent indent | bullist numlist | indent | link | image fullscreen | forecolor backcolor"
        ],
        tools: "inserttable",

        file_browser_callback : elFinderBrowser,
        setup : function(ed)
        {
            ed.on('init', function()
            {
                //this.execCommand("fontName", false, "tahoma");
                this.execCommand("fontSize", false, "14px");
            });
        }
    });
    function elFinderBrowser (field_name, url, type, win) {
        tinymce.activeEditor.windowManager.open({
            file: '{!! url("/dashboard/elfinder/tinymce4") !!}',
            customData: {
                _token: '{{ csrf_token() }}'
            },
            title: 'elFinder 2.0',
            width: 900,
            height: 450,
            resizable: 'yes',
            encoding: 'CP1251'
        }, {
            setUrl: function (url) {
                win.document.getElementById(field_name).value = url;
            }
        });
        return false;
    }
</script>
<!-- /Add TinyMce support -->
@stop
<div class="col-lg-8">
    <div class="form-group">
        {!! Form::label('title','Заголовок') !!}
        {!! Form::text('title',$value = null,['placeholder'=>'Название','class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('caption','Описание') !!}
        {!! Form::textarea('caption',$value = null,['placeholder'=>'Описание','rows'=>'12', 'class'=>'form-control tiny']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('link','Ссылка') !!}
        {!! Form::text('link',$value = null,['placeholder'=>'Ссылка','class'=>'form-control']) !!}
    </div>
    <div class="form-group">
        <a href="{!! route('elfinder.popup',['thumbnail']) !!}" class="popup_selector btn btn-default" data-inputid="thumbnail">Выбрать Изображение</a>
        <br/>
        <br/>
        <div class="banner-box">
            @if(is_file($banner->thumbnail))
                <img src="{!! asset($banner->thumbnail) !!}" alt=""/>
            @endif
        </div>
        {!! Form::hidden('thumbnail',$banner->thumbnail, ['id'=>'thumbnail']) !!}
    </div>
</div>
<div class="col-lg-4">
    <div class="form-group">
        {!! Form::label('alt','Alt мета-тег') !!}
        {!! Form::text('alt',$value = null,['placeholder'=>'Alt мета-тег','class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('order','Сортировка') !!}
        {!! Form::text('order',$value = null,['placeholder'=>'Сортировка от большего к меньшему','class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('area', 'Тип секции') !!}
        {!! Form::select('area', ["map-block"=>"Карта", "colour-block"=>'Цветные блоки', "advantages"=>"Преимущества",
        "quests-block"=>'Квесты', "review-block"=>'Отзывы',"mailing-block"=>'Обратная связь'], $selected = null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('','Показывать на сайте?')!!}<br/>
        <div class="btn-group" data-toggle="buttons">
            <label class="btn btn-primary {{ $banner->show == 1 ? 'active' : null }}">
                {!!Form::radio('show',$value = 1, $banner->show == 1 ? true : false)!!}Да</label>
            <label class="btn btn-primary {{ $banner->show == 0 ? 'active' : null }}">
                {!!Form::radio('show',$value = 0, $banner->show == 0 ? true : false)!!}Нет</label>
        </div>
    </div>

</div>

@section('bottom-scripts')
    <script>
        $(function() {
            var thumbnail;
            $(document).mousemove(function(){
                thumbnail = $("#thumbnail").val()
                if(thumbnail.length > 0) {
                    var img = $('<img />', {
                        id: '',
                        src: '/'+thumbnail,
                        alt: ''
                    });
                    $('div.banner-box').html(img);
                }
            });
        });
    </script>
@stop