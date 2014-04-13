@extends('_layout')
@section('content')
    {{link_to_action('comicShow', $comic->path, ['id' => $comic->id])}}<br><br>

    @foreach ($comic->tags as $tag)
        {{link_to_action('tagSearch', $tag->name, ['tag' => $tag->name])}}<br>
    @endforeach
@stop