@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Dashboard</div>

        <div class="card-body">
            @include('user.avatar_form')
        </div>
    </div>
</div>
@endsection
