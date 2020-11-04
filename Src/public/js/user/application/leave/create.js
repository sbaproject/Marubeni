$(document).ready(function () {
    /**
     * Date of leave
     */
    // init
    $('#dateLeaveFrom').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: moment()
    });
    $('#dateLeaveTo').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        useCurrent: true
    });
    // show
    var dateFrom = $('#dateLeaveFrom').data("DateTimePicker").date();
    var dateTo = $('#dateLeaveFrom').data("DateTimePicker").date();
    $('#dateLeaveTo').data("DateTimePicker").minDate(dateFrom);
    $('#dateLeaveFrom').data("DateTimePicker").maxDate(dateTo);
    $('#date_from').val(dateFrom.format('YYYYMMDD'));
    $('#date_to').val(dateTo.format('YYYYMMDD'));
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
        defaultDate: moment()
    });
    $('#timeLeaveFrom').datetimepicker({
        format: 'HH:mm',
    });
    $('#timeLeaveTo').datetimepicker({
        format: 'HH:mm',
        useCurrent: false
    });
    // show
    var timeDay = $('#timeLeaveDate').data("DateTimePicker").date();
    var timeLeaveFrom = $('#timeLeaveFrom').data("DateTimePicker").date();
    var timeLeaveTo = $('#timeLeaveTo').data("DateTimePicker").date();
    $('#time_day').val(timeDay.format('YYYYMMDD'));
    $('#timeLeaveTo').data("DateTimePicker").minDate(timeLeaveFrom);
    $('#timeLeaveFrom').data("DateTimePicker").maxDate(timeLeaveTo);
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
        defaultDate: moment()
    });
    $('#maternityLeaveTo').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        useCurrent: false
    });
    // show
    var maternityLeaveFrom = $('#maternityLeaveFrom').data("DateTimePicker").date();
    var maternityLeaveTo = $('#maternityLeaveTo').data("DateTimePicker").date();
    $('#maternityLeaveTo').data("DateTimePicker").minDate(maternityLeaveFrom);
    $('#maternityLeaveFrom').data("DateTimePicker").maxDate(maternityLeaveTo);
    $('#maternity_from').val(maternityLeaveFrom.format('YYYYMMDD'));
    $('#maternity_to').val(maternityLeaveTo.format('YYYYMMDD'));
    // change
    $("#maternityLeaveFrom").on("dp.change", function (e) {
        $('#maternityLeaveTo').data("DateTimePicker").minDate(e.date);
        $('#maternity_from').val(e.date.format('YYYYMMDD'));
    });
    $("#maternityLeaveTo").on("dp.change", function (e) {
        $('#maternityLeaveFrom').data("DateTimePicker").maxDate(e.date);
        $('#maternity_to').val(e.date.format('YYYYMMDD'));
    });

});