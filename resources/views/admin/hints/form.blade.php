@inject('productsProvider', 'App\ViewDataProviders\ProductsDataProvider')
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

<div class="col-xs-10">
    <div class="form-group">
        {!! Form::label('info','Текст подсказки') !!}
        {!! Form::textarea('info',$value = null, ['class'=>'form-control tiny', "rows"=>"3"]) !!}
    </div>
</div>

<div class="col-xs-2">
     <div class="form-group">
		{!! Form::label('published', 'Публикация?') !!}
		{!! Form::select('published', ['Нет', 'Да'], $selected = null, ['class' => 'form-control']) !!}
	</div>
    <div class="form-group">
        {!! Form::label('order','Порядок') !!}
        {!! Form::input("number", 'order',$value = "1", ['class'=>'form-control', "min"=>"0"]) !!}
    </div>
</div>