@extends('_layout')
@section('content')
    @include('comic._search_form')
    {{ $comics->render() }}
    <div>
        @include('comic._list')
    </div>
    {{ $comics->render() }}
@stop
