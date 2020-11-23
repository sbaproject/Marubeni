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
        if (e.date) {
            $('#date_from').val(e.date.format('YYYYMMDD'));
        } else {
            $('#date_from').val(null);
        }
    });
    $("#dateLeaveTo").on("dp.change", function (e) {
        $('#dateLeaveFrom').data("DateTimePicker").maxDate(e.date);
        if (e.date) {
            $('#date_to').val(e.date.format('YYYYMMDD'));
        } else {
            $('#date_to').val(null);
        }
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
        // defaultDate: $('#time_from').val(),
        useCurrent: false
    });
    $('#timeLeaveTo').datetimepicker({
        format: 'HH:mm',
        // defaultDate: $('#time_to').val(),
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
        if (e.date) {
            $('#time_day').val(e.date.format('YYYYMMDD'));
        } else {
            $('#time_day').val(null);
        }
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
        if (e.date) {
            $('#maternity_from').val(e.date.format('YYYYMMDD'));
        } else {
            $('#maternity_from').val(null);
        }
    });
    $("#maternityLeaveTo").on("dp.change", function (e) {
        $('#maternityLeaveFrom').data("DateTimePicker").maxDate(e.date);
        if (e.date) {
            $('#maternity_to').val(e.date.format('YYYYMMDD'));
        } else {
            $('#maternity_to').val(null);
        }
    });

    /**----------------------------------------------------------------------
     * 
     ----------------------------------------------------------------------*/

    if (!previewFlg) {
        settingPageByCodeLeave($('select[name="rd_code_leave"]'));
    }

    // code_leave change
    $('select[name="rd_code_leave"]').on('change', function () {
        $('#code_leave').val($(this).val());
        settingPageByCodeLeave($(this));
    });

    $('input[type=radio][name="rd_paid_type"]').on('change', function () {
        if ($(this).val() == paid_type.AL) {
            $('#days_use').removeAttr('readonly');
            $('#times_use').removeAttr('readonly');
        } else {
            $('#days_use').val('');
            $('#times_use').val('');
            $('#days_use').attr('readonly', 'readonly');
            $('#times_use').attr('readonly', 'readonly');
        }
        $('#paid_type').val($(this).val());
    });

    function settingPageByCodeLeave(codeLeaveSelector) {
        $('input[type=text][data-target="#dateLeaveFrom"]').removeAttr('readonly');
        $('input[type=text][data-target="#dateLeaveTo"]').removeAttr('readonly');
        $('input[type=text][data-target="#timeLeaveDate"]').removeAttr('readonly');
        $('#timeLeaveFrom').removeAttr('readonly');
        $('#timeLeaveTo').removeAttr('readonly');
        $('input[type=text][data-target="#maternityLeaveFrom"]').removeAttr('readonly');
        $('input[type=text][data-target="#maternityLeaveTo"]').removeAttr('readonly');
        $('input[type=radio][name="rd_paid_type"]').removeAttr('disabled');
        $('#days_use').removeAttr('readonly');
        $('#times_use').removeAttr('readonly');

        if (codeLeaveSelector.val() == code_leave.ML) {
            $('#dateLeaveFrom').data("DateTimePicker").date(null);
            $('#dateLeaveTo').data("DateTimePicker").date(null);
            $('#timeLeaveDate').data("DateTimePicker").date(null);
            $('#timeLeaveFrom').data("DateTimePicker").date(null);
            $('#timeLeaveTo').data("DateTimePicker").date(null);
            $('#days_use').val('');
            $('#times_use').val('');
            // date leave
            $('input[type=text][data-target="#dateLeaveFrom"]').attr('readonly', 'readonly');
            $('input[type=text][data-target="#dateLeaveTo"]').attr('readonly', 'readonly');
            // time leave
            $('input[type=text][data-target="#timeLeaveDate"]').attr('readonly', 'readonly');
            $('#timeLeaveFrom').attr('readonly', 'readonly');
            $('#timeLeaveTo').attr('readonly', 'readonly');
            // paid_type
            $('input[type=radio][name="rd_paid_type"]').attr('disabled', 'disabled');
            $('input[type=radio][name="rd_paid_type"]').prop('checked', false);
            $('#paid_type').val('');
            // days & times used
            $('#days_use').attr('readonly', 'readonly');
            $('#times_use').attr('readonly', 'readonly');
        } else if (codeLeaveSelector.val() != "empty") {
            $('#maternityLeaveFrom').data("DateTimePicker").date(null);
            $('#maternityLeaveTo').data("DateTimePicker").date(null);
            $('input[type=text][data-target="#maternityLeaveFrom"]').attr('readonly', 'readonly');
            $('input[type=text][data-target="#maternityLeaveTo"]').attr('readonly', 'readonly');

            if (codeLeaveSelector.val() != code_leave.SL) {
                $('input[type=radio][name="rd_paid_type"]').attr('disabled', 'disabled');
                $('input[type=radio][name="rd_paid_type"]').prop('checked', false);
                $('#paid_type').val('');
                if (codeLeaveSelector.val() != code_leave.AL) {
                    $('#days_use').attr('readonly', 'readonly');
                    $('#times_use').attr('readonly', 'readonly');
                    $('#days_use').val('');
                    $('#times_use').val('');
                }
                return;
            }

            if (codeLeaveSelector.val() == code_leave.SL) {
                if ($('input[type=radio][name="rd_paid_type"]:checked').val() != paid_type.AL){
                    $('#days_use').attr('readonly', 'readonly');
                    $('#times_use').attr('readonly', 'readonly');
                    $('#days_use').val('');
                    $('#times_use').val('');
                }
                return;
            }
        } else {
            $('#dateLeaveFrom').data("DateTimePicker").date(null);
            $('#dateLeaveTo').data("DateTimePicker").date(null);
            $('#timeLeaveDate').data("DateTimePicker").date(null);
            $('#timeLeaveFrom').data("DateTimePicker").date(null);
            $('#timeLeaveTo').data("DateTimePicker").date(null);
            $('#maternityLeaveFrom').data("DateTimePicker").date(null);
            $('#maternityLeaveTo').data("DateTimePicker").date(null);
            $('input[type=radio][name="rd_paid_type"]').prop('checked', false);
            $('#paid_type').val('');

            $('#days_use').val('');
            $('#times_use').val('');

            $('input[type=text][data-target="#dateLeaveFrom"]').attr('readonly', 'readonly');
            $('input[type=text][data-target="#dateLeaveTo"]').attr('readonly', 'readonly');
            $('input[type=text][data-target="#timeLeaveDate"]').attr('readonly', 'readonly');
            $('#timeLeaveFrom').attr('readonly', 'readonly');
            $('#timeLeaveTo').attr('readonly', 'readonly');
            $('input[type=text][data-target="#maternityLeaveFrom"]').attr('readonly', 'readonly');
            $('input[type=text][data-target="#maternityLeaveTo"]').attr('readonly', 'readonly');
            $('input[type=radio][name="rd_paid_type"]').attr('disabled', 'disabled');
            $('#days_use').attr('readonly', 'readonly');
            $('#times_use').attr('readonly', 'readonly');
        }
    }

    /**----------------------------------------------------------------------
     * Browse file
     ----------------------------------------------------------------------*/
    $('#input_file').val(null);
    $('#input_file').on('change', function (e) {
        //get the file name
        var fileName = e.target.files[0].name;
        if (fileName != '') {
            $('.file-name').html(fileName);
            $('#file_path').val(fileName);
        }
    });
    // remove file
    $('.file-remove').on('click', function () {
        $('#input_file').val(null);
        $('#file_path').val(null);
        $('.file-name').html($('.file-name').attr('place-holder'));
        $('.file-show').remove();
        $('.file-block').removeClass('d-none');
    });
    // open link attached file
    $('.file-link').on('click', function () {
        $(this).find('a')[0].click();
    });

});