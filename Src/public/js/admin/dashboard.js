$(document).ready(function () {
    //Setup Init
    $('#dateFrom').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#str_date').attr("value"),
        useCurrent: false
    });

    //Get time startup
    var st_date = $("#dateFrom").data('DateTimePicker').date().format('YYYY-MM-DD');
    $('#dataDateFrom').val(st_date);

    //Get time when change
    $("#dateFrom").on("dp.change", function (e) {
        $('#dataDateFrom').val(e.date.format('YYYY-MM-DD'));
    });
});
