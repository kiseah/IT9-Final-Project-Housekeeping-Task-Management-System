@extends('layouts.app')
@section('title', 'Room Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-door-closed"></i> Room {{ $room->room_number }} Details</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning text-white">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Room Info --}}
    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Room Information</h6>
            <table class="table table-borderless mb-0">
                <tr>
                    <td class="text-muted">Room No.</td>
                    <td><strong>{{ $room->room_number }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Type</td>
                    <td>{{ $room->room_type }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>
                        @if($room->status === 'available')
                            <span class="badge bg-success">Available</span>
                        @elseif($room->status === 'occupied')
                            <span class="badge bg-primary">Occupied</span>
                        @else
                            <span class="badge bg-warning text-dark">Under Maintenance</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Notes</td>
                    <td>{{ $room->notes ?? '—' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Recent Tasks --}}
    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Recent Tasks</h6>
            @forelse($room->tasks->take(5) as $task)
                <div class="py-2 border-bottom">
                    <div class="fw-semibold small">{{ $task->title }}</div>
                    <small class="text-muted">{{ $task->housekeeper->full_name ?? '—' }}</small>
                    <span class="badge bg-secondary float-end">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>
            @empty
                <p class="text-muted small">No tasks for this room.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Requests --}}
    <div class="col-md-4">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Recent Requests</h6>
            @forelse($room->requests->take(5) as $req)
                <div class="py-2 border-bottom">
                    <div class="fw-semibold small">{{ $req->request_type }}</div>
                    <small class="text-muted">{{ $req->guest->full_name ?? '—' }}</small>
                    <span class="badge bg-secondary float-end">
                        {{ ucfirst($req->status) }}
                    </span>
                </div>
            @empty
                <p class="text-muted small">No requests for this room.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection