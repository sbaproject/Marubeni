$(document).ready(function() {
    //=======================================
    // Datetimepicker
    //=======================================
    // init
    // $('#instruction_date_picker').datetimepicker({
    //     format: 'DD/MM/YYYY',
    //     defaultDate: $('#under_instruction_date').val(),
    //     useCurrent: false,
    //     showTodayButton: true,
    //     showClear: true
    // });
    // // show
    // var instructionDate = $('#instruction_date_picker').data("DateTimePicker").date();
    // if (instructionDate != null) {
    //     $('#under_instruction_date').val(instructionDate.format('YYYYMMDD'));
    // }
    // // change
    // $("#instruction_date_picker").on("dp.change", function(e) {
    //     if (e.date) {
    //         $('#under_instruction_date').val(e.date.format('YYYYMMDD'));
    //     } else {
    //         $('#under_instruction_date').val(null);
    //     }
    // });

    //=======================================
    // Cleave input (formatting inputs)
    //=======================================

    makeCleaveInputs();

    makeCleavePercent();

    // Number of days
    new Cleave('.number_of_days', {
        numericOnly: true,
        blocks: [3]
    });

    // Percent
    function makeCleavePercent() {
        $('.chargedbys_percent').each(function(index, element) {
            new Cleave(element, {
                numericOnly: true,
                blocks: [3]
            });
        });
    }

    function makeCleaveInputs() {
        // amount input
        $('.amount').each(function(index, element) {
            new Cleave(element, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        });
        // rate input
        $('.rate').each(function(index, element) {
            new Cleave(element, {
                numeral: true,
                numeralDecimalScale: 0,
                numeralThousandsGroupStyle: 'thousand',
                numeralPositiveOnly: true,
            });
        });
    }

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
        if (items == null) {
            setDatePicker(0);
            return;
        }
        items.forEach((item, index) => {
            setDatePicker(index);
        });
    }

    function setDatePicker(index) {
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
    // Charged Bys Block
    //=======================================

    // add new Transportation element
    $('#chargedbys-btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#chargedbys_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doChargedBySettings();

    });
    // remove Transportation element
    $(document).on("click", ".chargedbys-btnDelete", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doChargedBySettings();
    });

    function doChargedBySettings() {
        var otherFeeElements = $('.card-chargedbys:not(.copy)');
        otherFeeElements.each(function(index) {
            // re-order index
            $(this).find('.chargedbys_department').attr('name', 'chargedbys[' + index + '][department]');
            $(this).find('.chargedbys_percent').attr('name', 'chargedbys[' + index + '][percent]');

            // always keep at least one element
            if ((otherFeeElements.length === 1 && index === 0)) {
                $(this).find('.d-delete').addClass('d-none');
            } else {
                $(this).find('.d-delete').removeClass('d-none');
            }
        });

        // make cleave inputs
        // Percent
        makeCleavePercent();

        // maximum is 3 blocks only
        if (otherFeeElements.length >= 3) {
            $('#chargedbys-btnAdd').addClass('d-none');
        } else {
            $('#chargedbys-btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // Transportation Block
    //=======================================

    // add new Transportation element
    $('#transportations-btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#transportations_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doTransportationSettings();

    });
    // remove Transportation element
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
            $(this).find('.transportations_amount').attr('data-target', 'transportations[' + index + '][exchange_rate]');
            $(this).find('.transportations_amount').addClass('amount');
            $(this).find('.transportations_amount').addClass('sync_total');
            $(this).find('.transportations_unit').attr('name', 'transportations[' + index + '][unit]');
            $(this).find('.transportations_unit').attr('data-target', 'transportations[' + index + '][exchange_rate]');
            $(this).find('.transportations_rate').addClass('sync_total');
            $(this).find('.transportations_rate').attr('name', 'transportations[' + index + '][exchange_rate]');
            $(this).find('.transportations_rate').attr('data-target', 'transportations[' + index + '][amount]');
            $(this).find('.transportations_rate').addClass('rate');
            $(this).find('.transportations_note').attr('name', 'transportations[' + index + '][note]');

            // set trans number
            $(this).find('.sp_trans_no').text('A' + (index + 1));

            // Disabled / Enabled[Rate] depends on[Currency]
            disabledRateBySelectUnit($(this).find('.transportations_unit'));

            // always keep at least one element
            // if ((transElements.length === 1 && index === 0)) {
            //     $(this).find('.d-delete').addClass('d-none');
            // } else {
            //     $(this).find('.d-delete').removeClass('d-none');
            // }
        });

        // make cleave inputs
        makeCleaveInputs();

        // re-calculate total expenses
        calculateTotalExpenses();

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
            $(this).find('.communications_amount').attr('data-target', 'communications[' + index + '][exchange_rate]');
            $(this).find('.communications_amount').addClass('amount');
            $(this).find('.communications_amount').addClass('sync_total');
            $(this).find('.communications_unit').attr('name', 'communications[' + index + '][unit]');
            $(this).find('.communications_unit').attr('data-target', 'communications[' + index + '][exchange_rate]');
            $(this).find('.communications_rate').addClass('sync_total');
            $(this).find('.communications_rate').attr('name', 'communications[' + index + '][exchange_rate]');
            $(this).find('.communications_rate').attr('data-target', 'communications[' + index + '][amount]');
            $(this).find('.communications_rate').addClass('rate');
            $(this).find('.communications_note').attr('name', 'communications[' + index + '][note]');

            // set communication number
            $(this).find('.sp_com_no').text('C' + (index + 1));

            // Disabled / Enabled[Rate] depends on[Currency]
            disabledRateBySelectUnit($(this).find('.communications_unit'));

            // always keep at least one element
            // if ((comElements.length === 1 && index === 0)) {
            //     $(this).find('.d-delete').addClass('d-none');
            // } else {
            //     $(this).find('.d-delete').removeClass('d-none');
            // }
        });

        // make cleave inputs
        makeCleaveInputs();

        // re-calculate total expenses
        calculateTotalExpenses();

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
            $(this).find('.accomodations_amount').addClass('sync_total');
            $(this).find('.accomodations_amount').attr('data-target', 'accomodations[' + index + '][exchange_rate]');
            $(this).find('.accomodations_amount').addClass('amount');
            $(this).find('.accomodations_unit').attr('name', 'accomodations[' + index + '][unit]');
            $(this).find('.accomodations_unit').attr('data-target', 'accomodations[' + index + '][exchange_rate]');
            $(this).find('.accomodations_rate').addClass('sync_total');
            $(this).find('.accomodations_rate').attr('name', 'accomodations[' + index + '][exchange_rate]');
            $(this).find('.accomodations_rate').attr('data-target', 'accomodations[' + index + '][amount]');
            $(this).find('.accomodations_rate').addClass('rate');
            $(this).find('.accomodations_note').attr('name', 'accomodations[' + index + '][note]');

            // set communication number
            $(this).find('.sp_acom_no').text('B' + (index + 1));

            // Disabled / Enabled[Rate] depends on[Currency]
            disabledRateBySelectUnit($(this).find('.accomodations_unit'));

            // always keep at least one element
            // if ((comElements.length === 1 && index === 0)) {
            //     $(this).find('.d-delete').addClass('d-none');
            // } else {
            //     $(this).find('.d-delete').removeClass('d-none');
            // }
        });

        // make cleave inputs
        makeCleaveInputs();

        // re-calculate total expenses
        calculateTotalExpenses();

        // maximum is 10 blocks only
        if (comElements.length >= 10) {
            $('#accomodations-btnAdd').addClass('d-none');
        } else {
            $('#accomodations-btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // OtherFees Block
    //=======================================

    // add new Accomodation element
    $('#otherfees-btnAdd').on('click', function(e) {

        e.preventDefault();

        var mainBlock = $('#otherfees_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        mainBlock.append(copyElement);

        doOtherfeesettings();

    });
    // remove Accomodation element
    $(document).on("click", ".otherfees-btnDelete", function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        doOtherfeesettings();
    });

    function doOtherfeesettings() {
        var comElements = $('.card-otherfees:not(.copy)');
        comElements.each(function(index) {
            // re-order index
            $(this).find('.otherfees_amount').attr('name', 'otherfees[' + index + '][amount]');
            $(this).find('.otherfees_amount').addClass('sync_total');
            $(this).find('.otherfees_amount').attr('data-target', 'otherfees[' + index + '][exchange_rate]');
            $(this).find('.otherfees_amount').addClass('amount');
            $(this).find('.otherfees_unit').attr('name', 'otherfees[' + index + '][unit]');
            $(this).find('.otherfees_unit').attr('data-target', 'otherfees[' + index + '][exchange_rate]');
            $(this).find('.otherfees_rate').addClass('sync_total');
            $(this).find('.otherfees_rate').attr('name', 'otherfees[' + index + '][exchange_rate]');
            $(this).find('.otherfees_rate').attr('data-target', 'otherfees[' + index + '][amount]');
            $(this).find('.otherfees_rate').addClass('rate');
            $(this).find('.otherfees_note').attr('name', 'otherfees[' + index + '][note]');

            // set communication number
            $(this).find('.sp_acom_no').text('D' + (index + 1));

            // Disabled / Enabled[Rate] depends on[Currency]
            disabledRateBySelectUnit($(this).find('.otherfees_unit'));

            // always keep at least one element
            // if ((comElements.length === 1 && index === 0)) {
            //     $(this).find('.d-delete').addClass('d-none');
            // } else {
            //     $(this).find('.d-delete').removeClass('d-none');
            // }
        });

        // make cleave inputs
        makeCleaveInputs();

        // re-calculate total expenses
        calculateTotalExpenses();

        // maximum is 10 blocks only
        if (comElements.length >= 10) {
            $('#otherfees-btnAdd').addClass('d-none');
        } else {
            $('#otherfees-btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // Disabled/Enabled [Rate] depends on [Currency]
    //=======================================
    $(document).on('change', '.select_unit', function() {
        disabledRateBySelectUnit($(this));
        calculateTotalExpenses();
    });

    $('.select_unit').each(function(index) {
        disabledRateBySelectUnit($(this));
    });

    function disabledRateBySelectUnit(unitSelector) {

        let rateSelector = $('[name="' + unitSelector.attr('data-target') + '"]');

        if (unitSelector.val() == '' || unitSelector.val() == 'VND') {
            rateSelector.val('');
            rateSelector.attr('readonly', 'readonly');
            rateSelector.parent().find('span .required').addClass('d-none');
            return;
        }

        rateSelector.removeAttr('readonly');
        rateSelector.parent().find('span .required').removeClass('d-none');
    }

    //=======================================
    // Calculate Total Expenses
    //=======================================
    var totalExpenses = 0;

    $(document).on('change', '.sync_total', function() {
        calculateTotalExpenses();
    });

    $('#refresh-total').on('click', function(e) {
        calculateTotalExpenses();
        return false;
    });

    // re-calculate total expenses
    calculateTotalExpenses();

    function calculateTotalExpenses() {

        totalExpenses = 0;
        let tempArr = [];

        $('.sync_total').each(function(index) {
            let amount = 0;
            let rate = 1;

            if ($(this).hasClass('amount')) {

                let nameTarget = $(this).attr('data-target');
                let unitSelector = $('.select_unit[data-target="' + nameTarget + '"]');

                // Unit not selected item
                if (unitSelector != undefined && unitSelector.val() == '') {
                    return true; // continue
                }

                if (!tempArr.includes(nameTarget) && !tempArr.includes($(this).attr('name'))) {
                    amount = $(this).val();
                    rate = $('[name="' + nameTarget + '"]').val();
                    // rate is readonly
                    if (rate == '' && $('[name="' + nameTarget + '"]').attr('readonly') == 'readonly') {
                        rate = 1;
                    }
                    tempArr.push(nameTarget);
                    tempArr.push($(this).attr('name'));
                }
            }
            if ($(this).hasClass('rate')) {

                let nameTarget = $(this).attr('data-target');
                let unitSelector = $('.select_unit[data-target="' + $(this).attr('name') + '"]');

                // Unit not selected item
                if (unitSelector != undefined && unitSelector.val() == '') {
                    return true; // continue
                }

                if (!tempArr.includes(nameTarget) && !tempArr.includes($(this).attr('name'))) {
                    rate = $(this).val();
                    amount = $('[name="' + nameTarget + '"]').val();
                    tempArr.push(nameTarget);
                    tempArr.push($(this).attr('name'));
                }
            }

            amount = amount.toString().replace(/,/g, '');
            rate = rate.toString().replace(/,/g, '');

            if (isNaN(amount) || amount == '') {
                return true; // continue
            }
            if (isNaN(rate) || rate == '') {
                return true; // continue
            }

            let sub = amount * rate;

            totalExpenses += sub;
        });

        totalExpenses = numeral(totalExpenses).format('0,0.00')
        $('#total_expenses').text(totalExpenses);
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