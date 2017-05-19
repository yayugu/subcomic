@extends('_layout')
@section('content')
    <form action="{{action('AuthController@login')}}" method="post" role="form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group">
            <label>User name</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
@stop
