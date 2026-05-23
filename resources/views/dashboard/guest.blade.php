@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-speedometer2"></i> Welcome, {{ auth()->user()->first_name }}!</h4>
    <span class="text-muted">{{ now()->format('l, F d Y') }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat p-3" style="border-color: #0d6efd;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Total Requests</div>
                    <div class="fs-3 fw-bold">{{ $totalRequests }}</div>
                </div>
                <i class="bi bi-bell fs-1 text-primary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3" style="border-color: #ffc107;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Pending</div>
                    <div class="fs-3 fw-bold">{{ $pendingRequests }}</div>
                </div>
                <i class="bi bi-hourglass fs-1 text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3" style="border-color: #198754;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Completed</div>
                    <div class="fs-3 fw-bold">{{ $completedRequests }}</div>
                </div>
                <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-bell"></i> My Recent Requests</h6>
        <a href="{{ route('requests.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus"></i> New Request
        </a>
    </div>
    @forelse($myRequests as $req)
    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
        <div>
            <div class="fw-semibold">{{ $req->request_type }}</div>
            <small class="text-muted">Room {{ $req->room->room_number }} — {{ $req->created_at->diffForHumans() }}</small>
        </div>
        <span class="badge badge-{{ $req->status === 'pending' ? 'pending' : ($req->status === 'completed' ? 'completed' : 'progress') }}">
            {{ ucfirst($req->status) }}
        </span>
    </div>
    @empty
    <p class="text-muted small">You have no requests yet.
        <a href="{{ route('requests.create') }}">Submit one now!</a>
    </p>
    @endforelse
</div>
@endsection