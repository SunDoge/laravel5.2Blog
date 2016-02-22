<div class="form-group">
   {!! Form::label('title','Title:') !!}
   {!! Form::text('title',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
   {!! Form::label('content','Content:') !!}
   {!! Form::textarea('content',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
 {!!Form::label('published_at', 'Published at:')!!}
 {!!Form::input('date', 'published_at', date('Y-m-d'), ['class'=>'form-control'])!!}
</div>
<div class="form-group">
   {!! Form::submit('Publish',['class'=>'btn btn-success form-control']) !!}
</div>