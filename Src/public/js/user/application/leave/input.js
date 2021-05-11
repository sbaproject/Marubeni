$(document).ready(function () {
    //=======================================
    // Datetimpicker
    //=======================================

    $('#dateLeaveFrom').datetimepicker({
        format: 'DD/MM/YYYY',
        defaultDate: $('#date_from').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    $('#dateLeaveTo').datetimepicker({
        format: 'DD/MM/YYYY',
        defaultDate: $('#date_to').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    // show
    var dateFrom = $('#dateLeaveFrom').data("DateTimePicker").date();
    var dateTo = $('#dateLeaveTo').data("DateTimePicker").date();
    if (dateFrom != null) {
        $('#dateLeaveTo').data("DateTimePicker").minDate(dateFrom);
        $('#date_from').val(dateFrom.format('YYYYMMDD'));
    }
    if (dateTo != null) {
        // $('#dateLeaveFrom').data("DateTimePicker").maxDate(dateTo);
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
        format: 'DD/MM/YYYY',
        defaultDate: $('#time_day').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    $('#timeLeaveFrom').datetimepicker({
        format: 'HH:mm',
        // defaultDate: $('#time_from').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true,
        sbaDayOnly: false
    });
    $('#timeLeaveTo').datetimepicker({
        format: 'HH:mm',
        // defaultDate: $('#time_to').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true,
        sbaDayOnly: false
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
        format: 'DD/MM/YYYY',
        defaultDate: $('#maternity_from').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    $('#maternityLeaveTo').datetimepicker({
        format: 'DD/MM/YYYY',
        defaultDate: $('#maternity_to').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
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

    //=======================================
    // Cleave input (formatting inputs)
    //=======================================

    new Cleave('.days_use', {
        numericOnly: true,
        blocks: [2],
        onValueChanged: function (e) {
            $('[name="days_use"]').val(e.target.rawValue);
        }
    });

    // new Cleave('.times_use', {
    //     numericOnly: true,
    //     blocks: [2],
    //     onValueChanged: function (e) {
    //         $('[name="times_use"]').val(e.target.rawValue);
    //     }
    // });

    new Cleave('.times_use', {
        // stripLeadingZeroes: true,
        // numericOnly:true,
        // delimiters: [','],
        // blocks: [3, 3, 3],
        numeral: true,
        numeralDecimalScale: 0,
        // numeralThousandsGroupStyle: 'thousand',
        numeralPositiveOnly: true,
        stripLeadingZeroes: true,
        onValueChanged: function (e) {
            let maxLength = $($(this)[0].element).attr('max-number');
            let maxValue = $($(this)[0].element).attr('max-value');
            if (e.target.rawValue.length > parseInt(maxLength) || parseInt(e.target.rawValue) > parseInt(maxValue)) {
                this.setRawValue(this.lastInputValue);
            } else {
                $('[name="times_use"]').val(e.target.rawValue);
            }
        }
    });

    //=======================================
    // Subsequen changes
    //=======================================
    $('[name="cb_subsequent"]').on('change', function () {
        if ($(this).prop('checked')) {
            $('#subsequent').val(1);
        } else {
            $('#subsequent').val(0);
        }
    });
    $('#subsequent').val($('[name="cb_subsequent"]').prop('checked') ? 1 : 0);

    //=======================================
    // Disabled or Enabled inputs by CodeLeave
    //=======================================

    if (!previewFlg) {
        settingPageByCodeLeave($('[name="rd_code_leave"]'));
    }

    // code_leave was changed
    $('[name="rd_code_leave"]').on('change', function () {
        $('#code_leave').val($(this).val());
        settingPageByCodeLeave($(this));
    });

    $('[name="rd_paid_type"]').on('change', function () {
        if ($(this).val() == paid_type.AL) {
            $('#txt_days_use').removeAttr('readonly');
            $('#txt_times_use').removeAttr('readonly');
            $('#rq_take_this_time').removeClass('d-none');
        } else {
            $('#txt_days_use').val('');
            $('#txt_times_use').val('');
            $('[name="days_use"]').val('');
            $('[name="times_use"]').val('');
            $('#txt_days_use').attr('readonly', 'readonly');
            $('#txt_times_use').attr('readonly', 'readonly');
            $('#rq_take_this_time').addClass('d-none');
        }
        $('#paid_type').val($(this).val());
    });

    function settingPageByCodeLeave(codeLeaveSelector) {

        $('[data-target="#dateLeaveFrom"]').removeAttr('readonly');
        $('[data-target="#dateLeaveTo"]').removeAttr('readonly');
        $('[data-target="#timeLeaveDate"]').removeAttr('readonly');
        $('[data-target="#maternityLeaveFrom"]').removeAttr('readonly');
        $('[data-target="#maternityLeaveTo"]').removeAttr('readonly');
        $('[name="rd_paid_type"]').removeAttr('disabled');
        $('#fs_paid_type').removeAttr('disabled');

        $('#rq_paid_type').removeClass('d-none');
        $('#rq_date_leave').removeClass('d-none');
        $('#rq_maternity_leave').removeClass('d-none');
        $('#rq_take_this_time').removeClass('d-none');

        $('#timeLeaveFrom').removeAttr('readonly');
        $('#timeLeaveTo').removeAttr('readonly');
        $('#txt_days_use').removeAttr('readonly');
        $('#txt_times_use').removeAttr('readonly');

        if (codeLeaveSelector.val() == code_leave.ML) {
            $('#dateLeaveFrom').data("DateTimePicker").date(null);
            $('#dateLeaveTo').data("DateTimePicker").date(null);
            $('#timeLeaveDate').data("DateTimePicker").date(null);
            $('#timeLeaveFrom').data("DateTimePicker").date(null);
            $('#timeLeaveTo').data("DateTimePicker").date(null);
            $('#txt_days_use').val('');
            $('#txt_times_use').val('');
            $('[name="days_use"]').val('');
            $('[name="times_use"]').val('');
            // date leave
            $('[data-target="#dateLeaveFrom"]').attr('readonly', 'readonly');
            $('[data-target="#dateLeaveTo"]').attr('readonly', 'readonly');
            $('#rq_date_leave').addClass('d-none');
            // time leave
            $('[data-target="#timeLeaveDate"]').attr('readonly', 'readonly');
            $('#timeLeaveFrom').attr('readonly', 'readonly');
            $('#timeLeaveTo').attr('readonly', 'readonly');
            // paid_type
            $('#fs_paid_type').attr('disabled', 'disabled');
            $('[name="rd_paid_type"]').attr('disabled', 'disabled');
            $('[name="rd_paid_type"]').prop('checked', false);
            $('#rq_paid_type').addClass('d-none');
            $('#paid_type').val('');
            // days & times used
            $('#txt_days_use').attr('readonly', 'readonly');
            $('#txt_times_use').attr('readonly', 'readonly');
            $('#rq_take_this_time').addClass('d-none');

        } else if (codeLeaveSelector.val() != "empty") {

            $('#maternityLeaveFrom').data("DateTimePicker").date(null);
            $('#maternityLeaveTo').data("DateTimePicker").date(null);
            $('[data-target="#maternityLeaveFrom"]').attr('readonly', 'readonly');
            $('[data-target="#maternityLeaveTo"]').attr('readonly', 'readonly');
            $('#rq_maternity_leave').addClass('d-none');

            if (codeLeaveSelector.val() != code_leave.SL) {
                $('#fs_paid_type').attr('disabled', 'disabled');
                $('[name="rd_paid_type"]').attr('disabled', 'disabled');
                $('[name="rd_paid_type"]').prop('checked', false);
                $('#rq_paid_type').addClass('d-none');
                $('#paid_type').val('');
                if (codeLeaveSelector.val() != code_leave.AL) {
                    $('#txt_days_use').attr('readonly', 'readonly');
                    $('#txt_times_use').attr('readonly', 'readonly');
                    $('#rq_take_this_time').addClass('d-none');
                    $('#txt_days_use').val('');
                    $('#txt_times_use').val('');
                    $('[name="days_use"]').val('');
                    $('[name="times_use"]').val('');
                }
                return;
            }

            if (codeLeaveSelector.val() == code_leave.SL) {
                if ($('[name="rd_paid_type"]:checked').val() != paid_type.AL) {
                    $('#txt_days_use').attr('readonly', 'readonly');
                    $('#txt_times_use').attr('readonly', 'readonly');
                    $('#rq_take_this_time').addClass('d-none');
                    $('#txt_days_use').val('');
                    $('#txt_times_use').val('');
                    $('[name="days_use"]').val('');
                    $('[name="times_use"]').val('');
                }
                $('#rq_paid_type').removeClass('d-none');
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
            $('[name="rd_paid_type"]').prop('checked', false);

            $('#paid_type').val('');
            $('#txt_days_use').val('');
            $('#txt_times_use').val('');
            $('[name="days_use"]').val('');
            $('[name="times_use"]').val('');

            $('[data-target="#dateLeaveFrom"]').attr('readonly', 'readonly');
            $('[data-target="#dateLeaveTo"]').attr('readonly', 'readonly');
            $('[data-target="#timeLeaveDate"]').attr('readonly', 'readonly');
            $('[data-target="#maternityLeaveFrom"]').attr('readonly', 'readonly');
            $('[data-target="#maternityLeaveTo"]').attr('readonly', 'readonly');
            $('[name="rd_paid_type"]').attr('disabled', 'disabled');
            $('#fs_paid_type').attr('disabled', 'disabled');

            $('#rq_paid_type').addClass('d-none');
            $('#rq_date_leave').addClass('d-none');
            $('#rq_maternity_leave').addClass('d-none');
            $('#rq_take_this_time').addClass('d-none');

            $('#timeLeaveFrom').attr('readonly', 'readonly');
            $('#timeLeaveTo').attr('readonly', 'readonly');
            $('#txt_days_use').attr('readonly', 'readonly');
            $('#txt_times_use').attr('readonly', 'readonly');
        }
    }

    //=======================================
    // Browser file
    //=======================================
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

    //=======================================
    // Print PDF
    //=======================================

    $('#btnPdf').on('click', function (e) {

        e.preventDefault();

        // not show loading icon
        showLoadingFlg = false;

        let form = e.currentTarget.form;
        let buttonName = $(this).val();
        let hidSubmit = '<input type="hidden" name="' + buttonName + '" value="' + buttonName + '" />';

        $(form).append(hidSubmit);
        form.submit();
        $('[name="pdf"]').remove();
    });

    $('#txt_days_use').on('keyup', function () {

        let entitled = parseInt($('#entitled').val());
        let used_days = parseInt($('#used_days').val());
        let used_time = parseInt($('#used_time').val());
        let total_time_can_use = (entitled * 8) - ((used_days * 8) + used_time);

        let day_use = parseInt(this.value);
        let time_use = parseInt($('#txt_times_use').val());
        if (isNaN(day_use)) { day_use = 0; }
        if (isNaN(time_use)) { time_use = 0; }
        let total_want_use = (day_use * 8) + time_use;

        let time_remain = total_time_can_use - total_want_use;

        if (time_remain < 0) {

            time_remain = 0;
            let numDayMax = Math.floor(total_time_can_use / 8);
            let numTimeMax = total_time_can_use % 8;
            if (isNaN(numDayMax)) { numDayMax = 0; }
            if (isNaN(numTimeMax)) { numTimeMax = 0; }
            $('#txt_days_use').val(numDayMax);
            $('#txt_times_use').val(numTimeMax);
        }

        let numDay = Math.floor(time_remain / 8);
        let numTime = time_remain % 8;
        if (isNaN(numDay)) { numDay = 0; }
        if (isNaN(numTime)) { numTime = 0; }

        $('#remaining_days').val(numDay);
        $('#remaining_hours').val(numTime);
    });

    $('#txt_times_use').on('keyup', function () {

        let entitled = parseInt($('#entitled').val());
        let used_days = parseInt($('#used_days').val());
        let used_time = parseInt($('#used_time').val());
        let total_time_can_use = (entitled * 8) - ((used_days * 8) + used_time);

        let day_use = parseInt($('#txt_days_use').val());
        let time_use = parseInt(this.value);
        if (isNaN(day_use)) { day_use = 0; }
        if (isNaN(time_use)) { time_use = 0; }
        let total_want_use = (day_use * 8) + time_use;

        let time_remain = total_time_can_use - total_want_use;

        if (time_remain < 0) {

            time_remain = 0;
            let numDayMax = Math.floor(total_time_can_use / 8);
            let numTimeMax = total_time_can_use % 8;
            if (isNaN(numDayMax)) { numDayMax = 0; }
            if (isNaN(numTimeMax)) { numTimeMax = 0; }
            $('#txt_days_use').val(numDayMax);
            $('#txt_times_use').val(numTimeMax);
        }

        let numDay = Math.floor(time_remain / 8);
        let numTime = time_remain % 8;
        if (isNaN(numDay)) { numDay = 0; }
        if (isNaN(numTime)) { numTime = 0; }

        $('#remaining_days').val(numDay);
        $('#remaining_hours').val(numTime);
    });

    setValueDaysUseLoadError();
});

function setValueDaysUseLoadError() {
    let entitled = parseInt($('#entitled').val());
    let used_days = parseInt($('#used_days').val());
    let used_time = parseInt($('#used_time').val());
    let total_time_can_use = (entitled * 8) - ((used_days * 8) + used_time);

    let day_use = parseInt($('#txt_days_use').val());
    let time_use = parseInt($('#txt_times_use').val());
    if (isNaN(day_use)) { day_use = 0; }
    if (isNaN(time_use)) { time_use = 0; }
    let total_want_use = (day_use * 8) + time_use;

    let time_remain = total_time_can_use - total_want_use;

    if (time_remain < 0) {

        time_remain = 0;
        let numDayMax = Math.floor(total_time_can_use / 8);
        let numTimeMax = total_time_can_use % 8;
        if (isNaN(numDayMax)) { numDayMax = 0; }
        if (isNaN(numTimeMax)) { numTimeMax = 0; }
        $('#txt_days_use').val(numDayMax);
        $('#txt_times_use').val(numTimeMax);
    }

    let numDay = Math.floor(time_remain / 8);
    let numTime = time_remain % 8;
    if (isNaN(numDay)) { numDay = 0; }
    if (isNaN(numTime)) { numTime = 0; }

    $('#remaining_days').val(numDay);
    $('#remaining_hours').val(numTime);
}