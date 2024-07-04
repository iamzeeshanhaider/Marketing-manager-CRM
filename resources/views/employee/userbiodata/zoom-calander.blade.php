@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/modal/sweetalert.css') }}">
@endsection
@section('content')
    <div class="container">
        <div id='calendar'></div>
    </div>


    <!-- The modal markup -->
    <div class="modal fade" id="eventTitle" tabindex="-1" role="dialog" aria-labelledby="eventTitleLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header company-header">
                    <h5 class="modal-title text-white" id="eventTitleLabel">Add Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">


                        <div class="col-12 mb-3">
                            <label for="eventsTitle" class="form-label">Event Title:</label>
                            <input name="title" type="text" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="attendee" class="form-label">Add Attenddee</label>
                            <input id="attendee" name="attendee" type="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time:</label>
                            <input id="start_time" name="start_time" type="time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time:</label>
                            <input id="end_time" name="end_time" type="time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">Email Template</label>
                            <select name="email_template" class="form-control" id="">
                                @foreach ($emails as $email)
                                    <option value="{{ $email->id }}">{{ $email->title }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_title" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>


    <!-- The modal markup -->
    <div class="modal fade" id="eventAction" tabindex="-1" role="dialog" aria-labelledby="eventActionLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header company-header">
                    <h5 class="modal-title text-white" id="eventActionLabel">Event Actions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <label for="eventsTitle" class="form-label">Event Title:</label>
                            <input id="eventsTitle" name="eventTitle" type="text" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="attendee" class="form-label">Add Attenddee</label>
                            <input id="attendeeupdate" name="attendee" type="text" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="starttime" class="form-label">Start Time:</label>
                            <input id="starttime" name="start_time" type="time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endtime" class="form-label">End Time:</label>
                            <input id="endtime" name="end_time" type="time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">Email Template</label>
                            <select name="email_update" class="form-control" id="">
                                @foreach ($emails as $email)
                                    <option value="{{ $email->id }}">{{ $email->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="update_event" class="btn btn-warning">Update</button>
                    <button type="button" id="delete_event" class="btn btn-danger">Delete</button>
                    <button type="button" id="meeting" class="btn btn-primary">Meeting</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        $(document).ready(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    start: 'prev,next today',
                    center: 'title',
                    end: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap5',
                selectable: true,
                selectMirror: true,
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '/zoom/meetings/events',
                        method: 'GET',

                        success: function(response) {
                            successCallback(response);
                        },
                        error: function(xhr, status, error) {
                            failureCallback(error);
                        }
                    });
                },
                select: function(info, successCallback, failureCallback) {
                    $('#eventTitle').modal('show');
                    $('#eventTitle').modal({
                        backdrop: 'static',
                        keyboard: false
                    })
                    $('#add_title').click(function() {
                        var title = $('input[name="title"]').val();
                        var start = $('input[name="start_time"]').val();
                        var end = $('input[name="end_time"]').val();
                        var attendee = $('input[name="attendee"]').val();
                        var email = $('select[name="email_template"]').val();
                        if (title) {
                            if (!start || !end) {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Both start and end times must be provided!'
                                });
                                return;
                            }
                            if (start >= end) {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Start time must be before end time!'
                                });
                                return;
                            }
                            var startDate = new Date(info.startStr);
                            var endDate = new Date(info.startStr);
                            if (start) startDate.setHours(start.split(":")[0], start.split(":")[
                                1], 0);
                            if (end) endDate.setHours(end.split(":")[0], end.split(":")[1], 0);
                            var duration = (endDate - startDate) / (1000 * 60)
                            var eventData = {
                                title: title,
                                start_time: startDate.toISOString(),
                                duration: duration,
                                attendee: attendee,
                                email: email
                            };

                            $.ajax({
                                url: '/zoom/meetings/create',
                                method: 'POST',
                                data: eventData,
                                success: function(response) {
                                    if (response.status == 'success') {
                                        $('#eventTitle').modal('hide');
                                        swal.fire({
                                            icon: 'success',
                                            title: 'Hurrah...',
                                            text: response.message
                                        });

                                        calendar.refetchEvents();
                                        resetModal('eventTitle');
                                    } else {
                                        swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: response.message
                                        });
                                        return;
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log(error);
                                }
                            });

                        } else {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Please enter a title!'
                            });
                        }
                    });

                    calendar.unselect();
                },
                eventClick: function(info) {
                    var start = info.event.start;
                    var end = info.event.end;
                    var title = info.event.title;
                    var eventId = info.event.id;
                    var attendees = info.event.extendedProps.attendees;
                    if (attendees) {
                        var attendeesList = attendees.join(',');
                        $('#attendeeupdate').val(attendeesList);
                    }
                    var startTime = ('0' + start.getHours()).slice(-2) + ':' + ('0' + start
                        .getMinutes()).slice(-2);
                    var endTime = ('0' + end.getHours()).slice(-2) + ':' + ('0' + end.getMinutes())
                        .slice(-2);

                    $('#starttime').val(startTime);
                    $('#endtime').val(endTime);
                    $('#eventsTitle').val(title);
                    $('#eventAction').modal('show');
                    $('#delete_event').click(function() {
                        deleteEvent(eventId, calendar);
                    });

                    $('#update_event').click(function() {
                        updateEvent(eventId, calendar, info);
                    });

                    $('#meeting').click(function() {
                        if (info.event.extendedProps.link) {
                            window.open(info.event.extendedProps.link, '_blank');
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'No meeting link available for this event'
                            });
                        }
                    });
                },
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: true
                }
            });
            calendar.render();

            function deleteEvent(id) {
                if (id) {
                    $.ajax({
                        url: '/zoom/meetings/delete',
                        method: 'POST',
                        data: {
                            meeting_id: id
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                swal.fire({
                                    icon: 'success',
                                    title: 'Hurrah...',
                                    text: response.message
                                });
                                $('#eventAction').modal('hide');
                                calendar.refetchEvents();
                                resetModal('eventAction');
                                return;
                            } else {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message
                                });
                                return;
                            }
                        }
                    });
                } else {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Event Id is required"
                    });
                    return;
                }

            }

            function updateEvent(id, calender, info) {
                var start = $('#starttime').val();
                var end = $('#endtime').val();
                var title = $('#eventsTitle').val();
                var attendeesList = $('#attendeeupdate').val();
                var email = $('select[name="email_update"]').val();

                var startDate = new Date(info.event.startStr);
                var endDate = new Date(info.event.startStr);
                if (start) startDate.setHours(start.split(":")[0], start.split(":")[1], 0);
                if (end) endDate.setHours(end.split(":")[0], end.split(":")[1], 0);
                var duration = (endDate - startDate) / (1000 * 60)

                var eventData = {
                    title: title,
                    start_time: startDate.toISOString(),
                    duration: duration,
                    attendee: attendeesList,
                    meeting_id: id,
                    email: email
                };
                $.ajax({
                    url: '/zoom/meetings/update',
                    method: 'POST',
                    data: eventData,
                    success: function(response) {
                        if (response.status == 'success') {
                            swal.fire({
                                icon: 'success',
                                title: 'Hurrah...',
                                text: response.message
                            });
                            calendar.refetchEvents();
                            $('#eventAction').modal('hide');
                            resetModal('eventAction');
                            return;
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            });
                            return;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

        });

        function resetModal(id) {
            const modalInputs = document.querySelectorAll('#' + id + ' input');
            modalInputs.forEach(input => {
                input.value = '';
            });
        }
    </script>
@endpush
