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
    // Itinerary Block
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

    // add new Itinerary element
    $('#itinerary-btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#itineraries_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doItinerariesSetting();

    });
    // remove Itinerary element
    $(document).on("click", ".itinerary-btnDelete", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doItinerariesSetting();
    });

    function doItinerariesSetting() {
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
            $('#itinerary-btnAdd').addClass('d-none');
        } else {
            $('#itinerary-btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // Transportation Block
    //=======================================

    // add new Itinerary element
    $('#transportations-btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#transportations_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doTransportationSettings();

    });
    // remove Itinerary element
    $(document).on("click", ".transportations-btnDelete", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doTransportationSettings();
    });

    function doTransportationSettings() {
        var transElements = $('.card-transportations:not(.copy)');
        transElements.each(function(index) {
            // re-order index
            $(this).find('.transportations_method').attr('name', 'transportations[' + index + '][method]');
            $(this).find('.transportations_amount').attr('name', 'transportations[' + index + '][amount]');
            $(this).find('.transportations_unit').attr('name', 'transportations[' + index + '][unit]');
            $(this).find('.transportations_rate').attr('name', 'transportations[' + index + '][exchange_rate]');
            $(this).find('.transportations_note').attr('name', 'transportations[' + index + '][note]');

            // set trans number
            $(this).find('.sp_trans_no').text('A' + (index + 1));

            // always keep at least one element
            // if ((transElements.length === 1 && index === 0)) {
            //     $(this).find('.d-delete').addClass('d-none');
            // } else {
            //     $(this).find('.d-delete').removeClass('d-none');
            // }
        });
        // maximum is 10 blocks only
        if (transElements.length >= 10) {
            $('#transportations-btnAdd').addClass('d-none');
        } else {
            $('#transportations-btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // Communication Block
    //=======================================

    // add new Communication element
    $('#communications-btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#communications_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doCommunicationSettings();

    });
    // remove Communication element
    $(document).on("click", ".communications-btnDelete", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doCommunicationSettings();
    });

    function doCommunicationSettings() {
        var comElements = $('.card-communications:not(.copy)');
        comElements.each(function(index) {
            // re-order index
            $(this).find('.communications_method').attr('name', 'communications[' + index + '][method]');
            $(this).find('.communications_amount').attr('name', 'communications[' + index + '][amount]');
            $(this).find('.communications_unit').attr('name', 'communications[' + index + '][unit]');
            $(this).find('.communications_rate').attr('name', 'communications[' + index + '][exchange_rate]');
            $(this).find('.communications_note').attr('name', 'communications[' + index + '][note]');

            // set communication number
            $(this).find('.sp_com_no').text('C' + (index + 1));

            // always keep at least one element
            // if ((comElements.length === 1 && index === 0)) {
            //     $(this).find('.d-delete').addClass('d-none');
            // } else {
            //     $(this).find('.d-delete').removeClass('d-none');
            // }
        });
        // maximum is 10 blocks only
        if (comElements.length >= 10) {
            $('#communications-btnAdd').addClass('d-none');
        } else {
            $('#communications-btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // Accomodation Block
    //=======================================

    // add new Accomodation element
    $('#accomodations-btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#accomodations_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doAccomodationsettings();

    });
    // remove Accomodation element
    $(document).on("click", ".accomodations-btnDelete", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doAccomodationsettings();
    });

    function doAccomodationsettings() {
        var comElements = $('.card-accomodations:not(.copy)');
        comElements.each(function(index) {
            // re-order index
            $(this).find('.accomodations_amount').attr('name', 'accomodations[' + index + '][amount]');
            $(this).find('.accomodations_unit').attr('name', 'accomodations[' + index + '][unit]');
            $(this).find('.accomodations_rate').attr('name', 'accomodations[' + index + '][exchange_rate]');
            $(this).find('.accomodations_note').attr('name', 'accomodations[' + index + '][note]');

            // set communication number
            $(this).find('.sp_acom_no').text('B' + (index + 1));

            // always keep at least one element
            // if ((comElements.length === 1 && index === 0)) {
            //     $(this).find('.d-delete').addClass('d-none');
            // } else {
            //     $(this).find('.d-delete').removeClass('d-none');
            // }
        });
        // maximum is 10 blocks only
        if (comElements.length >= 10) {
            $('#accomodations-btnAdd').addClass('d-none');
        } else {
            $('#accomodations-btnAdd').removeClass('d-none');
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