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
                    <a href="{{ route("dashboard.questions.create") }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i>
                        Добавить вопрос
                    </a>
                    <br/>
                    <br/>
                </div>
                <div>
                    <table id="sample-table-2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th class="center">Вопрос действителен</th>
                            <th class="center">Квест</th>
                            <th class="mini-thumb center">Миниатюра</th>
                            <th class="center">Порядок</th>
                            <th colspan="2" class="options">Опции</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($questions as $question)
                            <tr>
                                <td>{{ $question->question }}</td>
                                <td class="center">
                                    @if($question->published)
                                        <i class="fa fa-check green"></i>
                                    @else
                                        <i class="fa fa-minus"></i>
                                    @endif
                                </td>

                                <td class="center">{{ $question->product->title }}</td>

                                <td class="center">
                                    <img src="{{ $question->product->thumbnail->first()->path }}" class="mini-thumb"/>
                                </td>
                                <td class="center">{{ $question->order }}</td>
                                <!-- Options -->
                                <td class="options">
                                    <a class="green" href="{!! route('dashboard.questions.edit', $question->id) !!}">
                                        <i class="ace-icon fa fa-pencil bigger-130"></i>
                                    </a>
                                </td>
                                <td class="options">
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        {!! Form::open(['route'=>['dashboard.questions.destroy', $question->id],'method'=>'delete' ]) !!}
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
