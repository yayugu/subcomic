@extends('_layout')
@section('content')
    <a href="{{$comic->getUrlToShow()}}">{!! $comic->path !!}</a>
    <a href="{!! $comic->getRawUrl() !!}">download</a>
    <br><br>

    @foreach ($comic->tags as $tag)
        <a href="{{action("ComicController@tagSearch", ['tag' => $tag->name])}}">{{$tag->name}}</a><br>
    @endforeach
@stop
