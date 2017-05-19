@extends('_layout')
@section('content')
    @include('comic._search_form')
    <div>
        @include('comic._list')
    </div>
    {{ $comics->render() }}
@stop
