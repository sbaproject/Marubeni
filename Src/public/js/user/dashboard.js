$(function () {
    //Date picker
    $('#dateFrom').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        maxDate: moment(),
        defaultDate: moment()
    });

    $('#dateTo').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: moment(),
        minDate: moment(),
        useCurrent: false
    });

    $("#dateFrom").on("dp.change", function (e) {
        $('#dateTo').data("DateTimePicker").minDate(e.date);
        $('#dateTo').data("DateTimePicker").date(e.date);
    });

    $("#dateTo").on("dp.change", function (e) {
        $('#dateFrom').data("DateTimePicker").maxDate(e.date);
    });

});
