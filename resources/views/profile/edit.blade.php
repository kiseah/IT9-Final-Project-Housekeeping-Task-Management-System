@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-person-circle"></i> My Profile</h4>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row g-3">

    {{-- Profile Info --}}
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-4">
                <i class="bi bi-person"></i> Personal Information
            </h6>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $user->first_name) }}">
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
                               value="{{ old('middle_name', $user->middle_name) }}">
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Last Name</label>
                    <input type="text" name="last_name"
                           class="form-control @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name', $user->last_name) }}">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <input type="text" class="form-control"
                           value="{{ ucfirst($user->role) }}" disabled>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Profile
                </button>
            </form>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-4">
                <i class="bi bi-lock"></i> Change Password
            </h6>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Current Password</label>
                    <input type="password" name="current_password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           placeholder="Enter current password">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimum 8 characters">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Re-enter new password">
                </div>

                <button type="submit" class="btn btn-warning text-white">
                    <i class="bi bi-shield-lock"></i> Change Password
                </button>
            </form>
        </div>
    </div>

</div>
@endsection