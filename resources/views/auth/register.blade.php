@extends('app')
@section('content')
<div class="col-md-6 col-md-offset-3">
	{!!Form::open(['url'=>'auth/register'])!!}
	<div class="form-group">
		{!!Form::label('name', 'Name:')!!}
		{!!Form::text('name', null, ['class'=>'form-control'])!!}
	</div>
	<div class="form-group">
		{!!Form::label('email', 'Email:')!!}
		{!!Form::email('email', null, ['class'=>'form-control'])!!}
	</div>
	<div class="form-group">
		{!!Form::label('password', 'Password:')!!}
		{!!Form::password('password', ['class'=>'form-control'])!!}
	</div>
	<div class="form-group">
		{!!Form::label('password_confirmation', 'Password confirmation:')!!}
		{!!Form::password('password_confirmation', ['class'=>'form-control'])!!}
	</div>
	{!!Form::submit('Register', ['class'=>'btn btn-primary form-control'])!!}
	{!!Form::close()!!}
</div>
@stop