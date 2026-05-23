@extends('layouts.app')
@section('title', 'Archived Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">
        <i class="bi bi-archive"></i> Archived Users
        <span class="badge bg-secondary ms-2">Checked-Out Guests & Deleted Accounts</span>
    </h4>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
</div>

{{-- Info Banner --}}
<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle"></i>
    Archived users are <strong>not permanently deleted</strong>. Their housekeeping request
    and task history is fully preserved. You can restore or permanently delete them below.
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Room</th>
                    <th>Checked Out / Archived</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($archivedUsers as $user)
                <tr class="table-warning">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $user->full_name }}</strong>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-danger">Admin</span>
                        @elseif($user->role === 'housekeeper')
                            <span class="badge bg-info text-dark">Housekeeper</span>
                        @else
                            <span class="badge bg-success">Guest</span>
                        @endif
                    </td>
                    <td>
                        @if($user->room)
                            <span class="badge bg-primary">
                                Room {{ $user->room->room_number }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted small">
                            {{ $user->deleted_at->format('M d, Y h:i A') }}
                        </span>
                    </td>
                    <td class="text-center">
                        {{-- View History --}}
                        <a href="{{ route('users.archived.show', $user->id) }}"
                           class="btn btn-sm btn-info text-white"
                           title="View History">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- Restore --}}
                        <form action="{{ route('users.restore', $user->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Restore {{ $user->first_name }}?')">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-sm btn-success"
                                    title="Restore User">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </form>

                        {{-- Permanent Delete --}}
                        <form action="{{ route('users.force-delete', $user->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('PERMANENTLY delete {{ $user->first_name }}? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    title="Permanently Delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-archive"></i>
                        No archived users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-end">
    {{ $archivedUsers->links() }}
</div>
@endsection