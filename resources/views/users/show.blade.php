@extends('layouts.app')
@section('title', 'User Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-person"></i> User Details</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning text-white">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- User Info --}}
    <div class="col-md-4">
        <div class="card p-4">
            <div class="text-center mb-3">
                <div class="bg-secondary rounded-circle d-inline-flex align-items-center
                            justify-content-center text-white"
                     style="width:70px; height:70px; font-size:28px;">
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                </div>
                <h5 class="mt-2 mb-0">{{ $user->full_name }}</h5>
                <small class="text-muted">{{ $user->email }}</small>
            </div>
            <hr>
            <table class="table table-borderless mb-0 small">
                <tr>
                    <td class="text-muted">Role</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->role === 'housekeeper')
                            <span class="badge bg-info text-dark">Housekeeper</span>
                        @else
                            <span class="badge bg-success">Guest</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Joined</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>

                @if($user->isGuest())
                <tr>
                    <td class="text-muted">Room</td>
                    <td>
                        @if($user->room)
                            <span class="badge bg-primary">
                                Room {{ $user->room->room_number }}
                            </span>
                            ({{ $user->room->room_type }})
                        @else
                            <span class="text-muted">No room assigned</span>
                        @endif
                    </td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Tasks (if housekeeper) --}}
    @if($user->isHousekeeper())
    <div class="col-md-8">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Assigned Tasks</h6>
            @forelse($user->tasks->take(5) as $task)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold small">{{ $task->title }}</div>
                        <small class="text-muted">
                            Due: {{ $task->due_date
                                ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y')
                                : 'No due date' }}
                        </small>
                    </div>
                    <span class="badge bg-secondary">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>
            @empty
                <p class="text-muted small">No tasks assigned yet.</p>
            @endforelse
        </div>
    </div>
    @endif

    {{-- Requests (if guest) --}}
    @if($user->isGuest())
    <div class="col-md-8">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Submitted Requests</h6>
            @forelse($user->requests->take(5) as $req)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold small">{{ $req->request_type }}</div>
                        <small class="text-muted">{{ $req->created_at->format('M d, Y') }}</small>
                    </div>
                    <span class="badge bg-secondary">{{ ucfirst($req->status) }}</span>
                </div>
            @empty
                <p class="text-muted small">No requests submitted yet.</p>
            @endforelse
        </div>
    </div>
    @endif
</div>
@endsection