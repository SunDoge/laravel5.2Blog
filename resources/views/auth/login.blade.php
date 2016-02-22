@extends('app')
@section('content')
<div class="col-md-6 col-md-offset-3">
	{!!Form::open(['url'=>'auth/login'])!!}
	<div class="form-group">
		{!!Form::label('email', 'Email:')!!}
		{!!Form::email('email', null, ['class'=>'form-control'])!!}
	</div>
	<div class="form-group">
		{!!Form::label('password', 'Password:')!!}
		{!!Form::password('password', ['class'=>'form-control'])!!}
	</div>
	{!!Form::submit('Login', ['class'=>'btn btn-primary form-control'])!!}
	{!!Form::close()!!}
</div>
@stop