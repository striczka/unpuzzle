<div class="col-xs-12">
           <div class="col-lg-8">
                    <div class="form-group">
                        {!! Form::label('text','Вопрос') !!}
                        {!! Form::text('text', $value = null,
                        ['placeholder'=>'Вопрос','class'=>'form-control','v-model'=>'title']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('answer','Ответ') !!}
                        {!! Form::textarea('answer', $value = null,
                        ['placeholder'=>'Ответ','class'=>'form-control','v-model'=>'title', "rows"=>"6"]) !!}
                    </div>
            </div>
</div>