@extends('layouts.app')
@section('title', 'Archived User History')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">
        <i class="bi bi-clock-history"></i>
        {{ $user->isGuest() ? 'Guest History' : 'Housekeeper History' }}
    </h4>
    <a href="{{ route('users.archived') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Archived
    </a>
</div>

{{-- Profile Card --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="bg-secondary rounded-circle d-inline-flex align-items-center
                            justify-content-center text-white"
                     style="width:65px; height:65px; font-size:26px;">
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                </div>
            </div>
            <div class="col">
                <h5 class="mb-1 fw-bold">{{ $user->full_name }}</h5>
                <div class="text-muted small">{{ $user->email }}</div>
                <div class="mt-1">
                    @if($user->isGuest())
                        <span class="badge bg-success">Guest</span>
                    @elseif($user->isHousekeeper())
                        <span class="badge bg-info text-dark">Housekeeper</span>
                    @else
                        <span class="badge bg-danger">Admin</span>
                    @endif
                    <span class="badge bg-secondary ms-1">
                        <i class="bi bi-archive"></i> Archived
                    </span>
                </div>
            </div>
            <div class="col-auto text-end">
                @if($user->isGuest() && $user->room)
                    <div class="small text-muted">Assigned Room</div>
                    <span class="badge bg-primary fs-6">
                        Room {{ $user->room->room_number }}
                        ({{ $user->room->room_type }})
                    </span>
                @endif
                <div class="small text-muted mt-2">
                    <i class="bi bi-calendar-x"></i>
                    Archived: {{ $user->deleted_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════ --}}
{{-- GUEST HISTORY                                   --}}
{{-- ═══════════════════════════════════════════════ --}}
@if($user->isGuest())

@php
    $totalRequests  = $user->requests->count();
    $completedCount = $user->requests->where('status', 'completed')->count();
    $pendingCount   = $user->requests->where('status', 'pending')->count();
    $cancelledCount = $user->requests->where('status', 'cancelled')->count();
@endphp

{{-- Guest Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #0d6efd;">
            <div class="text-muted small">Total Requests</div>
            <div class="fs-3 fw-bold text-primary">{{ $totalRequests }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #198754;">
            <div class="text-muted small">Completed</div>
            <div class="fs-3 fw-bold text-success">{{ $completedCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #ffc107;">
            <div class="text-muted small">Pending</div>
            <div class="fs-3 fw-bold text-warning">{{ $pendingCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #dc3545;">
            <div class="text-muted small">Cancelled</div>
            <div class="fs-3 fw-bold text-danger">{{ $cancelledCount }}</div>
        </div>
    </div>
</div>

{{-- Guest Request History Table --}}
<div class="card">
    <div class="card-header bg-dark text-white fw-bold">
        <i class="bi bi-clock-history"></i> Full Request & Task History
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>Request Type</th>
                    <th>Room</th>
                    <th>Request Status</th>
                    <th>Assigned Housekeeper</th>
                    <th>Task Status</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->requests as $req)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $req->request_type }}</strong>
                        @if($req->description)
                            <br>
                            <small class="text-muted">{{ $req->description }}</small>
                        @endif
                    </td>
                    <td>
                        Room {{ $req->room->room_number }}
                        <small class="text-muted d-block">{{ $req->room->room_type }}</small>
                    </td>
                    <td>
                        @php
                            $sColor = match($req->status) {
                                'pending'     => 'warning text-dark',
                                'reviewed'    => 'info text-dark',
                                'in_progress' => 'primary',
                                'completed'   => 'success',
                                'cancelled'   => 'danger',
                                default       => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $sColor }}">
                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </td>
                    <td>
                        @if($req->task && $req->task->housekeeper)
                            <div class="fw-semibold">
                                {{ $req->task->housekeeper->full_name }}
                            </div>
                            <small class="text-muted">
                                Assigned {{ $req->task->created_at->format('M d, Y') }}
                            </small>
                        @else
                            <span class="text-muted fst-italic">Not yet assigned</span>
                        @endif
                    </td>
                    <td>
                        @if($req->task)
                            @php
                                $tColor = match($req->task->status) {
                                    'pending'     => 'warning text-dark',
                                    'in_progress' => 'primary',
                                    'completed'   => 'success',
                                    'cancelled'   => 'danger',
                                    default       => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $tColor }}">
                                {{ ucfirst(str_replace('_', ' ', $req->task->status)) }}
                            </span>
                            @if($req->task->due_date)
                                <br>
                                <small class="text-muted">
                                    Due: {{ \Carbon\Carbon::parse($req->task->due_date)->format('M d, Y') }}
                                </small>
                            @endif
                        @else
                            <span class="badge bg-light text-dark border">No Task</span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $req->created_at->format('M d, Y') }}</div>
                        <small class="text-muted">{{ $req->created_at->format('h:i A') }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        This guest had no housekeeping requests during their stay.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════ --}}
{{-- HOUSEKEEPER HISTORY                             --}}
{{-- ═══════════════════════════════════════════════ --}}
@elseif($user->isHousekeeper())

@php
    $totalTasks     = $user->tasks->count();
    $completedTasks = $user->tasks->where('status', 'completed')->count();
    $pendingTasks   = $user->tasks->where('status', 'pending')->count();
    $cancelledTasks = $user->tasks->where('status', 'cancelled')->count();
@endphp

{{-- Housekeeper Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #0d6efd;">
            <div class="text-muted small">Total Tasks</div>
            <div class="fs-3 fw-bold text-primary">{{ $totalTasks }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #198754;">
            <div class="text-muted small">Completed</div>
            <div class="fs-3 fw-bold text-success">{{ $completedTasks }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #ffc107;">
            <div class="text-muted small">Pending</div>
            <div class="fs-3 fw-bold text-warning">{{ $pendingTasks }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3" style="border-left: 4px solid #dc3545;">
            <div class="text-muted small">Cancelled</div>
            <div class="fs-3 fw-bold text-danger">{{ $cancelledTasks }}</div>
        </div>
    </div>
</div>

{{-- Housekeeper Task History Table --}}
<div class="card">
    <div class="card-header bg-dark text-white fw-bold">
        <i class="bi bi-list-check"></i> Full Task History
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>Task Title</th>
                    <th>Room</th>
                    <th>Priority</th>
                    <th>Linked Guest Request</th>
                    <th>Task Status</th>
                    <th>Due Date</th>
                    <th>Date Assigned</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->tasks as $task)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $task->title }}</strong>
                        @if($task->description)
                            <br>
                            <small class="text-muted">{{ $task->description }}</small>
                        @endif
                    </td>
                    <td>
                        Room {{ $task->room->room_number }}
                        <small class="text-muted d-block">{{ $task->room->room_type }}</small>
                    </td>
                    <td>
                        @php
                            $pColor = match($task->priority) {
                                'urgent' => 'danger',
                                'high'   => 'warning',
                                'normal' => 'primary',
                                'low'    => 'secondary',
                                default  => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $pColor }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td>
                        @if($task->request)
                            <div class="fw-semibold small">
                                {{ $task->request->request_type }}
                            </div>
                            <small class="text-muted">
                                by {{ $task->request->guest->full_name ?? 'Archived Guest' }}
                            </small>
                        @else
                            <span class="text-muted fst-italic">No linked request</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $tColor = match($task->status) {
                                'pending'     => 'warning text-dark',
                                'in_progress' => 'primary',
                                'completed'   => 'success',
                                'cancelled'   => 'danger',
                                default       => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $tColor }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td>
                        @if($task->due_date)
                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $task->created_at->format('M d, Y') }}</div>
                        <small class="text-muted">{{ $task->created_at->format('h:i A') }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        This housekeeper had no assigned tasks.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endif

{{-- Bottom Actions --}}
<div class="d-flex gap-2 mt-4">
    <form action="{{ route('users.restore', $user->id) }}"
          method="POST"
          onsubmit="return confirm('Restore {{ $user->full_name }}?')">
        @csrf
        @method('PUT')
        <button class="btn btn-success">
            <i class="bi bi-arrow-counterclockwise"></i> Restore
        </button>
    </form>

    <form action="{{ route('users.force-delete', $user->id) }}"
          method="POST"
          onsubmit="return confirm('PERMANENTLY delete {{ $user->full_name }}? This cannot be undone.')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">
            <i class="bi bi-trash-fill"></i> Permanently Delete
        </button>
    </form>

    <a href="{{ route('users.archived') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

@endsection