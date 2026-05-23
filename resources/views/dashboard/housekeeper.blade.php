@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-speedometer2"></i> My Dashboard</h4>
    <span class="text-muted">{{ now()->format('l, F d Y') }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat p-3" style="border-color: #ffc107;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Pending Tasks</div>
                    <div class="fs-3 fw-bold">{{ $pendingTasks }}</div>
                </div>
                <i class="bi bi-hourglass fs-1 text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3" style="border-color: #0d6efd;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">In Progress</div>
                    <div class="fs-3 fw-bold">{{ $activeTasks }}</div>
                </div>
                <i class="bi bi-arrow-repeat fs-1 text-primary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat p-3" style="border-color: #198754;">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Completed</div>
                    <div class="fs-3 fw-bold">{{ $completedTasks }}</div>
                </div>
                <i class="bi bi-check-circle fs-1 text-success opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card p-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-list-check"></i> My Active Tasks</h6>
    @forelse($myTasks as $task)
    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
        <div>
            <div class="fw-semibold">{{ $task->title }}</div>
            <small class="text-muted">Room {{ $task->room->room_number }} —
                Due: {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date' }}
            </small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge badge-{{ $task->status === 'pending' ? 'pending' : 'progress' }}">
                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
            </span>
            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
        </div>
    </div>
    @empty
    <p class="text-muted small">No active tasks right now. 🎉</p>
    @endforelse
</div>
@endsection