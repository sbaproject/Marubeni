$(document).ready(function() {
    //Setup Init
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

    //Get time startup
    var date = $("#dateFrom").data('DateTimePicker').date().format('YYYYMMDD');
    $('#dataDateFrom').val(date);
    $('#dataDateTo').val(date);

    //Get time when change
    $("#dateFrom").on("dp.change", function (e) {
        $('#dateTo').data("DateTimePicker").minDate(e.date);
        $('#dataDateFrom').val(e.date.format('YYYYMMDD'));
    });
    $("#dateTo").on("dp.change", function (e) {
        $('#dateFrom').data("DateTimePicker").maxDate(e.date);
        $('#dateTo').val(e.date.format('YYYYMMDD'));
    });

    //Edit Form Status
    $('#status').each(function() {
        switch( $(this).attr("value")) {
            case '0':
                $(this).attr('class', 'status-apply');
                $(this).text('Applying');
              break;
            case '-1':
                $(this).attr('class', 'status-declined');
                $(this).text('Declined');
              break;
            case '-2':
                $(this).attr('class', 'status-reject');
                $(this).text('Reject');
              break;
            case '99':
                $(this).attr('class', 'status-completed');
                $(this).text('Completed');
              break;
            default:

          }
    });


});
