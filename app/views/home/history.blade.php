@extends('_layout')
@section('content')
    <h1>History</h1>
    <div>
        @include('comic._list')
    </div>
    {{ $pagination->links(); }}
@stop