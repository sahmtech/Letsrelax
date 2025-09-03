@if ($data->status != 'completed' && $data->status != 'cancelled')
    <select name="branch_for" class="select2 change-select" data-token="{{ csrf_token() }}"
        data-url="{{ route('backend.bookings.updateStatus', ['id' => $data->id, 'action_type' => 'update-status']) }}"
        style="width: 100%;">
        @foreach ($booking_status as $key => $value)
            @php
                $color = $booking_colors->where('sub_type', $data->status)->first()->name;
            @endphp
            <option value="{{ $value->name }}" {{ $data->status == $value->name ? 'selected' : '' }}
                data-color="{{ $color }}">{{ $value->value }}</option>
        @endforeach
    </select>
@elseif($data->status === 'cancelled')
    @php
        $color = $booking_colors->where('sub_type', $data->status)->first()->name;
    @endphp
    <div class="cancellation-status-container">
        <span class="text-capitalize">
            @if ($data->cancellation_note)
                <span class="cancellation-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ $data->cancellation_note }}">
                    <i class="fas fa-comment-alt"></i>
                </span>
            @endif
            <i class="fa-solid fa-circle" style="color: {{ $color }}"></i>
            {{ $data->status }}
        </span>
    </div>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
@else
    @php
        $color = $booking_colors->where('sub_type', $data->status)->first()->name;
    @endphp
    <span class="text-capitalize"><i class="fa-solid fa-circle" style="color: {{ $color }}"></i>
        {{ $data->status }}</span>
@endif
