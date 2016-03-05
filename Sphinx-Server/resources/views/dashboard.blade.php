@extends('common.template')

@section('head')
    <link rel="stylesheet" href="{{ url('/css/dashboard.css') }}">

    <script src="{{ url('/js/dashboard.js') }}"></script>
@endsection

@section('content')
    @if(!$stats['nodeOnline'])
        <div class="alert alert-danger">
            <strong>Important!</strong> The Sphinx Node is currently unreachable!<br>
            Realms cannot be updated and are likely offline.
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <h1>Hello, {{ Auth::user()->username }}!</h1>
        </div>
        <div class="col-md-4">
            <table class="statistics-table table table-hover">
                <tr>
                    <td class="stat-name">Node Online?</td>
                    <td class="stat-value" id="stat-nodeOnline">{!! $stats['nodeOnline'] ? '<span style="color:green;">Online</span>' : '<span style="color:red;">Offline</span>' !!}</td>
                </tr>
                <tr>
                    <td class="stat-name">Realm Count</td>
                    <td class="stat-value" id="stat-realmCount">{{ $stats['realmCount'] }}</td>
                </tr>
                <tr>
                    <td class="stat-name">Servers Running</td>
                    <td class="stat-value" id="stat-serversRunning">{{ $stats['serversRunning'] }}</td>
                </tr>
                <tr>
                    <td class="stat-name">Online Players</td>
                    <td class="stat-value" id="stat-onlinePlayers">{{ $stats['onlinePlayers'] }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
