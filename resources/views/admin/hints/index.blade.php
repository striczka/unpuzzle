@extends('admin.app')

@section('top-scripts')@stop

@section('page-title')
    Акции
@stop

@section('content')
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <div>
                    <a href="{{ route("dashboard.hints.create") }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i>
                        Добавить подсказку
                    </a>
                    <br/>
                    <br/>
                </div>
                <div>
                    <table id="sample-table-2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="center">Публикация</th>
                            <th class="center">Квест</th>
                            <th class="mini-thumb center">Миниатюра</th>
                            <th class="center">Порядок</th>
                            <th colspan="2" class="options">Опции</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hints as $hint)
                            <tr>
                                <td class="center">
                                    @if($hint->published)
                                        <i class="fa fa-check green"></i>
                                    @else
                                        <i class="fa fa-minus"></i>
                                    @endif
                                </td>

                                <td class="center">{{ $hint->question->product->title }}</td>

                                <td class="center">
                                    <img src="{{ $hint->question->product->thumbnail->first()->path }}" class="mini-thumb"/>
                                </td>
                                <td class="center">{{ $hint->order }}</td>
                                <!-- Options -->
                                <td class="options">
                                    <a class="green" href="{!! route('dashboard.hints.edit', $hint->id) !!}">
                                        <i class="ace-icon fa fa-pencil bigger-130"></i>
                                    </a>
                                </td>
                                <td class="options">
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        {!! Form::open(['route'=>['dashboard.hints.destroy', $hint->id],'method'=>'delete' ]) !!}
                                        <label class="red" style="display: inline-block; cursor: pointer;">
                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                            {!! Form::submit('Удалить',
                                            ["class" => "ace-icon fa fa-trash-o bigger-120", "id" => "test", "style" => "display:none"]) !!}
                                            {!! Form::close() !!}
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <!-- /End Options -->
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- /.col -->
@stop

@section('bottom-scripts')@stop
