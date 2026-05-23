@extends('layouts.app')
@section('title', 'Convert to Task')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">
        <i class="bi bi-arrow-right-circle"></i> Convert Request to Task
    </h4>
    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

{{-- Request Summary --}}
<div class="alert alert-info mb-4">
    <strong>Request #{{ $request->id }}:</strong>
    {{ $request->request_type }} —
    {{ $request->guest->full_name }},
    Room {{ $request->room->room_number }}
    @if($request->description)
        <br><small>{{ $request->description }}</small>
    @endif
</div>

<div class="card p-4" style="max-width: 700px;">
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        {{-- Pre-fill hidden fields from the request --}}
        <input type="hidden" name="request_id" value="{{ $request->id }}">
        <input type="hidden" name="room_id"    value="{{ $request->room_id }}">

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Task Title <span class="text-danger">*</span>
            </label>
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $request->services->pluck('service_type')->join(', ') . ' - Room ' . $request->room->room_number) }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Assign To (Housekeeper) <span class="text-danger">*</span>
            </label>
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

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Description <span class="text-muted">(optional)</span>
            </label>
            <textarea name="description" rows="3"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Task details...">{{ old('description', $request->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Priority <span class="text-danger">*</span>
                </label>
                <select name="priority"
                        class="form-select @error('priority') is-invalid @enderror">
                    @foreach(['low', 'normal', 'high', 'urgent'] as $p)
                        <option value="{{ $p }}"
                            {{ old('priority', 'normal') == $p ? 'selected' : '' }}>
                            {{ ucfirst($p) }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Status <span class="text-danger">*</span>
                </label>
                <select name="status"
                        class="form-select @error('status') is-invalid @enderror">
                    @foreach(['pending', 'in_progress'] as $s)
                        <option value="{{ $s }}"
                            {{ old('status', 'pending') == $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Due Date <span class="text-muted">(optional)</span>
                </label>
                <input type="date" name="due_date"
                       class="form-control @error('due_date') is-invalid @enderror"
                       value="{{ old('due_date') }}">
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Create Task from Request
            </button>
            <a href="{{ route('requests.show', $request->id) }}"
               class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection