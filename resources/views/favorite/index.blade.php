@extends('_layout')
@section('content')
    <h1>Favorites</h1>
    <div>
        @include('comic._list')
    </div>
    {{ $pagination->render() }}
@stop
