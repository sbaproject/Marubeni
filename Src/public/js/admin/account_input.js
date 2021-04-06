$(function() {
    //=======================================
    // Cleave input (formatting inputs)
    //=======================================

    new Cleave('#txt_leave_days', {
        numeral: true,
        numeralDecimalScale: 0,
        numeralPositiveOnly: false,
        stripLeadingZeroes: true,
        onValueChanged: function(e) {
            let rawValue = e.target.rawValue;
            if (e.target.rawValue == '') {
                this.setRawValue(0);
                rawValue = 0;
            }
            $('[name="leave_days"]').val(rawValue);
        }
    });

    new Cleave('#txt_leave_remaining_days', {
        numeral: true,
        numeralDecimalScale: 0,
        numeralPositiveOnly: false,
        stripLeadingZeroes: true,
        onValueChanged: function(e) {
            let rawValue = e.target.rawValue;
            if (e.target.rawValue == '') {
                this.setRawValue(0);
                rawValue = 0;
            }
            $('[name="leave_remaining_days"]').val(rawValue);
        }
    });

    new Cleave('#txt_leave_remaining_time', {
        numeral: true,
        numeralDecimalScale: 0,
        numeralPositiveOnly: false,
        stripLeadingZeroes: true,
        onValueChanged: function(e) {
            let rawValue = e.target.rawValue;
            if (e.target.rawValue == '') {
                this.setRawValue(0);
                rawValue = 0;
            }
            $('[name="leave_remaining_time"]').val(e.target.rawValue);
        }
    });
});