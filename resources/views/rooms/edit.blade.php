@extends('layouts.app')
@section('title', 'Edit Room')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-pencil"></i> Edit Room {{ $room->room_number }}</h4>
    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card p-4" style="max-width: 600px;">
    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Room Number <span class="text-danger">*</span></label>
            <input type="text" name="room_number"
                   class="form-control @error('room_number') is-invalid @enderror"
                   value="{{ old('room_number', $room->room_number) }}">
            @error('room_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Room Type <span class="text-danger">*</span></label>
            <select name="room_type" class="form-select @error('room_type') is-invalid @enderror">
                @foreach(['Single', 'Double', 'Twin', 'Suite', 'Deluxe'] as $type)
                    <option value="{{ $type }}"
                        {{ old('room_type', $room->room_type) == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
            @error('room_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select @error('status') is-invalid @enderror">
                <option value="available"         {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                <option value="occupied"          {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="under_maintenance" {{ old('status', $room->status) == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Notes <span class="text-muted">(optional)</span></label>
            <textarea name="notes" rows="3"
                      class="form-control @error('notes') is-invalid @enderror"
                      placeholder="Any additional notes...">{{ old('notes', $room->notes) }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning text-white">
                <i class="bi bi-save"></i> Update Room
            </button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection