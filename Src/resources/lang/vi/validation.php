<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'Xác nhận :attribute không đúng.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => ':attribute đã nhập không đúng định dạng email.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => ':attribute đã chọn không hợp lệ.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => ':attribute phải lớn hơn :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => ':attribute đã chọn không hợp lệ.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => ':attribute chỉ được nhập tối đa :max ký tự.',
        'file' => ':attribute có kích cỡ không được quá :max kilobytes.',
        'string' => ':attribute chỉ được nhập tối đa :max ký tự.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => ':attribute chỉ chấp nhận kiểu tập tin sau: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => ':attribute phải nhập ít nhất :min ký tự.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => ':attribute phải nhập ít nhất :min ký tự.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => ':attribute phải là số.',
    'password' => ':attribute không đúng.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'Vui lòng nhập :attribute.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => ':attribute phải là dạng chuỗi.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => ':attribute này đã tồn tại.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',
    'phone_number' => ':attribute không hợp lệ.',
    'required_select' => 'Vui lòng chọn :attribute.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'confirm_new_password' => [
            'same' => ':attribute chưa khớp.',
        ],
        'new_password' => [
            'regex' => ':attribute chỉ được chứa ký tự số 0 -> 9, chữ cái a -> z và các ký tự đặc biệt sau : _@.#&+%!-'
        ],
    ],

    'change_pass' => [
        'current_incorrect' => ':attribute không đúng.',
        'new_same_current' => ':attribute không được trùng mật khẩu hiện tại.'
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'password' => 'Mật khẩu',
        'current_password' => 'Mật khẩu hiện tại',
        'new_password' => 'Mật khẩu mới',
        'confirm_new_password' => 'Xác nhận mật khẩu mới',
        'email' => 'E-Mail',
        'user' => [
            'name' => 'Tên',
        ],
        'location' => 'Chi nhánh',
        'department' => 'Phòng ban',
        'role' => 'Vai trò',
        'phone' => 'Điện thoại',
        'approval' => 'Phê duyệt',
        'memo' => 'Ghi chú',
        'user_no' => 'Mã số nhân viên',
        'paid_type' => 'Hình thức xin nghỉ phép',
        'code_leave' => 'Mã nghỉ phép',
        'file_path' => 'Tập tin đính kèm',
        'input_file' => 'Tập tin đính kèm',
        'name' => 'Tên Công Ty',
        'country'   => 'Tên Nước',
        'tel'   => 'Số Điện Thoại',
        'address'   => 'Địa Chỉ',
        'attendants_name'   => 'Tên',
        'attendants_department'   => 'Phòng Ban',
        'email' => 'E-mail',
        //Budget
        'amount1' => 'Hạng Thường',
        'amount2' => 'Hạng Thương Gia',
        'amount3' => 'Hạng Thường',
        'amount4' => 'Hạng Thương Gia',
        'amount5' => 'Not PO',
        'amount6' => 'PO',
        'amount7' => 'Not PO',
        'amount8' => 'PO',
        // Business
        'trip_dt_from' => 'Ngày đi',
        'trip_dt_to' => 'Ngày đến',
    ],
];
