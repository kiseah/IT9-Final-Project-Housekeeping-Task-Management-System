@extends('layouts.app')
@section('title', 'Add User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-person-plus"></i> Add New User</h4>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card p-4" style="max-width: 600px;">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name"
                       class="form-control @error('first_name') is-invalid @enderror"
                       value="{{ old('first_name') }}">
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Middle Name <span class="text-muted">(optional)</span>
                </label>
                <input type="text" name="middle_name"
                       class="form-control @error('middle_name') is-invalid @enderror"
                       value="{{ old('middle_name') }}">
                @error('middle_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="last_name"
                   class="form-control @error('last_name') is-invalid @enderror"
                   value="{{ old('last_name') }}">
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
            <select name="role" id="roleSelect"
                    class="form-select @error('role') is-invalid @enderror"
                    onchange="toggleRoomField()">
                <option value="">-- Select Role --</option>
                <option value="admin"       {{ old('role') == 'admin'       ? 'selected' : '' }}>Admin</option>
                <option value="housekeeper" {{ old('role') == 'housekeeper' ? 'selected' : '' }}>Housekeeper</option>
                <option value="guest"       {{ old('role') == 'guest'       ? 'selected' : '' }}>Guest</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ✅ Room field: only visible when role is Guest --}}
        <div class="mb-3" id="roomField" style="display: none;">
            <label class="form-label fw-semibold">
                Assign Room <span class="text-danger">*</span>
                <small class="text-muted fw-normal">(required for guests)</small>
            </label>
            <select name="room_id"
                    class="form-select @error('room_id') is-invalid @enderror">
                <option value="">-- Select Room --</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}"
                        {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        Room {{ $room->room_number }}
                        ({{ $room->room_type }})
                        — {{ ucfirst(str_replace('_', ' ', $room->status)) }}
                    </option>
                @endforeach
            </select>
            @error('room_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Minimum 8 characters">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation"
                   class="form-control"
                   placeholder="Re-enter password">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Create User
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleRoomField() {
        const role      = document.getElementById('roleSelect').value;
        const roomField = document.getElementById('roomField');
        const roomSelect = roomField.querySelector('select');

        if (role === 'guest') {
            roomField.style.display = 'block';
            roomSelect.setAttribute('required', 'required');
        } else {
            roomField.style.display = 'none';
            roomSelect.removeAttribute('required');
            roomSelect.value = '';
        }
    }

    // ✅ Run on page load to handle old() input after validation error
    document.addEventListener('DOMContentLoaded', function () {
        toggleRoomField();
    });
</script>
@endpush

@endsection