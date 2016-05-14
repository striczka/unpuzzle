@extends('admin.app')

@section('top-scripts')
    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{!! asset('admin/assets/css/jquery-ui.custom.min.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/chosen.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/datepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/bootstrap-timepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/daterangepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/bootstrap-datetimepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/colorpicker.css') !!}" />


    <!-- Add TinyMce support -->
    <script src="{!! url('packages/tinymce/tinymce.min.js') !!}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector:"textarea.tiny",
            font_formats: "AAvanteBs=AAvanteBs;Aavanteheavy=aavanteheavy;AvenirPro Bold=AvenirNextLTProBold;Fregat Bold=FregatBold;Muli Italic=MuliItalic;" +
            "Muli Light=MuliLight;Muli Light Italic=MuliLightItalic;"+
            "Muli Regular=MuliRegular;MyriadPro=myriadpro;MyriadPro Bold=MyriadProBold",
            fontsize_formats: "8px 10px 12px 14px 15px 18px 20px 24px 27px 36px 49px",
            plugins : 'image,table,colorpicker,textcolor,code,fullscreen,link',
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
                file: '{!! url("dashboard/elfinder/tinymce4") !!}',
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                title: 'elFinder 2.0',
                width: 900,
                height: 450,
                resizable: 'yes'
            }, {
                setUrl: function (url) {
                    win.document.getElementById(field_name).value = url;
                }
            });
            return false;
        }
    </script>
    <!-- /Add TinyMce support -->
@endsection

@section('page-title')
    Редактировать категорию
@stop

@section('page-nav')
    <div class="row h60">
        <div class="col-lg-12">
            <button form="form-data" class="btn btn-sm btn-primary" name="button" value="0" title="Сохранить">
                <i class="ace-icon fa fa-floppy-o"></i> Сохранить
            </button>

            <button form="form-data" class="btn btn-sm btn-primary" name="button" value="1" title="Сохранить и выйти">
                <i class="ace-icon fa fa-chevron-circle-up "></i> Сохранить и выйти
            </button>
        </div>
    </div>
@stop

@section('content')
    <div id="asked-questions" class="row">
        @include('admin.partials.errors')
        {!! Form::model($askedQuestion,['route'=>['dashboard.asked-questions.update',$askedQuestion->id],'method'=>'put', 'id'=>'form-data']) !!}
            @include("admin.asked-questions.form")
        {!! Form::close() !!}
    </div>
@stop
