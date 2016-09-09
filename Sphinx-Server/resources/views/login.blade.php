<?php
$active_tab = 'login';
$simple_navbar = true;
?>

@extends('common.template')

@section('head')
    <link rel="stylesheet" href="{{ url('/css/login.css') }}">
@endsection

@section('content')
<br />
<center>
<div class="col-md-4">
<br />
<div class="panel panel-primary">
<div class="panel-heading"><center>Login</center></div>

<div class="panel-body">

    <form action="" method="post" class="form-login">
        

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {!! csrf_field() !!}
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        <input type="submit" class="btn btn-primary btn-block" value="Sign in">
    </form>
	
	</div>
	</div>
	</div>
	<div class="panel panel-primary">
	<div class="panel-body">
	<b>Welcome to the Sphinx control panel!</b> <br/>
	You can login with your credentials to the left.<br/>
	If you do not have an account, an administrator can make one for you.<br/>
	
	
	</div>
	</div>
	</center>
@endsection
