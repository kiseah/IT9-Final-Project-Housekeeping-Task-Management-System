@extends('layouts.app')
@section('title', 'Access Denied')

@section('content')
<div class="text-center py-5">
    <div class="mb-4">
        <i class="bi bi-shield-lock text-danger" style="font-size: 80px;"></i>
    </div>
    <h1 class="fw-bold text-danger">403</h1>
    <h4 class="mb-3">Access Denied</h4>
    <p class="text-muted mb-4">
        You don't have permission to access this page.
    </p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">
        <i class="bi bi-house"></i> Back to Dashboard
    </a>
</div>
@endsection