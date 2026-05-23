@extends('layouts.app')
@section('title', 'Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">
        <i class="bi bi-bell"></i>
        {{ auth()->user()->isGuest() ? 'My Requests' : 'All Requests' }}
    </h4>
    @if(auth()->user()->isGuest())
    <a href="{{ route('requests.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Request
    </a>
    @endif
</div>

{{-- Filter --}}
<div class="card p-3 mb-3">
    <form method="GET" action="{{ route('requests.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Filter by Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                @foreach(['pending', 'reviewed', 'in_progress', 'completed', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $s)) }}
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
            <a href="{{ route('requests.index') }}"
               class="btn btn-sm btn-outline-secondary w-100">
                <i class="bi bi-x"></i> Clear
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    @if(auth()->user()->isAdmin())
                    <th>Guest</th>
                    @endif
                    <th>Services Requested</th>
                    <th>Room</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    @if(auth()->user()->isAdmin())
                    <td>
                        {{ $req->guest->full_name ?? 'Unknown Guest' }}
                        @if($req->guest && $req->guest->trashed())
                            <span class="badge bg-secondary ms-1">
                                <i class="bi bi-archive"></i> Archived
                            </span>
                        @endif
                    </td>
                    @endif

                    {{-- ✅ Grouped services in ONE cell --}}
                    <td>
                        @forelse($req->services as $service)
                            <span class="badge bg-light text-dark border me-1 mb-1">
                                {{ $service->service_type }}
                            </span>
                        @empty
                            <span class="text-muted">—</span>
                        @endforelse
                        @if($req->description)
                            <div class="text-muted small mt-1">
                                <i class="bi bi-chat-left-text"></i>
                                {{ Str::limit($req->description, 50) }}
                            </div>
                        @endif
                    </td>

                    <td>Room {{ $req->room->room_number }}</td>

                    <td>
                        @php
                            $sColor = match($req->status) {
                                'pending'     => 'warning text-dark',
                                'reviewed'    => 'info text-dark',
                                'in_progress' => 'primary',
                                'completed'   => 'success',
                                'cancelled'   => 'danger',
                                default       => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $sColor }}">
                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </td>

                    <td>{{ $req->created_at->diffForHumans() }}</td>

                    <td class="text-center">
                        <a href="{{ route('requests.show', $req->id) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('requests.edit', $req->id) }}"
                           class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if(!$req->task)
                        <a href="{{ route('requests.convert', $req->id) }}"
                           class="btn btn-sm btn-success text-white"
                           title="Convert to Task">
                            <i class="bi bi-arrow-right-circle"></i>
                        </a>
                        @endif
                        <form action="{{ route('requests.destroy', $req->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this request?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-end">
    {{ $requests->links() }}
</div>
@endsection