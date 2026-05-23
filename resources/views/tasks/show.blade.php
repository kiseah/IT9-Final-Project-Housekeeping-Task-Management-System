@extends('layouts.app')
@section('title', 'Task Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-list-check"></i> Task Details</h4>
    <div class="d-flex gap-2">
        {{-- Lock banner --}}
        @if($task->is_locked)
        <div class="alert alert-secondary mb-3">
            <i class="bi bi-lock-fill"></i>
            This task is <strong>locked</strong> — it has been
            <strong>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</strong>
            and moved to history. No edits are allowed.
        </div>
        @endif
        @if(auth()->user()->isAdmin())
        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning text-white">
            <i class="bi bi-pencil"></i> Edit
        </a>
        @endif
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Task Info --}}
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Task Information</h6>
            <table class="table table-borderless mb-0">
                <tr>
                    <td class="text-muted" width="140">Title</td>
                    <td><strong>{{ $task->title }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Room</td>
                    <td>Room {{ $task->room->room_number }} ({{ $task->room->room_type }})</td>
                </tr>
                <tr>
                    <td class="text-muted">Housekeeper</td>
                    <td>{{ $task->housekeeper->full_name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Priority</td>
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
                        <span class="badge bg-{{ $pColor }}">{{ ucfirst($task->priority) }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>
                        @php
                            $sColor = match($task->status) {
                                'pending'     => 'warning text-dark',
                                'in_progress' => 'primary',
                                'completed'   => 'success',
                                'cancelled'   => 'danger',
                                default       => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $sColor }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Due Date</td>
                    <td>{{ $task->due_date
                        ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y')
                        : '—' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Description</td>
                    <td>{{ $task->description ?? '—' }}</td>
                </tr>
                @if($task->request)
                <tr>
                    <td class="text-muted">Linked Request</td>
                    <td>
                        #{{ $task->request->id }} — {{ $task->request->request_type }}
                        <br>
                        <small class="text-muted">by {{ $task->request->guest->full_name ?? '—' }}</small>
                    </td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Housekeeper: Update Status (only if not locked) --}}
    @if(auth()->user()->isHousekeeper())
        @if($task->is_locked)
            <div class="col-md-6">
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">Task Status</h6>
                    <div class="alert alert-secondary mb-0">
                        <i class="bi bi-lock-fill"></i>
                        This task is <strong>locked</strong> because it is
                        <strong>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</strong>.
                        No further changes can be made.
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-6">
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">Update Task Status</h6>
                    <form action="{{ route('tasks.update.status', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Status</label>
                            <select name="status" class="form-select">
                                @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $s)
                                    <option value="{{ $s }}"
                                        {{ $task->status == $s ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-exclamation-triangle"></i>
                            Setting status to <strong>Completed</strong> or
                            <strong>Cancelled</strong> will <strong>lock</strong>
                            this task permanently.
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-repeat"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection