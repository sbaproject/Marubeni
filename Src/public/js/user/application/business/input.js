$(document).ready(function () {
    //=======================================
    // Datetimepicker
    //=======================================
    // init
    $('#trip_from').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#trip_dt_from').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    $('#trip_to').datetimepicker({
        format: 'ddd, DD/MM/YYYY',
        defaultDate: $('#trip_dt_to').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    // show
    var tripFrom = $('#trip_from').data("DateTimePicker").date();
    var tripTo = $('#trip_to').data("DateTimePicker").date();
    if (tripFrom != null) {
        $('#trip_to').data("DateTimePicker").minDate(tripFrom);
        $('#trip_dt_from').val(tripFrom.format('YYYYMMDD'));
    }
    if (tripTo != null) {
        $('#trip_from').data("DateTimePicker").maxDate(tripTo);
        $('#trip_dt_to').val(tripTo.format('YYYYMMDD'));
    }
    // change
    $("#trip_from").on("dp.change", function (e) {
        $('#trip_to').data("DateTimePicker").minDate(e.date);
        if (e.date) {
            $('#trip_dt_from').val(e.date.format('YYYYMMDD'));
        } else {
            $('#trip_dt_from').val(null);
        }
    });
    $("#trip_to").on("dp.change", function (e) {
        $('#trip_from').data("DateTimePicker").maxDate(e.date);
        if (e.date) {
            $('#trip_dt_to').val(e.date.format('YYYYMMDD'));
        } else {
            $('#trip_dt_to').val(null);
        }
    });

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
    $('.file-link').on('click', function (e) {
        $(this).find('a')[0].click();
    });

    //=======================================
    // Itinerary & Transportation Block
    //=======================================

    // add new transportation element
    $('#btnAdd').on('click', function (e) {

        e.preventDefault();

        var mainBlock = $('#transport_block');
        var copyElement = $('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doSettingElement();

    });
    // remove transportation element
    $(document).on("click", ".btnDelete", function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doSettingElement();
    });

    function doSettingElement() {
        var transportElements = $('.card-itinerary-transport:not(.copy)');
        transportElements.each(function (index) {
            // re-order index
            $(this).find('.departure').attr('name', 'trans[' + index + '][departure]');
            $(this).find('.arrive').attr('name', 'trans[' + index + '][arrive]');
            $(this).find('.method').attr('name', 'trans[' + index + '][method]');
            // always keep at least one element
            if (transportElements.length === 1 && index === 0) {
                $(this).find('.d-delete').addClass('d-none');
            } else {
                $(this).find('.d-delete').removeClass('d-none');
            }
        });
    }
});