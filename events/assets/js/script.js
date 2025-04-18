$(document).ready(function() {
    // Initialize the FullCalendar
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today', // Navigation buttons on the left
            center: 'title', // Title in the center
            right: 'month,agendaWeek,agendaDay' // View options on the right
        },
        events: 'fetch_events.php', // URL to fetch events
        selectable: true, // Allow dates to be selectable
        selectHelper: true, // Show a placeholder when selecting
        select: function(start, end) {
            // Clear the form inputs
            $('#eventId').val('');
            $('#eventTitle').val('');
            $('#startTime').val(moment(start).format("YYYY-MM-DD HH:mm:ss"));
            $('#endTime').val(moment(end).format("YYYY-MM-DD HH:mm:ss"));
            // Show the modal for adding a new event
            $('#eventModal').modal('show');
        },
        editable: true, // Allow events to be editable
        eventDrop: function(event) {
            // Update the event's new start and end times
            var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
            var end = moment(event.end).format("YYYY-MM-DD HH:mm:ss");
            $.ajax({
                url: 'edit_event.php', // URL to update event
                data: {
                    id: event.id, // Event ID
                    title: event.title, // Event title
                    start: start, // New start time
                    end: end // New end time
                },
                type: "POST",
                success: function(data) {
                    alert("Event updated successfully"); // Alert on success
                }
            });
        },
        eventClick: function(event) {
            // Populate the form inputs with the event data
            $('#eventId').val(event.id);
            $('#eventTitle').val(event.title);
            $('#startTime').val(moment(event.start).format("YYYY-MM-DD HH:mm:ss"));
            $('#endTime').val(moment(event.end).format("YYYY-MM-DD HH:mm:ss"));
            // Show the modal for editing the event
            $('#eventModal').modal('show');
        }
    });

    // Initialize datetimepicker for start and end time inputs
    $('.datetimepicker').datetimepicker({
        format: 'Y-MM-DD HH:mm:ss', // Date-time format
        useCurrent: false, // Do not auto-select current date
        icons: {
            time: 'custom-time-icon', // Custom class for time icon
            date: 'custom-date-icon', // Custom class for date icon
            up: 'custom-up-icon', // Custom class for increment
            down: 'custom-down-icon', // Custom class for decrement
            previous: 'custom-previous-icon', // Custom class for previous
            next: 'custom-next-icon', // Custom class for next
            today: 'custom-today-icon', // Custom class for today
            clear: 'custom-clear-icon', // Custom class for clear
            close: 'custom-close-icon' // Custom class for close
        },
    });

    // After initializing datetimepicker, add the text "Výber času"
    $('.bootstrap-datetimepicker-widget .timepicker .timepicker-picker').prepend(
        '<div class="timepicker-title">Výber času</div>'
    );

    // Handle form submission for adding/editing events
    $('#eventForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#eventId').val(); // Event ID (if editing)
        var title = $('#eventTitle').val(); // Event title
        var start = $('#startTime').val(); // Start time
        var end = $('#endTime').val(); // End time

        // Determine whether to add or edit event
        var url = id ? 'edit_event.php' : 'add_event.php';
        var data = id ? { id: id, title: title, start: start, end: end } : { title: title, start: start, end: end };

        $.ajax({
            url: url,
            data: data,
            type: "POST",
            success: function(data) {
                $('#calendar').fullCalendar('refetchEvents'); // Refresh events
                $('#eventModal').modal('hide'); // Hide the modal
                alert(id ? 'Udalosť úspešne upravená' : 'Udalosť úspešne pridaná'); // Alert on success
            }
        });
    });

    // Handle event deletion
    $('#deleteEvent').on('click', function() {
        var id = $('#eventId').val(); // Event ID
        if (id && confirm("Skutočne chcete odstrániť túto udalosť?")) { // Confirm deletion
            $.ajax({
                url: 'delete_event.php', // URL to delete event
                data: { id: id }, // Event ID to delete
                type: "POST",
                success: function(data) {
                    $('#calendar').fullCalendar('removeEvents', id); // Remove event from calendar
                    $('#eventModal').modal('hide'); // Hide the modal
                    alert("Udalosť úspešne odstránená"); // Alert on success
                }
            });
        }
    });
});
