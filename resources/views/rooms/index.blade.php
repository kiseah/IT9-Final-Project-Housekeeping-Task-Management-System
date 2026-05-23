@extends('layouts.app')
@section('title', 'Rooms')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-door-closed"></i> Room Management</h4>
    <a href="{{ route('rooms.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Room
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>Room {{ $room->room_number }}</strong></td>
                    <td>{{ $room->room_type }}</td>
                    <td>
                        @if($room->status === 'available')
                            <span class="badge bg-success">Available</span>
                        @elseif($room->status === 'occupied')
                            <span class="badge bg-primary">Occupied</span>
                        @else
                            <span class="badge bg-warning text-dark">Under Maintenance</span>
                        @endif
                    </td>
                    <td>{{ $room->notes ?? '—' }}</td>
                    <td class="text-center">
                        <a href="{{ route('rooms.show', $room->id) }}"
                           class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('rooms.edit', $room->id) }}"
                           class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('rooms.destroy', $room->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete Room {{ $room->room_number }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No rooms found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3 d-flex justify-content-end">
    {{ $rooms->links() }}
</div>
@endsection