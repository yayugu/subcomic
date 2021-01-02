@extends('_layout')
@section('content')
<h1>Add User</h1>
<form action="{{action('UserController@store')}}" method="post" role="form">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="form-group">
        <label>User name</label>
        <input type="text" class="form-control" name="name">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password">
    </div>
    <div class="form-group">
        <label>Password (confirm)</label>
        <input type="password" class="form-control" name="password-confirm">
    </div>
    <button type="submit" class="btn btn-primary">Add</button>
</form>
@stop
