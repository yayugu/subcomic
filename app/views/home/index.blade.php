@extends('_layout')
@section('content')
    <form action="{{action('comicSearch')}}" method="get">
        <input type="text" name="q">
        <button type="submit">検索</button>
    </form>
    <div>
        @include('comic._list')
    </div>
    {{ $comics->links(); }}
@stop
