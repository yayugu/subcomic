@extends('_layout')
@section('content')
    <form action="{{action('comicSearch')}}" method="get">
        <input type="text" name="q">
        <input type="submit">
    </form>
    <div>
        @include('comic._list')
    </div>
    {{ $comics->links(); }}
@stop
