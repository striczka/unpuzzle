@inject('productsProvider', 'App\ViewDataProviders\ProductsDataProvider')
@section('tiny')
        <!-- Add TinyMce support -->
<script src="{!! url('packages/tinymce/tinymce.min.js') !!}"></script>
<script type="text/javascript">
    function enableTiny(e){
        tinymce.init({
            selector: e,
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
                ed.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    }
    enableTiny("textarea.tiny");
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

<div class="tabbable tabs-left">
    <ul class="nav nav-tabs" id="myTab3">
        <li class="active">
            <a data-toggle="tab" href="#main">
                <i class="ace-icon fa fa-desktop"></i>
                Основные
            </a>
        </li>
        <li class="">
            <a data-toggle="tab" href="#hints">
                <i class="ace-icon fa fa-cogs"></i>
                Подсказки
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <!-- Main options -->
        <div id="main" class="tab-pane active">
            <div class="col-md-9">
                <div class="form-group">
                    {!! Form::label('question','Вопрос') !!}
                    {!! Form::textarea('question',$value = null, ['class'=>'form-control tiny', "rows"=>"3"]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('answer','Ответ') !!}
                    {!! Form::textarea('answer',$value = null, ['class'=>'form-control', "rows"=>"3"]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('published', 'Вопрос активен?') !!}
                    {!! Form::select('published', ['Нет', 'Да'], $selected = null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('order','Порядок') !!}
                    {!! Form::input("number", 'order',$value = null, ['class'=>'form-control', "min"=>"0"]) !!}
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    {!! Form::label('info','Информация при правильном ответе') !!}
                    {!! Form::textarea('info',$value = null, ['class'=>'form-control tiny', "rows"=>"6"]) !!}
                </div>
            </div>

            <div id="questionWrap">
                <div class="col-xs-12">
                    <input type="hidden" v-model="selectedProductsIds" name="selectedProductsIds"/>
                    <input type="hidden" v-model="token" value="{{ csrf_token() }}"/>
                    <input type="hidden" v-model="questionId", value="{{ isset($question) ? $question->id : null }}"/>

                    <nav v-if="relOptions.selected.length > 0">
                        <ul class="pager">
                            <li class="previous @{{ pagination.currentPage == 1 ? 'disabled' : '' }}">
                                <a href="#" v-on="click: _prevPage($event)"><span aria-hidden="true">&larr;</span> Предыдущая</a>
                            </li>
                            <li>
                                @{{ pagination.currentPage }} / @{{ pagination.lastPage  }}
                            </li>
                            <li class="next @{{ pagination.currentPage ==  pagination.lastPage ? 'disabled' : '' }}" >
                                <a href="#" v-on="click: _nextPage($event)">Следующая <span aria-hidden="true">&rarr;</span></a>
                            </li>
                        </ul>
                    </nav>

                    <hr/>
                    <h4>Квест, к которому относится вопрос</h4>
                    <div class="well clearfix">
                        <div class="col-md-4">
                            {{--<span>Показывать по</span>--}}
                            {!! Form::select('_paginate', [
                                        20 => 'Показывать по 20 квестов',
                                        50 => 'По 50 квестов',
                                        100 => 'По 100 квестов'
                                      ], $selected = null,
                             ['class' => 'form-control', 'v-model' => 'relOptions.paginate', 'v-on' => 'change: getProducts()']) !!}
                        </div>
                        <div class="col-md-4 pull-right">
                            {!! Form::text('search', $value = null,
                             ['class' => 'form-control','placeholder' => 'Поиск', 'v-model' => 'relOptions.search', 'v-on' => 'input: getProducts()']) !!}
                        </div>
                    </div>
                    <table class="table table-hover pr-table">
                        <tr>
                            <th class="mini-thumb center">Миниатюра</th>
                            <th>Название квеста</th>
                            <th>Артикул</th>
                            <th>Цена</th>
                            <th class="options">Добавить</th>
                        </tr>
                        <tr v-repeat="relProduct: productsList.products">
                            <td class="center">
                                <img v-attr="src: relProduct.thumbnail[0].path " v-if="!!relProduct.thumbnail[0]" class="mini-thumb"/>
                                <img src="/frontend/images/default.png" v-if="!relProduct.thumbnail[0]" class="mini-thumb"/>
                            </td>
                            <td>
                                @{{ relProduct.title }} <br/>
                                <small style="color: #808080">(@{{ relProduct.category.title }})</small>
                            </td>
                            <td> @{{ relProduct.article }}</td>
                            <td> @{{ relProduct.price }}</td>
                            <td class="options">
                                <input type="radio" name="product_id" value = "@{{ relProduct.id }}"
                                       checked="@{{relProduct.id==questionParentId}}" />
                            </td>
                        </tr>
                    </table>
                    <hr/>
                    <p v-if="productsList.products.length == 0">
                        <b>Список квестов по текущему запросу пуст</b>
                    </p>
                    <nav v-if="productsList.products.length > 0">
                        <ul class="pager">
                            <li class="previous @{{ productsList.pagination.currentPage == 1 ? 'disabled' : '' }}">
                                <a href="#" v-on="click: prevPage($event, productsList.products)"><span aria-hidden="true">&larr;</span> Предыдущая</a>
                            </li>
                            <li>
                                @{{ productsList.pagination.currentPage }} / @{{ productsList.pagination.lastPage  }}
                            </li>
                            <li class="next @{{ productsList.pagination.currentPage ==  productsList.pagination.lastPage ? 'disabled' : '' }}" >
                                <a href="#" v-on="click: nextPage($event, productsList.products)">Следующая <span aria-hidden="true">&rarr;</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div id="hints" class="tab-pane">
            <div class="col-xs-12">
                <input type="hidden" id="questionId" value="{{ isset($question) ? $question->id : '' }}">
                <input type="hidden" value="{{ csrf_token() }}" v-model="token"/>
                <h4>Добавить подсказку</h4>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Подсказка</th>
                        <th>Порядок</th>
                        <th>Опции</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{!! Form::textarea("hintText", $value = null, ["placeholder" =>"Введите текст подсказки",
                        "class"=>"tiny"])
                            !!}
                        </td>
                        <td>{!! Form::input("number","hintOrder", $value = null,
                        ["placeholder" =>"1"]) !!}
                        </td>
                        <td>
                            <p class="btn btn-success" v-on="click: createHint">
                                Сохранить
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h4>Подсказки</h4>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Подсказка</th>
                        <th>Порядок</th>
                        <th>Опции</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-repeat="hint: hints | orderBy 'order'">
                        <td class="col-xs-8">
                            <textarea data-id="@{{hint.id}}"
                                name="hintInfoNew"
                                class="col-xs-12"
                                placeholder="Введите текст подсказки">
                                @{{ hint.info }}
                            </textarea>
                            <div class="col-xs-12 text-left" style="padding:7px 0 0">
                                <button id="tinyButton-@{{hint.id}}"
                                        type="button" class="left action trash btn btn-primary"
                                        v-on="click: enableTiny(hint, $event)">
                                    <i class="fa fa-edit"></i> Включить редактор
                                </button>
                            </div>
                        </td>
                        <td width="50px">
                            <input data-id="@{{hint.id}}"
                                   type="number"
                                   name="hintOrderNew"
                                   value="@{{hint.order}}" min="0" class="span12 order" />
                        </td>
                        <td>
                            <p class="left action btn btn-info" style="font-size:14px;"
                               v-on="click: changeHint(hint, $event)">
                                <i class="fa fa-edit"></i>
                            </p>
                            <button type="button" class="left action trash btn btn-danger"
                                    v-on="click: deleteHint(hint, $event)">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{--tabs end--}}

            <!-- remove status draft from products -->
    <input type="hidden" name="draft" value="0" form="form-data"/>
</div>



<script src="/admin/assets/js/uncompressed/date-time/moment.js"></script>
<script src="/admin/assets/js/uncompressed/date-time/daterangepicker.js"></script>
<script src="/admin/assets/js/uncompressed/date-time/bootstrap-datepicker.js"></script>


{{--@section('top-scripts')--}}
    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{!! asset('admin/assets/css/jquery-ui.custom.min.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/chosen.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/datepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/bootstrap-timepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/daterangepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/bootstrap-datetimepicker.css') !!}" />
    <link rel="stylesheet" href="{!! asset('admin/assets/css/colorpicker.css') !!}" />




{{--@stop--}}

@section('bottom-scripts')

    <script src="/admin/assets/js/chosen.jquery.min.js"></script>
    <script>

        new Vue({

            el: "#questionWrap",
            ready:function(){
                var vue = this;
                setTimeout(function(){
                    vue.getProducts();
                }, 1000)

            },

            data: {
                questionParentId: "{{ isset($question) ? $question->product->id : null }}",
                questionId: null,
                token: null,
                productsList:{
                    products:{},
                    pagination:{
                        currentPage: {},
                        lastPage: {},
                        pageToGet: 1
                    }
                },
                relOptions:{
                    category:0,
                    paginate: 20,
                    search: null,
                    selected: []
                },
                options: {
                    category:0,
                    paginate: 20,
                    search: null,
                    selected: []
                },

                pagination:{
                    currentPage: {},
                    lastPage: {},
                    pageToGet: 1
                },

                selectedProductsIds: []
            },

            methods: {
                getProducts: function(){
                    var vue = this;
//                    console.log(this.selectedProductsIds);
                    $.ajax({
                        dataType: "json",
                        method: "POST",
                        url: '/dashboard/product-actions/getProductsForSale',
                        cache: false,
                        data: {
                            paginate: vue.relOptions.paginate,
                            search: vue.relOptions.search,
                            selected: vue.selectedProductsIds,
                            page: vue.productsList.pagination.pageToGet,
                            _token: vue.token
                        },
                        success: function (response) {
                            vue.productsList.products = response.data;
                            vue.productsList.pagination.currentPage = response.current_page;
                            vue.productsList.pagination.lastPage = response.last_page;
                            if(vue.productsList.pagination.lastPage < vue.productsList.pagination.pageToGet) {
                                vue.productsList.pagination.pageToGet = vue.productsList.pagination.lastPage;
                                vue.getProducts()
                            }
                        }
                    });
                },

                nextPage: function(event){
                    event.preventDefault();
                    if(this.productsList.pagination.currentPage != this.productsList.pagination.lastPage){
                        this.productsList.pagination.pageToGet = this.productsList.pagination.currentPage + 1;
                        this.getProducts();
                    }
                },
                prevPage: function(event){
                    event.preventDefault();
                    if(this.productsList.pagination.currentPage != 1) {
                        this.productsList.pagination.pageToGet = this.productsList.pagination.currentPage - 1;
                        this.getProducts();
                    }
                },

                _nextPage: function(event){
                    event.preventDefault();
                    if(this.pagination.currentPage != this.pagination.lastPage){
                        this.pagination.pageToGet = this.pagination.currentPage + 1;
                        this.getRelatedProducts();
                    }
                },

                _prevPage: function(event){
                    event.preventDefault();
                    if(this.pagination.currentPage != 1) {
                        this.pagination.pageToGet = this.pagination.currentPage - 1;
                        this.getRelatedProducts();
                    }
                }

            }

        });

        var hint = new Vue({
            el: "#hints",
            ready: function () {
                this.questionId = $("#questionId").val();
                this.getHints();
            },

            data: {
                hints: [],
                token: null
            },

            methods: {
                enableTiny: function (hint, event) {
                    $("#tinyButton-" + hint.id).remove();
                    event.preventDefault();
                    enableTiny("textarea[data-id='" + hint.id + "'][name='hintInfoNew']");
                },
                getHints: function () {
                    var vue = this;
                    $.ajax("/dashboard/get-hints/" + vue.questionId).done(function (hints) {
                        vue.hints = hints;
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log("error");
                        console.dir(arguments);
                    });
                },
                createHint: function () {
                    var info = $("[name='hintText']").val(),
                        order = $("[name='hintOrder']").val();
                    if(info!=0){
                        var data = new FormData();
                        var that = this;
                        data.append('info', info);
                        data.append('order', order);
                        data.append('_token', that.token);
                        $.ajax({
                            url: '/dashboard/create-hint/' + that.questionId,
                            type: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            dataType: "JSON"
                        }).done(function (image) {
                            that.getHints();
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            console.log("error");
                            console.dir(arguments);
                        });
                    }
                    else{
                        alert("Введите текст подсказки");
                    }
                },
                deleteHint: function (hint, event) {
                    event.preventDefault();
                    var vue = this;
                    $.ajax("/dashboard/delete-hint/" + hint.id).done(function (data) {
                        if (!!data) {
                            vue.hints.splice(vue.hints.indexOf(hint), 1);
                        }
                    })
                },
                changeHint: function (hint, event) {
                    event.preventDefault();
                    var info = $("[name='hintInfoNew'][data-id='" + hint.id + "']").val(),
                        order = $("[name='hintOrderNew'][data-id='" + hint.id + "']").val();
                    var data = new FormData();
                    var that = this;
                    data.append('info', info);
                    data.append('order', order);
                    data.append('_token', that.token);
                    $.ajax({
                        url: '/dashboard/change-hint/' + hint.id,
                        type: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        dataType: "JSON"
                    }).done(function () {
                        that.getHints();
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log("error");
                        console.dir(arguments);
                    });
                }
            }
        });
    </script>



@endsection