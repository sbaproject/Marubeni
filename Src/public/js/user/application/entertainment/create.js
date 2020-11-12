$(document).ready(function () {
    //=======================================
    // Datetimepicker
    //=======================================
    // init
    $('#datetime').datetimepicker({
        format: 'ddd, DD/MM/YYYY HH:mm',
        defaultDate: $('#entertainment_dt').val(),
        toolbarPlacement: 'bottom',
        sideBySide: true,
        showTodayButton: true,
        showClear: true,
        useCurrent: false,
        icons: {
            clear: 'fa fa-trash',
            today: 'fa fa-clock',
        }
    });
    // show
    var datetime = $('#datetime').data("DateTimePicker").date();
    if (datetime != null) {
        $('#entertainment_dt').val(datetime.format('YYYYMMDD HH:mm'));
    }
    // change
    $("#datetime").on("dp.change", function (e) {
        if (e.date) {
            $('#entertainment_dt').val(e.date.format('YYYYMMDD HH:mm'));
        } else {
            $('#entertainment_dt').val(null);
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
    $('.file-link').on('click', function () {
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