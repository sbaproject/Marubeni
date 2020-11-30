$(document).ready(function () {
    //Setup Init
    $('#dateFrom').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#str_date').attr("value"),
        maxDate: $('#end_date').attr("value"),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    $('#dateTo').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#end_date').attr("value"),
        minDate: $('#str_date').attr("value"),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });

    //Get time startup
    var st_date = $("#dateFrom").data('DateTimePicker').date().format('YYYY-MM-DD');
    var en_date = $("#dateTo").data('DateTimePicker').date().format('YYYY-MM-DD');
    $('#dataDateFrom').val(st_date);
    $('#dataDateTo').val(en_date);

    //Get time when change
    $("#dateFrom").on("dp.change", function (e) {
        $('#dateTo').data("DateTimePicker").minDate(e.date);
        $('#dataDateFrom').val(e.date.format('YYYY-MM-DD'));
    });
    $("#dateTo").on("dp.change", function (e) {
        $('#dateFrom').data("DateTimePicker").maxDate(e.date);
        $('#dataDateTo').val(e.date.format('YYYY-MM-DD'));
    });
});
