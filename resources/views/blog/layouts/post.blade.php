@extends('blog.layouts.master', [
  'title' => $post->title,
  'meta_description' => $post->meta_description ?: config('blog.description'),
])

{{--@include('editor::decode')--}}
@section('styles')
    <link rel="stylesheet" href="/assets/code-prettify/skins/desert.css">
    <style>
        .linenums li {
            list-style-type: decimal;
        }
    </style>
@stop

@section('page-header')
    <header class="intro-header"
            style="background-image: url('{{ page_image($post->page_image) }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1>{{ $post->title }}</h1>
                        <h2 class="subheading">{{ $post->subtitle }}</h2>
                        <span class="meta">
              Posted on {{ $post->published_at->format('F j, Y') }}
                            @if ($post->tags->count())
                                in
                                {!! join(' ', $post->tagLinks()) !!}
                            @endif
            </span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop

@section('content')

    {{-- The Post --}}
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    {!! $post->content_html !!}
                </div>
            </div>
        </div>
    </article>

    {{-- The Pager --}}
    <div class="container">
        <ul class="pager">
            @if ($tag && $tag->reverse_direction)
                @if ($post->olderPost($tag))
                    <li class="previous">
                        <a href="{!! $post->olderPost($tag)->url($tag) !!}">
                            <i class="fa fa-long-arrow-left fa-lg"></i>
                            Previous {{ $tag->tag }} Post
                        </a>
                    </li>
                @endif
                @if ($post->newerPost($tag))
                    <li class="next">
                        <a href="{!! $post->newerPost($tag)->url($tag) !!}">
                            Next {{ $tag->tag }} Post
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                    </li>
                @endif
            @else
                <div class="row">
                    @if ($post->newerPost($tag))
                        <li class="previous">
                            <a href="{!! $post->newerPost($tag)->url($tag) !!}">
                                <i class="fa fa-long-arrow-left fa-lg"></i>
                                Next Newer {{ $tag ? $tag->tag : '' }} Post
                            </a>
                        </li>
                    @endif
                    @if ($post->olderPost($tag))
                        <li class="next">
                            <a href="{!! $post->olderPost($tag)->url($tag) !!}">
                                Next Older {{ $tag ? $tag->tag : '' }} Post
                                <i class="fa fa-long-arrow-right"></i>
                            </a>
                        </li>
                    @endif
                </div>
            @endif
        </ul>


    </div>
@stop

@section('scripts')
    @include('editor::decode')
    <script src="/assets/code-prettify/prettify.js"></script>
    <script>
        $('pre').addClass('prettyprint linenums');
        prettyPrint();
    </script>
@stop