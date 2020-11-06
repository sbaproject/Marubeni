$(document).ready(function () {
    /**
     * Date of leave
     */
    // init
    $('#dateLeaveFrom').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#date_from').val(),
        useCurrent: false
    });
    $('#dateLeaveTo').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#date_to').val(),
        useCurrent: false
    });
    // show
    var dateFrom = $('#dateLeaveFrom').data("DateTimePicker").date();
    var dateTo = $('#dateLeaveTo').data("DateTimePicker").date();
    if (dateFrom != null) {
        $('#dateLeaveTo').data("DateTimePicker").minDate(dateFrom);
        $('#date_from').val(dateFrom.format('YYYYMMDD'));
    }
    if (dateTo != null) {
        $('#dateLeaveFrom').data("DateTimePicker").maxDate(dateTo);
        $('#date_to').val(dateTo.format('YYYYMMDD'));
    }
    // change
    $("#dateLeaveFrom").on("dp.change", function (e) {
        $('#dateLeaveTo').data("DateTimePicker").minDate(e.date);
        $('#date_from').val(e.date.format('YYYYMMDD'));
    });
    $("#dateLeaveTo").on("dp.change", function (e) {
        $('#dateLeaveFrom').data("DateTimePicker").maxDate(e.date);
        $('#date_to').val(e.date.format('YYYYMMDD'));
    });

    /**
     * Time of leave
     */
    // init
    $('#timeLeaveDate').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#time_day').val(),
        useCurrent: false
    });
    $('#timeLeaveFrom').datetimepicker({
        format: 'HH:mm',
        defaultDate: $('#time_from').val(),
        useCurrent: false
    });
    $('#timeLeaveTo').datetimepicker({
        format: 'HH:mm',
        defaultDate: $('#time_to').val(),
        useCurrent: false
    });
    // show
    var timeDay = $('#timeLeaveDate').data("DateTimePicker").date();
    var timeLeaveFrom = $('#timeLeaveFrom').data("DateTimePicker").date();
    var timeLeaveTo = $('#timeLeaveTo').data("DateTimePicker").date();
    if (timeDay != null) {
        $('#time_day').val(timeDay.format('YYYYMMDD'));
    }
    if (timeLeaveFrom != null) {
        $('#timeLeaveTo').data("DateTimePicker").minDate(timeLeaveFrom);
    }
    if (timeLeaveTo != null) {
        $('#timeLeaveFrom').data("DateTimePicker").maxDate(timeLeaveTo);
    }
    // change
    $("#timeLeaveDate").on("dp.change", function (e) {
        $('#time_day').val(e.date.format('YYYYMMDD'));
    });
    $("#timeLeaveFrom").on("dp.change", function (e) {
        $('#timeLeaveTo').data("DateTimePicker").minDate(e.date);
    });
    $("#timeLeaveTo").on("dp.change", function (e) {
        $('#timeLeaveFrom').data("DateTimePicker").maxDate(e.date);
    });

    /**
     * Maternity of Leave
     */

    //init
    $('#maternityLeaveFrom').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#maternity_from').val(),
        useCurrent: false
    });
    $('#maternityLeaveTo').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#maternity_to').val(),
        useCurrent: false
    });
    // show
    var maternityLeaveFrom = $('#maternityLeaveFrom').data("DateTimePicker").date();
    var maternityLeaveTo = $('#maternityLeaveTo').data("DateTimePicker").date();
    if (maternityLeaveFrom != null) {
        $('#maternityLeaveTo').data("DateTimePicker").minDate(maternityLeaveFrom);    
        $('#maternity_from').val(maternityLeaveFrom.format('YYYYMMDD'));
    }
    if (maternityLeaveTo != null) {
        $('#maternityLeaveFrom').data("DateTimePicker").maxDate(maternityLeaveTo);
        $('#maternity_to').val(maternityLeaveTo.format('YYYYMMDD'));
    }
    // change
    $("#maternityLeaveFrom").on("dp.change", function (e) {
        $('#maternityLeaveTo').data("DateTimePicker").minDate(e.date);
        $('#maternity_from').val(e.date.format('YYYYMMDD'));
    });
    $("#maternityLeaveTo").on("dp.change", function (e) {
        $('#maternityLeaveFrom').data("DateTimePicker").maxDate(e.date);
        $('#maternity_to').val(e.date.format('YYYYMMDD'));
    });

    /**----------------------------------------------------------------------
     * Brows file
     ----------------------------------------------------------------------*/
    $('#file_path').on('change', function () {
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        if (fileName != '') {
            $(this).next('.custom-file-label').html(fileName);
        }
    })

});