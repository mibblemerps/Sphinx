<?php $active_tab = 'users'; ?>

@extends('common.template')

@section('head')
    <link rel="stylesheet" href="{{ url('/css/users.css') }}">

    <script src="{{ url('/js/users.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h2>User Management</h2>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{!! ($user->email === null) ? '<span class="text-muted">Unknown</span>' : $user->email !!}</td>
                        <td>
                            <input type="button" value="Remove" class="user-remove-btn btn-danger" data-userid="{{ $user->id }}" data-username="{{ $user->username }}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h2>Add User</h2>
            <div class="form-create-user">
                <label for="name">Username</label>
                <input type="text" id="user-create-name" class="form-control" name="name" maxlength="32">

                <label for="name">Email</label>
                <input type="text" id="user-create-email" class="form-control" name="email" maxlength="96">

                <label for="name">Password</label>
                <input type="password" id="user-create-password" class="form-control" name="password" maxlength="96">

                <input type="button" id="user-create-submit" class="btn btn-lg btn-success" value="Create">
            </div>
        </div>
    </div>
@endsection
