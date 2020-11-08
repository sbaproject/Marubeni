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
