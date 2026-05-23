@extends('layouts.app')
@section('title', 'Add Task')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-plus-circle"></i> Add New Task</h4>
    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card p-4" style="max-width: 700px;">
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Task Title <span class="text-danger">*</span></label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}"
                   placeholder="e.g. Clean Room 101">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Assign To (Housekeeper) <span class="text-danger">*</span></label>
                <select name="housekeeper_id"
                        class="form-select @error('housekeeper_id') is-invalid @enderror">
                    <option value="">-- Select Housekeeper --</option>
                    @foreach($housekeepers as $hk)
                        <option value="{{ $hk->id }}"
                            {{ old('housekeeper_id') == $hk->id ? 'selected' : '' }}>
                            {{ $hk->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('housekeeper_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Room <span class="text-danger">*</span></label>
                <select name="room_id"
                        class="form-select @error('room_id') is-invalid @enderror">
                    <option value="">-- Select Room --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}"
                            {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            Room {{ $room->room_number }} ({{ $room->room_type }})
                        </option>
                    @endforeach
                </select>
                @error('room_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Linked Request
                <span class="text-muted">(optional)</span>
            </label>
            <select name="request_id"
                    class="form-select @error('request_id') is-invalid @enderror">
                <option value="">-- No linked request --</option>
                @foreach($requests as $req)
                    <option value="{{ $req->id }}"
                        {{ old('request_id') == $req->id ? 'selected' : '' }}>
                        #{{ $req->id }} — {{ $req->request_type }}
                        ({{ $req->guest->full_name }}, Room {{ $req->room->room_number }})
                    </option>
                @endforeach
            </select>
            @error('request_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Description <span class="text-muted">(optional)</span></label>
            <textarea name="description" rows="3"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Task details...">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
                <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                    @foreach(['low', 'normal', 'high', 'urgent'] as $p)
                        <option value="{{ $p }}" {{ old('priority', 'normal') == $p ? 'selected' : '' }}>
                            {{ ucfirst($p) }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', 'pending') == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Due Date <span class="text-muted">(optional)</span></label>
                <input type="date" name="due_date"
                       class="form-control @error('due_date') is-invalid @enderror"
                       value="{{ old('due_date') }}">
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Create Task
            </button>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection