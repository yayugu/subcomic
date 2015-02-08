@extends('_layout')
@section('content')
    <a href="{{$comic->getUrlToShow()}}">{{{$comic->path}}}</a>
    <a href="{{$comic->getRawUrl()}}">download</a>
    <br><br>

    @foreach ($comic->tags as $tag)
        {{link_to_action('tagSearch', $tag->name, ['tag' => $tag->name])}}<br>
    @endforeach
@stop
