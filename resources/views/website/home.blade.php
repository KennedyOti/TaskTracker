@extends('layouts.app')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center min-vh-100 landing-bg">
    <div class="text-center">
        <h1 class="display-4 fw-bold mb-4">Welcome to TaskTracker</h1>
        
        <a href="{{ route('login') }}" class="btn btn-custom btn-lg">Get Started</a>
    </div>
</div>
@endsection