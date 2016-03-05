@extends('common.template')

@section('content')
    <h1>Hello, {{ Auth::user()->username }}!</h1>

    @if(!\App\Facades\SphinxNode::ping())
        <div class="alert alert-danger">
            <strong>Important!</strong> The Sphinx Node is currently unreachable!<br>
            Realms cannot be updated and are likely offline.
        </div>
    @endif
@endsection
