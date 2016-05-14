@extends('admin.app')

@section('top-scripts')@stop

@section('page-title')
    Категории
@stop

@section('content')
    <div class="">
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <a href="{!! route('dashboard.asked-questions.create') !!}"
                       class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i>
                        Добавить вопрос
                    </a>
                    <a href="#" id="saveOrder" class="btn btn-sm btn-primary">Сохранить порядок</a>
                    <br/>
                    <br/>
                </div>
                    <div class="dd" id="nestable">
                        <ol class="dd-list">
                            @foreach($askedQuestions as $askedQuestion)
                                <li class="dd-item" data-id="{!! $askedQuestion->id !!}">
                                    <div class="dd-handle">
                                        {{ $askedQuestion->text }}
                                        <div class="pull-right action-buttons">
                                            <a class="blue" href="{!! route('dashboard.asked-questions.edit',$askedQuestion->id) !!}">
                                                <i class="ace-icon fa fa-pencil bigger-130"></i>
                                            </a>
                                            <a class="red" href="#">
                                                <label for="{{ $askedQuestion->id }}" class="label-delete">
                                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                    {!! Form::open(['route'=>['dashboard.asked-questions.destroy', $askedQuestion->id],'method'=>'delete' ]) !!}
                                                    {!! Form::submit('Удалить',["class" => "ace-icon fa fa-trash-o bigger-120", "id" => $askedQuestion->id, "style" => "display:none"]) !!}
                                                    {!! Form::close() !!}
                                                </label>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                </div>
            </div>
        </div>
    </div><!-- /.col -->
@stop

@section('bottom-scripts')
    <!-- page specific plugin scripts -->
    <script src="{{ url('admin/assets/js/jquery.nestable.min.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function($){
            var nestable,
                serialized,
                settings = {maxDepth:2},
                saveOrder = $('#saveOrder'),
                edit = $('.edit');

            nestable = $('.dd').nestable(settings);

            saveOrder.on('click', function(e) {
                e.preventDefault();
                serialized = nestable.nestable('serialize');

                $.ajax({
                    method:'POST',
                    url : "{!! route('dashboard.asked-questions.order') !!}",
                    data: { _token: "{!! csrf_token() !!}", serialized: serialized }

                }).done(function (data) {
                    alert("Сохранено!");
                })
            })

            $('.dd-handle a').on('mousedown', function(e){
                e.stopPropagation();
            });

            $('[data-rel="tooltip"]').tooltip();

        });
    </script>

@stop
