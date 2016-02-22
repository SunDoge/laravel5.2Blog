@extends('app')
@section('content')
<h1>Edit Your Article</h1>
{!! Form::model($article, ['method'=>'PATCH', 'url'=>'articles/'.$article->id]) !!}
   @include('articles.form')
{!! Form::close() !!}
@include('errors.list')
@endsection