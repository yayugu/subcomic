@extends('_layout')
@section('content')
    <h1>Favorites</h1>
    {{ $pagination->render() }}
    <div>
        @include('comic._list')
    </div>
    {{ $pagination->render() }}
@stop
