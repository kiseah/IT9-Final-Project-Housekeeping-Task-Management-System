@extends('layouts.app')
@section('title', 'Tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">
        <i class="bi bi-list-check"></i>
        {{ auth()->user()->isAdmin() ? 'Task Management' : 'My Tasks' }}
    </h4>
    @if(auth()->user()->isAdmin() && $view === 'active')
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Task
    </a>
    @endif
</div>

{{-- ✅ Active / History Tabs --}}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $view === 'active' ? 'active fw-bold' : '' }}"
           href="{{ route('tasks.index', ['view' => 'active']) }}">
            <i class="bi bi-list-check"></i> Active Tasks
            <span class="badge bg-primary ms-1">{{ $activeCount }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $view === 'history' ? 'active fw-bold' : '' }}"
           href="{{ route('tasks.index', ['view' => 'history']) }}">
            <i class="bi bi-clock-history"></i> History
            <span class="badge bg-secondary ms-1">{{ $historyCount }}</span>
        </a>
    </li>
</ul>

{{-- Info banner for history tab --}}
@if($view === 'history')
<div class="alert alert-secondary mb-3">
    <i class="bi bi-lock-fill"></i>
    <strong>History</strong> — These tasks are
    <strong>completed or cancelled</strong> and are now
    <strong>locked</strong>. They cannot be edited or deleted.
</div>
@endif

{{-- Filters --}}
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-end">
        <input type="hidden" name="view" value="{{ $view }}">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Filter by Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                @if($view === 'active')
                    @foreach(['pending', 'in_progress'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                @else
                    @foreach(['completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Filter by Priority</label>
            <select name="priority" class="form-select form-select-sm">
                <option value="">All Priorities</option>
                @foreach(['low', 'normal', 'high', 'urgent'] as $p)
                    <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>
                        {{ ucfirst($p) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-sm btn-secondary w-100">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('tasks.index', ['view' => $view]) }}"
               class="btn btn-sm btn-outline-secondary w-100">
                <i class="bi bi-x"></i> Clear
            </a>
        </div>
    </form>
</div>

{{-- Tasks Table --}}
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Room</th>
                    @if(auth()->user()->isAdmin())
                    <th>Housekeeper</th>
                    @endif
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr class="{{ $task->is_locked ? 'table-light text-muted' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $task->title }}</strong>
                        @if($task->is_locked)
                            <i class="bi bi-lock-fill text-secondary ms-1"
                               title="Locked"></i>
                        @endif
                    </td>
                    <td>Room {{ $task->room->room_number }}</td>
                    @if(auth()->user()->isAdmin())
                    <td>{{ $task->housekeeper->full_name }}</td>
                    @endif
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
                    <td>
                        {{ $task->due_date
                            ? $task->due_date->format('M d, Y')
                            : '—' }}
                    </td>
                    <td class="text-center">
                        {{-- View always visible --}}
                        <a href="{{ route('tasks.show', $task->id) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- Edit and Delete only for unlocked + Admin --}}
                        @if(auth()->user()->isAdmin() && !$task->is_locked)
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('tasks.destroy', $task->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Locked indicator for history --}}
                        @if($task->is_locked)
                        <span class="badge bg-secondary">
                            <i class="bi bi-lock-fill"></i> Locked
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        @if($view === 'history')
                            <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                            No task history yet.
                        @else
                            <i class="bi bi-check-circle fs-2 d-block mb-2"></i>
                            No active tasks found.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-end">
    {{ $tasks->links() }}
</div>
@endsection