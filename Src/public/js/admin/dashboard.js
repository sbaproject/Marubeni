$(document).ready(function () {
    //Setup Init
    $('#dateFrom').datetimepicker({
        format: 'DD/MM/YYYY',
        defaultDate: $('#str_date').attr("value"),
        maxDate: $('#end_date').attr("value"),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    $('#dateTo').datetimepicker({
        format: 'DD/MM/YYYY',
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

    //Set Property for Search of Type
    $("#applying").mousemove(function(){
        $('#typeApply').val(0);
    });
    $("#applying").mouseout(function(){
        $('#typeApply').val('');
    });

    $("#approval").mousemove(function(){
        $('#typeApply').val(1);
    });
    $("#approval").mouseout(function(){
        $('#typeApply').val('');
    });

    $("#declined").mousemove(function(){
        $('#typeApply').val(-1);
    });
    $("#declined").mouseout(function(){
        $('#typeApply').val('');
    });

    $("#reject").mousemove(function(){
        $('#typeApply').val(-2);
    });
    $("#reject").mouseout(function(){
        $('#typeApply').val('');
    });

    $("#completed").mousemove(function(){
        $('#typeApply').val(99);
    });
    $("#completed").mouseout(function(){
        $('#typeApply').val('');
    });

    //Set Property for Submit Form
    $("#applying").click(function(){
        $("#formSearch").attr('action', 'admin/dashboard');
        $('#formSearch').submit();
    });
    $("#approval").click(function(){
        $("#formSearch").attr('action', 'admin/dashboard');
        $('#formSearch').submit();
    });
    $("#declined").click(function(){
        $("#formSearch").attr('action', 'admin/dashboard');
        $('#formSearch').submit();
    });
    $("#reject").click(function(){
        $("#formSearch").attr('action', 'admin/dashboard');
        $('#formSearch').submit();
    });
    $("#completed").click(function(){
        $("#formSearch").attr('action', 'admin/dashboard');
        $('#formSearch').submit();
    });

    $("#btnSearch").click(function(){
        $("#formSearch").attr('action', 'admin/dashboard');
        $('#formSearch').submit();
    });

    $("#btnExcel").click(function(){
        $("#formSearch").attr('action', 'admin/exportExcel');
        $('#formSearch').submit();
        setTimeout(function () {
          $("#popup-loading").modal('hide');
        }, 100);
        
    });
});
