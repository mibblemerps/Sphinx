@extends('common.template')

@section('head')
    <link rel="stylesheet" href="{{ url('/css/login.css') }}">
@endsection

@section('content')
    <form action="" method="post" class="form-login">
        <h2>Please sign in...</h2>
        <input type="text" class="form-control" id="username" placeholder="Username" required autofocus>
        <input type="password" class="form-control" id="password" placeholder="Password" required>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Sign in">
    </form>
@endsection
