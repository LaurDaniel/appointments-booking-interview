@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Book Appointment') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div id='full_calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modalClose()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="hours">Pick a Time</label>
                <select id="hours"></select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="modalClose()">Close</button>
                <button type="button" class="btn btn-primary" onclick="bookAppointment()">Book Appointment</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#hours').select2({
            dropdownParent: $('#appointmentModal')
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var calendar = $('#full_calendar').fullCalendar({
            editable: true,
            displayEventTime: false,
            contentHeight: 500,
            validRange: {
                start: moment().format('YYYY-MM-DD')
            },
            events: {
                url: '/get-events',
                method: 'GET',
            },
            eventRender: function(event, element) {
                element.css("font-size", "1.2em");
                element.css("padding", "5px");
            },
            eventClick: function(event) {
                $.ajax({
                    url: '/get-available-hours',
                    type: 'POST',
                    data: {
                        date: event.start.format('YYYY-MM-DD'),
                    },
                    success: function(data) {
                        $('#hours').find('option').remove();
                        $.each(data, function(key, value) {
                            $('#hours').append('<option value="' + value + '">' + value.substring(0, 5) + '</option>');
                        });
                        $('#appointmentModalLabel').html('Selected Date: ' + event.start.format('YYYY-MM-DD'));
                        $('#appointmentModal').modal('show');
                    }
                });
            }

        });
    });

    function modalClose() {
        $('#appointmentModal').modal('hide');
    }

    function bookAppointment() {
        var date = $('#appointmentModalLabel').html().split(': ')[1];
        var hour = $('#hours').val();
        $.ajax({
            url: '/book-appointment',
            type: 'POST',
            data: {
                date: date,
                time: hour,
            },
            success: function(data) {
                alert('Appointment booked successfully on ' + data.date + ' at: ' + data.time.substring(0, 5));
                $('#full_calendar').fullCalendar('refetchEvents');
                $('#appointmentModal').modal('hide');
            }
        });
    }
</script>

@endsection
