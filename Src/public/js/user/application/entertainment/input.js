$(document).ready(function () {

    formLoad();
    
    //=======================================
    // Datetimepicker
    //=======================================
    // init
    $('#datetime').datetimepicker({
        format: 'DD/MM/YYYY HH:mm',
        defaultDate: $('#entertainment_dt').val() != '' ? moment($('#entertainment_dt').val()) : false,
        toolbarPlacement: 'bottom',
        sideBySide: true,
        showTodayButton: true,
        showClear: true,
        useCurrent: false,
        sbaDayOnly: false
    });
    // change
    $("#datetime").on("dp.change", function (e) {
        if (e.date) {
            $('#entertainment_dt').val(e.date.format('YYYY/MM/DD HH:mm'));
        } else {
            $('#entertainment_dt').val(null);
        }
    });

    //=======================================
    // Cleave input (formatting inputs)
    //=======================================

    new Cleave('.est_amount', {
        // stripLeadingZeroes: true,
        // numericOnly:true,
        // delimiters: [','],
        // blocks: [3, 3, 3],
        numeral: true,
        numeralDecimalScale: 0,
        numeralThousandsGroupStyle: 'thousand',
        numeralPositiveOnly: true,
        stripLeadingZeroes: true,
        onValueChanged: function (e) {
            let maxLength = $($(this)[0].element).attr('max-number');
            if (e.target.rawValue.length > maxLength) {
                this.setRawValue(this.lastInputValue);
            } else {
                $('[name="est_amount"]').val(e.target.rawValue);
            }
        }
    });

    new Cleave('.entertainment_person', {
        numericOnly: true,
        blocks: [3],
        onValueChanged: function (e) {
            $('[name="entertainment_person"]').val(e.target.rawValue);
        }
    });

    new Cleave('.entertainment_times', {
        numericOnly: true,
        blocks: [3],
        onValueChanged: function (e) {
            $('[name="entertainment_times"]').val(e.target.rawValue);
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
    // Radio Button & Checkbox Changes
    //=======================================
    $('[name="rd_during_trip"]').on('change', function () {
        $('#during_trip').val($(this).val());
    });
    $('[name="rd_budget_position"]').on('change', function () {
        $('#budget_position').val($(this).val());
    });
    $('[name="rd_check_row"]').on('change', function () {
        $('#check_row').val($(this).val());
    });
    $('[name="rd_has_entertainment_times"]').on('change', function () {
        $('#has_entertainment_times').val($(this).val());
        if ($(this).val() == true) {
            $('#txt_entertainment_times').removeAttr('readonly');
            $('#rq_et_times').removeClass('d-none');
        } else {
            $('#txt_entertainment_times').attr('readonly', 'readonly');
            $('#rq_et_times').addClass('d-none');
            $('input[type=number][name="entertainment_times"]').val('');
        }
    });
    $('[name="rd_existence_projects"]').on('change', function () {
        $('#existence_projects').val($(this).val());
    });
    $('[name="rd_includes_family"]').on('change', function () {
        $('#includes_family').val($(this).val());
    });
    $('[name="cb_subsequent"]').on('change', function () {
        if ($(this).prop('checked')) {
            $('#subsequent').val(1);
        } else {
            $('#subsequent').val(0);
        }
    });
    $('[name="rd_entertainment_reason"]').on('change', function () {
        $('#entertainment_reason').val($(this).val());
        if ($(this).val() == _REASON_OTHER) { // Other
            $('#entertainment_reason_other').removeAttr('readonly');
            $('#entertainment_reason_other').removeClass('d-none');
        } else {
            $('#entertainment_reason_other').attr('readonly', 'readonly');
            $('#entertainment_reason_other').addClass('d-none');
        }
    });

    $('#during_trip').val($('[name="rd_during_trip"]:checked').val());
    $('#budget_position').val($('[name="rd_budget_position"]:checked').val());
    $('#check_row').val($('[name="rd_check_row"]:checked').val());
    $('#has_entertainment_times').val($('[name="rd_has_entertainment_times"]:checked').val());
    $('#existence_projects').val($('[name="rd_existence_projects"]:checked').val());
    $('#includes_family').val($('[name="rd_includes_family"]:checked').val());
    $('#subsequent').val($('[name="cb_subsequent"]').prop('checked') ? 1 : 0);

    //=======================================
    // Form load
    //=======================================
    function formLoad() {
        // entertainment reason
        $('#entertainment_reason').val($('[name="rd_entertainment_reason"]').val());
        if ($('[name="rd_entertainment_reason"]').val() == _REASON_OTHER) { // Other
            if (!_PREVIEW_FLG) {
                $('#entertainment_reason_other').removeAttr('readonly');
            }
            $('#entertainment_reason_other').removeClass('d-none');
        } else {
            $('#entertainment_reason_other').attr('readonly', 'readonly');
            $('#entertainment_reason_other').addClass('d-none');
        }

        // entertainment_times
        if ($('[name="has_entertainment_times"]').val() == true) {
            $('#txt_entertainment_times').removeAttr('readonly');
            $('#rq_et_times').removeClass('d-none');
        } else {
            $('#txt_entertainment_times').attr('readonly', 'readonly');
            $('#rq_et_times').addClass('d-none');
            $('input[type=number][name="entertainment_times"]').val('');
        }
    }

    //=======================================
    // Auto complete (typehead.js)
    //=======================================
    var substringMatcher = function (strs) {
        return function findMatches(q, cb) {
            var matches, substringRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function (i, str) {
                if (substrRegex.test(str)) {
                    matches.push(str);
                }
            });

            cb(matches);
        };
    };
    function applyAutoComplete(selector) {
        selector.typeahead(
            {
                hint: false,
                highlight: true,
                minLength: 1
            },
            {
                limit: 999,
                source: substringMatcher(_COMPANIES)
            }
        );
    }

    //=======================================
    // Form load
    //=======================================
    applyAutoComplete($('.cp_name'));

    //=======================================
    // Entertainment Infos Block
    //=======================================
    // add new entertainment info element
    $('#btnAdd').on("click", function (e) {

        e.preventDefault();

        var mainBlock = $('#infos_block');
        var copyElement = $('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        applyAutoComplete(copyElement.find('.cp_name'));

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
        var infosElements = $('.card-company:not(.copy)');
        infosElements.each(function (index) {
            // re-order index
            $(this).find('.cp_name').attr('name', 'infos[' + index + '][cp_name]');
            $(this).find('.title').attr('name', 'infos[' + index + '][title]');
            $(this).find('.name_attendants').attr('name', 'infos[' + index + '][name_attendants]');
            $(this).find('.details_dutles').attr('name', 'infos[' + index + '][details_dutles]');
            // always keep at least one element
            if (infosElements.length === 1 && index === 0) {
                $(this).find('.d-delete').addClass('d-none');
            } else {
                $(this).find('.d-delete').removeClass('d-none');
            }
        });
        // maximum is 4 blocks only
        if (infosElements.length >= 4) {
            $('#btnAdd').addClass('d-none');
        } else {
            $('#btnAdd').removeClass('d-none');
        }
    }

    //=======================================
    // Print PDF
    //=======================================

    $('[name="pdf"]').on('click', function (e) {

        e.preventDefault();

        // not show loading icon
        showLoadingFlg = false;

        let form = e.currentTarget.form;
        let buttonName = $(this).attr('name');
        let hidSubmit = '<input type="hidden" name="' + buttonName + '" value="' + buttonName + '" />';
        $(form).append(hidSubmit);
        form.submit();
    });
});