@extends('app')
@section('content')
<h1>Write A New Article</h1>
{!! Form::open(['url'=>'articles']) !!}
   @include('articles.form')
{!! Form::close() !!}
@include('errors.list')
@endsection