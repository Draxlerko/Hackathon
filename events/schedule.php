<?php
include 'templates/header.php';
include 'templates/navbar.php';
include 'includes/functions.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-*">
            <h2>Event Calendar</h2>
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Modal for adding/editing events -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Pridať/Upraviť udalosť</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <input type="hidden" id="eventId">
                    <div class="form-group">
                        <label for="eventTitle" style="font-weight:700;">Názov udalosti</label>
                        <input type="text" class="form-control" id="eventTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="startTime" style="font-weight:700;" >Dátum začiatku</label>
                        <input type="text" class="form-control datetimepicker" id="startTime" required>
                    </div>
                    <div class="form-group">
                        <label for="endTime" style="font-weight:700;" >Dátum konca</label>
                        <input type="text" class="form-control datetimepicker" id="endTime" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="font-weight:500">Uložiť zmeny</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent" style="font-weight:500">Odstrániť udalosť</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>