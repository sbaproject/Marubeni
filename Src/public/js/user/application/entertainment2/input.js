$(document).ready(function() {

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
    $("#datetime").on("dp.change", function(e) {
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
        numeral: true,
        numeralDecimalScale: 0,
        numeralThousandsGroupStyle: 'thousand',
        numeralPositiveOnly: true,
        stripLeadingZeroes: true,
        onValueChanged: function(e) {
            $('[name="est_amount"]').val(e.target.rawValue);
        }
    });

    //=======================================
    // Auto complete (typehead.js)
    //=======================================
    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
            var matches, substringRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    matches.push(str);
                }
            });

            cb(matches);
        };
    };

    function applyAutoComplete(selector) {
        selector.typeahead({
            hint: false,
            highlight: true,
            minLength: 1
        }, {
            limit: 999,
            source: substringMatcher(_COMPANIES)
        });
    }

    //=======================================
    // Form load
    //=======================================
    function formLoad() {

        makeCleavePercent();
    }

    applyAutoComplete($('.cp_name'));

    //=======================================
    // Entertainment Infos Block
    //=======================================
    // add new entertainment info element
    $('#btnAdd').on("click", function(e) {

        e.preventDefault();

        var mainBlock = $('#infos_block');
        var copyElement = mainBlock.find('.copy').clone();

        copyElement.removeClass('copy');
        copyElement.removeClass('d-none');

        applyAutoComplete(copyElement.find('.cp_name'));

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
        var infosElements = $('.card-company:not(.copy)');
        infosElements.each(function(index) {
            // re-order index
            $(this).find('.cp_name').attr('name', 'entertainmentinfos[' + index + '][cp_name]');
            $(this).find('.title').attr('name', 'entertainmentinfos[' + index + '][title]');
            $(this).find('.name_attendants').attr('name', 'entertainmentinfos[' + index + '][name_attendants]');
            $(this).find('.details_dutles').attr('name', 'entertainmentinfos[' + index + '][details_dutles]');
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

    // Percent
    function makeCleavePercent() {
        $('.chargedbys_percent').each(function(index, element) {
            new Cleave(element, {
                numericOnly: true,
                blocks: [3]
            });
        });
    }

    //=======================================
    // Print PDF
    //=======================================

    $('#btnPdf').on('click', function(e) {

        e.preventDefault();

        var data = $('#post-form').serialize();
        var pdf_url = $('#pdf_url').val();

        $("#popup-loading").modal('show');

        $.ajax({
            url: pdf_url,
            type: "GET",
            data: data,
            success: function(response, xhr) {
                $("#popup-loading").modal('hide');
                window.open(pdf_url);
            },
            error: function(err) {
                $("#popup-loading").modal('hide');
                alert('Error to show PDF !');
            }
        });

        return false;
    });
});