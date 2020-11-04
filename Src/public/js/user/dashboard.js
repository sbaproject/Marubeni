$(document).ready(function () {
    //Setup Init
    $('#dateFrom').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        maxDate: $('#end_date').attr("value"),
        defaultDate: $('#str_date').attr("value")
    });
    $('#dateTo').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        minDate: $('#str_date').attr("value"),
        defaultDate: $('#end_date').attr("value"),
        useCurrent: false
    });

    //Get time startup
    var date = $("#dateFrom").data('DateTimePicker').date().format('YYYY-MM-DD');
    $('#dataDateFrom').val(date);
    $('#dataDateTo').val(date);

    //Get time when change
    $("#dateFrom").on("dp.change", function (e) {
        $('#dateTo').data("DateTimePicker").minDate(e.date);
        $('#dataDateFrom').val(e.date.format('YYYY-MM-DD'));
    });
    $("#dateTo").on("dp.change", function (e) {
        $('#dateFrom').data("DateTimePicker").maxDate(e.date);
        $('#dataDateTo').val(e.date.format('YYYY-MM-DD'));
    });

    // //Edit Form Status
    $('#table_list_status tbody tr td').each(function () {
        $(this).find("#status").each(function () {

            if ($(this).attr("value") == '0') {
                $(this).attr('class', 'status-apply');
                $(this).text('Applying');
            } else if ($(this).attr("value") == '-1') {
                $(this).attr('class', 'status-declined');
                $(this).text('Declined');
            } else if ($(this).attr("value") == '-2') {
                $(this).attr('class', 'status-reject');
                $(this).text('Reject');
            } else if ($(this).attr("value") == '99') {
                $(this).attr('class', 'status-completed');
                $(this).text('Completed');
            } else {
                $(this).attr('class', 'status-approval');
                $(this).text('Approval');
            }
        });
    });
});
