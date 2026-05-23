@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-speedometer2"></i> Admin Dashboard</h4>
    <span class="text-muted">{{ now()->format('l, F d Y') }}</span>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stat p-3" style="border-color: #0d6efd;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Total Users</div>
                    <div class="fs-3 fw-bold">{{ $totalUsers }}</div>
                </div>
                <i class="bi bi-people fs-1 text-primary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3" style="border-color: #198754;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Total Rooms</div>
                    <div class="fs-3 fw-bold">{{ $totalRooms }}</div>
                </div>
                <i class="bi bi-door-closed fs-1 text-success opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3" style="border-color: #ffc107;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Pending Requests</div>
                    <div class="fs-3 fw-bold">{{ $pendingRequests }}</div>
                </div>
                <i class="bi bi-bell fs-1 text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3" style="border-color: #dc3545;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Active Tasks</div>
                    <div class="fs-3 fw-bold">{{ $activeTasks }}</div>
                </div>
                <i class="bi bi-list-check fs-1 text-danger opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- RECENT REQUESTS & TASKS --}}
<div class="row g-3">
    {{-- Recent Requests --}}
    <div class="col-md-6">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-bell"></i> Recent Requests</h6>
                <a href="{{ route('requests.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            @forelse($recentRequests as $req)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <div class="fw-semibold">
                        {{ $req->services->pluck('service_type')->join(', ') }}
                    </div>
                    <small class="text-muted">
                        {{ $req->guest->full_name }} — Room {{ $req->room->room_number }}
                    </small>
                </div>
                <span class="badge badge-{{ $req->status === 'pending' ? 'pending' : ($req->status === 'completed' ? 'completed' : 'progress') }}">
                    {{ ucfirst($req->status) }}
                </span>
            </div>
            @empty
            <p class="text-muted small">No requests yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Tasks --}}
    <div class="col-md-6">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-check"></i> Recent Tasks</h6>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            @forelse($recentTasks as $task)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div>
                    <div class="fw-semibold">{{ $task->title }}</div>
                    <small class="text-muted">
                        {{ $task->housekeeper->full_name }} — Room {{ $task->room->room_number }}
                    </small>
                </div>
                <span class="badge badge-{{ $task->status === 'pending' ? 'pending' : ($task->status === 'completed' ? 'completed' : 'progress') }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
            </div>
            @empty
            <p class="text-muted small">No tasks yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection