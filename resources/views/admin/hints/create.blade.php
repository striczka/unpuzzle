@extends('admin.app')

@section('top-scripts')

@stop

@section('page-title')
    Добавить подсказку
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
    <div id="categories" class="row">
        @include('admin.partials.errors')
        {!! Form::open(['route'=>'dashboard.hints.store', 'method'=>'post', 'id'=>'form-data']) !!}
            @include("admin.hints.form")
        {!! Form::close() !!}
    </div>
@stop