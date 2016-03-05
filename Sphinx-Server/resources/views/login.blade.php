<?php $simple_navbar = true; ?>

@extends('common.template')

@section('head')
    <link rel="stylesheet" href="{{ url('/css/login.css') }}">
@endsection

@section('content')
    <form action="" method="post" class="form-login">
        <h2>Please sign in...</h2>

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{ csrf_field() }}
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Sign in">
    </form>
@endsection
