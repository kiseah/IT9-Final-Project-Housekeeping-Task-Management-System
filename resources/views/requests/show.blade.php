@extends('layouts.app')
@section('title', 'Request Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-bell"></i> Request Details</h4>
    <div class="d-flex gap-2">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('requests.edit', $request->id) }}"
           class="btn btn-warning text-white">
            <i class="bi bi-pencil"></i> Edit Status
        </a>
        @if(!$request->task)
        <a href="{{ route('requests.convert', $request->id) }}"
           class="btn btn-success text-white">
            <i class="bi bi-arrow-right-circle"></i> Convert to Task
        </a>
        @endif
        @endif
        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Request Info --}}
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Request Information</h6>
            <table class="table table-borderless mb-0">
                <tr>
                    <td class="text-muted" width="130">Request #</td>
                    <td><strong>#{{ $request->id }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Guest</td>
                    <td>{{ $request->guest->full_name }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Room</td>
                    <td>Room {{ $request->room->room_number }}
                        ({{ $request->room->room_type }})</td>
                </tr>
                <tr>
                    <td class="text-muted">Services</td>
                    <td>
                        @foreach($request->services as $service)
                            <span class="badge bg-light text-dark border me-1 mb-1">
                                {{ $service->service_type }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Notes</td>
                    <td>{{ $request->description ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Status</td>
                    <td>
                        @php
                            $sColor = match($request->status) {
                                'pending'     => 'warning text-dark',
                                'reviewed'    => 'info text-dark',
                                'in_progress' => 'primary',
                                'completed'   => 'success',
                                'cancelled'   => 'danger',
                                default       => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $sColor }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Submitted</td>
                    <td>{{ $request->created_at->format('M d, Y h:i A') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Linked Task --}}
    <div class="col-md-6">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Linked Task</h6>
            @if($request->task)
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="130">Task</td>
                        <td>
                            <a href="{{ route('tasks.show', $request->task->id) }}">
                                {{ $request->task->title }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Housekeeper</td>
                        <td>{{ $request->task->housekeeper->full_name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Task Status</td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ ucfirst(str_replace('_', ' ', $request->task->status)) }}
                            </span>
                        </td>
                    </tr>
                </table>
            @else
                <p class="text-muted small">
                    No task has been created from this request yet.
                    @if(auth()->user()->isAdmin())
                        <br>
                        <a href="{{ route('requests.convert', $request->id) }}">
                            Convert to task now →
                        </a>
                    @endif
                </p>
            @endif
        </div>
    </div>
</div>
@endsection