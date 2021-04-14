$(document).ready(function() {
    //=======================================
    // Datetimepicker
    //=======================================
    // init
    $('#instruction_date_picker').datetimepicker({
        format: 'DD/MM/YYYY',
        defaultDate: $('#under_instruction_date').val(),
        useCurrent: false,
        showTodayButton: true,
        showClear: true
    });
    // show
    var instructionDate = $('#instruction_date_picker').data("DateTimePicker").date();
    if (instructionDate != null) {
        $('#under_instruction_date').val(instructionDate.format('YYYYMMDD'));
    }
    // change
    $("#instruction_date_picker").on("dp.change", function(e) {
        if (e.date) {
            $('#under_instruction_date').val(e.date.format('YYYYMMDD'));
        } else {
            $('#under_instruction_date').val(null);
        }
    });

    //=======================================
    // Browser file
    //=======================================

    $('#input_file').val(null);
    $('#input_file').on('change', function(e) {
        //get the file name
        var fileName = e.target.files[0].name;
        if (fileName != '') {
            $('.file-name').html(fileName);
            $('#file_path').val(fileName);
        }
    });
    // remove file
    $('.file-remove').on('click', function() {
        $('#input_file').val(null);
        $('#file_path').val(null);
        $('.file-name').html($('.file-name').attr('place-holder'));
        $('.file-show').remove();
        $('.file-block').removeClass('d-none');
    });
    // open link attached file
    $('.file-link').on('click', function(e) {
        $(this).find('a')[0].click();
    });

    //=======================================
    // Itinerary & Transportation Block
    //=======================================

    // Datetimepicker of Itineraries
    console.log(_ITINERARIES);

    setTransDatePickers(_ITINERARIES);

    function setTransDatePickers(items) {
        items.forEach((item, index) => {
            $('#trans_date_picker_' + index).datetimepicker({
                format: 'DD/MM/YYYY',
                defaultDate: $('#hid_trans_date_' + index).val(),
                useCurrent: false,
                showTodayButton: true,
                showClear: true
            });
            // show
            var datePicker = $('#trans_date_picker_' + index).data("DateTimePicker").date();
            if (datePicker != null) {
                $('#hid_trans_date_' + index).val(datePicker.format('YYYYMMDD'));
            }
            // change
            $('#trans_date_picker_' + index).on("dp.change", function(e) {
                if (e.date) {
                    $('#hid_trans_date_' + index).val(e.date.format('YYYYMMDD'));
                } else {
                    $('#hid_trans_date_' + index).val(null);
                }
            });
        });
    }

    // add new transportation element
    $('#btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#itineraries_block');
        var copyElement = $('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doSettingElement();

    });
    // remove transportation element
    $(document).on("click", ".btnDelete", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doSettingElement();
    });

    function doSettingElement() {
        var itinerariesElements = $('.card-itinerary-itineraries:not(.copy)');
        itinerariesElements.each(function(index) {
            // re-order index
            $(this).find('.departure').attr('name', 'itineraries[' + index + '][departure]');
            $(this).find('.arrive').attr('name', 'itineraries[' + index + '][arrive]');
            // trans_date
            $(this).find('.hid_trans_date').attr('name', 'itineraries[' + index + '][trans_date]');
            $(this).find('.hid_trans_date').attr('id', 'hid_trans_date_' + index);
            $(this).find('.trans_date_picker').attr('id', 'trans_date_picker_' + index);
            $(this).find('.txt_trans_date_picker').attr('data-target', 'trans_date_picker_' + index);
            $(this).find('.group_trans_date_picker').attr('data-target', 'trans_date_picker_' + index);
            // always keep at least one element
            if ((itinerariesElements.length === 1 && index === 0)) {
                $(this).find('.d-delete').addClass('d-none');
            } else {
                $(this).find('.d-delete').removeClass('d-none');
            }
        });
        // make trans_date picker
        setTransDatePickers(itinerariesElements.toArray());
        // maximum is 4 blocks only
        if (itinerariesElements.length >= 4) {
            $('#btnAdd').addClass('d-none');
        } else {
            $('#btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // Print PDF
    //=======================================

    $('#btnPdf').on('click', function(e) {

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
});