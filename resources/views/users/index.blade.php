@extends('layouts.app')
@section('title', 'Users')

@section('content')
<!-- <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-people"></i> User Management</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add User
    </a>
</div> -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-people"></i> User Management</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('users.archived') }}" class="btn btn-outline-secondary">
            <i class="bi bi-archive"></i> Archived Users
        </a>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add User
        </a>
    </div>
</div>

{{-- Filter Tabs --}}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ !request('role') ? 'active' : '' }}"
           href="{{ route('users.index') }}">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('role') === 'admin' ? 'active' : '' }}"
           href="{{ route('users.index', ['role' => 'admin']) }}">Admins</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('role') === 'housekeeper' ? 'active' : '' }}"
           href="{{ route('users.index', ['role' => 'housekeeper']) }}">Housekeepers</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('role') === 'guest' ? 'active' : '' }}"
           href="{{ route('users.index', ['role' => 'guest']) }}">Guests</a>
    </li>
</ul>

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
                    <th>Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $user->full_name }}</strong>
                        @if($user->id === auth()->id())
                            <span class="badge bg-secondary ms-1">You</span>
                        @endif
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
                        @if($user->isGuest() && $user->room)
                            <span class="badge bg-primary">
                                Room {{ $user->room->room_number }}
                            </span>
                        @elseif($user->isGuest())
                            <span class="text-muted small">No room</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="text-center">
                        <a href="{{ route('users.show', $user->id) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}"
                           class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <!-- @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm(
                                '{{ $user->isGuest() ? 'Check out ' . $user->first_name . '? Their records will be archived.' : 'Delete ' . $user->first_name . '?' }}'
                            )">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm {{ $user->isGuest() ? 'btn-warning' : 'btn-danger' }}"
                                    title="{{ $user->isGuest() ? 'Check Out Guest' : 'Delete User' }}">
                                <i class="bi bi-{{ $user->isGuest() ? 'box-arrow-right' : 'trash' }}"></i>
                            </button>
                        </form>
                        @endif -->
                        
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm(
                                '{{ $user->isGuest()
                                    ? 'Check out ' . $user->first_name . '? Their records will be archived.'
                                    : ($user->isHousekeeper()
                                        ? 'Archive housekeeper ' . $user->first_name . '? Their task history will be preserved.'
                                        : 'Delete ' . $user->first_name . '?') }}'
                            )">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm {{ $user->isGuest() ? 'btn-warning' : ($user->isHousekeeper() ? 'btn-warning' : 'btn-danger') }}"
                                    title="{{ $user->isGuest() ? 'Check Out Guest' : ($user->isHousekeeper() ? 'Archive Housekeeper' : 'Delete User') }}">
                                <i class="bi bi-{{ $user->isGuest() ? 'box-arrow-right' : ($user->isHousekeeper() ? 'archive' : 'trash') }}"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-end">
    {{ $users->links() }}
</div>
@endsection