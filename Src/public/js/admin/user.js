$(document).ready(function () {

    $("#btnSearch").click(function(){
        $("#formSearch").attr('action', 'admin/user');
        $('#formSearch').submit();
    });

    $("#btnExcel").click(function(){
        $("#formSearch").attr('action', 'admin/user/exportExcel');
        $('#formSearch').submit();
        setTimeout(function () {
          $("#popup-loading").modal('hide');
        }, 100);
        
    });
});
