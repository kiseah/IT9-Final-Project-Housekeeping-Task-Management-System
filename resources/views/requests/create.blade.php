@extends('layouts.app')
@section('title', 'New Request')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="bi bi-plus-circle"></i> Submit a Request</h4>
    <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card p-4" style="max-width: 650px;">

    {{-- Room Info --}}
    <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-door-closed fs-5"></i>
        <div>
            <div class="fw-semibold">Your Room</div>
            <div>Room {{ $room->room_number }} — {{ $room->room_type }}</div>
        </div>
    </div>

    <form action="{{ route('requests.store') }}" method="POST">
        @csrf

        {{-- ✅ Services Checkboxes --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">
                Select Services <span class="text-danger">*</span>
                <small class="text-muted fw-normal">(choose one or more)</small>
            </label>

            @error('services')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror

            <div class="row g-2">
                @foreach([
                    'Room Cleaning',
                    'Change Linen',
                    'Extra Towels',
                    'Extra Pillows',
                    'Extra Blankets',
                    'Toiletries Refill',
                    'Laundry Service',
                    'Trash Removal',
                    'Maintenance Issue',
                    'Other',
                ] as $service)
                <div class="col-md-6">
                    <div class="form-check border rounded p-2 ps-4
                        {{ in_array($service, old('services', [])) ? 'bg-primary bg-opacity-10 border-primary' : '' }}">
                        <input class="form-check-input service-check"
                               type="checkbox"
                               name="services[]"
                               value="{{ $service }}"
                               id="svc_{{ Str::slug($service) }}"
                               {{ in_array($service, old('services', [])) ? 'checked' : '' }}>
                        <label class="form-check-label w-100"
                               for="svc_{{ Str::slug($service) }}">
                            {{ $service }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Additional Notes --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">
                Additional Notes <span class="text-muted">(optional)</span>
            </label>
            <textarea name="description" rows="3"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="Any specific instructions or details...">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i> Submit Request
            </button>
            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // ✅ Highlight selected checkboxes
    document.querySelectorAll('.service-check').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const wrapper = this.closest('.form-check');
            if (this.checked) {
                wrapper.classList.add('bg-primary', 'bg-opacity-10', 'border-primary');
            } else {
                wrapper.classList.remove('bg-primary', 'bg-opacity-10', 'border-primary');
            }
        });
    });
</script>
@endpush

@endsection