@extends('layouts.app')
@section('title', 'Update Request')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-pencil"></i> Update Request Status</h4>
    <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card p-4" style="max-width: 500px;">
    {{-- Request Summary --}}
    <div class="alert alert-light border mb-4">
        <div><strong>Request #{{ $request->id }}</strong></div>
        <div class="text-muted small">
            {{ $request->request_type }} —
            {{ $request->guest->full_name }},
            Room {{ $request->room->room_number }}
        </div>
    </div>

    <form action="{{ route('requests.update', $request->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="form-label fw-semibold">
                Status <span class="text-danger">*</span>
            </label>
            <select name="status"
                    class="form-select @error('status') is-invalid @enderror">
                @foreach(['pending', 'reviewed', 'in_progress', 'completed', 'cancelled'] as $s)
                    <option value="{{ $s }}"
                        {{ old('status', $request->status) == $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning text-white">
                <i class="bi bi-save"></i> Update Status
            </button>
            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection