<?php

/**
 * Label here
 */


return [
    'button' => [
        'update' => 'Cập nhật',
        'change' => 'Thay đổi',
        'register' => 'Đăng ký',
        'addnew' => 'Thêm',
        'edit' => 'Sửa',
        'delete' => 'Xóa',
        'cancel' => 'Hủy',
        'search' => 'Tìm kiếm',
        'login' => 'Đăng nhập',
        'logout' => 'Đăng xuất',
        'apply' => 'Nộp đơn',
        'draft' => 'Nháp',
        'export' => 'Xuất PDF',
        'approval' => 'Phê Duyệt',
    ],
    'rememberme' => 'Nhớ đăng nhập',
    'forgotpassword' => 'Quên mật khẩu ?',
    'success' => 'Thành công',
    'fail' => 'Thất bại',
    'hn' => 'Hà Nội',
    'hcm' => 'Hồ Chí Minh',
    'select' => 'Vui lòng chọn',
    'on' => 'Bật',
    'off' => 'Tắt',
    'action' => 'Chức năng',
    '_no_' => 'Mã số',
    'email_address' => 'E-Mail',
    'change_pass' => 'Đổi Mật Khẩu',
    /**
     * Paginator
     */
    'paginator' => [
        'prev' => 'Trang trước',
        'next' => 'Trang sau',
        'first' => 'Trang đầu',
        'last' => 'Trang cuối',
    ],
    /**
     * Form
     */
    'form' => [
        'leave' => 'Đơn xin nghỉ phép',
        'biz_trip' => 'Đơn công tác',
        'entertaiment' => 'Đơn tiếp khách',
        // get by id
        '1' => 'Đơn xin nghỉ phép',
        '2' => 'Đơn công tác',
        '3' => 'Đơn tiếp khách',
    ],
    /**
     * Leave application
     */
    'leave' => [
        'code_leave' => [
            'AL' => 'Nghỉ phép',
            'UL' => 'Nghỉ không hưởng lương',
            'CL' => 'Nghỉ bù',
            'WL' => 'Nghỉ cưới',
            'PL' => 'Nghỉ CK kinh nguyệt',
            'ML' => 'Nghỉ sinh con',
            'SL' => 'Nghỉ ốm',
        ],
        // Type leave of SICK LEAVE code
        'paid_type' => [
            'AL' => 'Nghỉ phép theo chế độ',
            'UL' => 'Nghỉ phép không hưởng lương'
        ],
        'caption' => [
            'code_leave' => 'Mã xin nghỉ phép',
            'reason_leave' => 'Lý do xin nghỉ phép',
            'paid_type' => 'Hình thức xin nghỉ phép',
            'date_leave' => 'Nghỉ theo ngày',
            'time_leave' => 'Nghỉ theo giờ',
            'maternity_leave' => 'Nghỉ sinh con',
            'annual_leave' => 'Tình trạng nghỉ phép',
            'subsequent' => 'Tạo thêm',
            'file_path' => 'Tập tin đính kèm',
            'entitled_year' => 'Số ngày phép được nghỉ trong năm',
            'used_this_year' => 'Số ngày phép đã sử dụng trong năm',
            'take_this_time' => 'Số ngày phép xin nghỉ',
            'remaining' => 'Số ngày phép chưa sử dụng',
            'days' => 'Ngày',
            'hours' => 'Giờ',
        ],
    ],
    'file' => 'Tập tin',
    'choose_file' => 'Chọn tập tin',
    //Type Application
    'application_list' => 'Danh sách đơn',
    'leave_application' => 'Đơn xin nghỉ phép',
    'biz_application' => 'Đơn công tác',
    'entertaiment_application' => 'Đơn tiếp khách',
    /**
     * Date From - Date To (Search)
     */
    'from' => 'Từ',
    'to' => 'Đến',
    'date' => 'Ngày',
    'date_from' => 'Từ ngày',
    'date_to' => 'Đến ngày',
    /**
     * Dashboard
     */
    'dashboard' => [
        //form
        'list_application' => 'Danh sách Đơn',
        'application_no' => 'Mã Đơn',
        'application_name' => 'Loại Đơn',
        'status' => 'Trạng Thái',
        'apply_date' => 'Ngày Nộp Đơn',
        'view_details' => 'Chi tiết',
        'applying' => 'Đơn Chuẩn Bị Duyệt',
        'approval' => 'Đơn Đang Phê Duyệt',
        'declined' => 'Đơn Không Duyệt',
        'reject' => 'Đơn Trả Lại',
        'completed' => 'Đơn Hoàn Thành',
    ],
    /**
     * Draft
     */
    'draft' => [
        //forms
        'no' => 'Mã Đơn',
        'application_name' => 'Loại Đơn',
        'date_create' => 'Ngày Tạo',
        'action' => 'Chức Năng',
    ],
    /**
     * Status
     */
    'status' => [
        //forms
        'list_of_applying_documents' => 'Danh Sách Đơn Đang Duyệt',
        'list_of_approval_un_documents' => 'List of Approved/Under payment Documents',
        'list_of_approval_in_documents' => 'List of Approved/in-processing of payment Documents',
        'list_of_declined_documents' => 'Danh Sách Đơn Không Duyệt',
        'list_of_reject_documents' => 'Danh Sách Đơn Trả Lại',
        'list_of_completed_documents' => 'Danh Sách Đơn Hoàn Thành',
        'no' => 'Số',
        'application_type' => 'Tên Đơn',
        'apply_date' => 'Ngày Nộp Đơn',
        'next_approver' => 'Người Duyệt Tiếp Theo',
        'view_details' => 'Chi tiết',
    ],
    /**
     * Company
     */
    'company' => [
        'keyword' => 'Từ Khóa',
        'company_registration' => 'Thêm Thông Tin Công Ty',
        //forms
        'no' => 'Số',
        'company_information' => 'Thông Tin Công Ty',
        'company_name' => 'Tên Công Ty',
        'company_country' => 'Đất Nước',
        'company_tell' => 'Số Điện Thoại',
        'company_address' => 'Địa Chỉ',
        'att_information' => 'Thông Tin Người Nhận',
        'att_name' => 'Tên Người Nhận',
        'att_department' => 'Bộ Phận',
        'att_email' => 'E-mail',
        'text' => 'Ghi Chú',
        'action' => 'Chức Năng',
    ],
    /**
     * Budget
     */
    'budget' => [
        'business_trip' => 'Phí Công Tác',
        'assignment' => 'Trước Duyệt',
        'settlement' => 'Sau Duyệt',
        'economy_class' => 'Hạng Phổ Thông',
        'business_class' => 'Hạng Thương Gia',
        'pre_approvel_settlement_for_entertainment_free' => 'Phí Giải Trí (Trước và Sau Duyệt)',
        'not_po' => 'Not PO',
        'po' => 'PO',
    ],
    /**
     * Flow
     */
    'flow' => [
        'approval_flow_list' => 'Danh Sách Luồng Phê Duyệt',
        'no' => 'Số',
        'flow_name' => 'Tên Luồng',
        'step' => 'Bước',
        'final_approver' => 'Người Duyệt Sau Cùng',
        'Actions' => 'Chức Năng',
        'approval_flow_setting' => 'Thiết lập quy trình phê duyệt',
        'approval_no' => 'Số phê duyệt',
        'approval_flow_name' => 'Tên quy trình phê duyệt',
        'application_form' => 'Loại đơn xin',
        'type' => 'Loại',
        'applicant_role' => 'Vai trò người đề nghị',
        'budget_for_per_person' => 'Ngân sách cho mỗi người',
        'less_or_equal_than' => 'Nhỏ hơn hoặc bằng',
        'greater_than' => 'Lớn hơn',
        'approver' => 'Người phê duyệt',
        'step' => 'Bước',
        'destination' => 'Nơi đơn chuyển tới',
        'add' => 'Thêm',
        'submit' => 'Lưu',
        'delete' => 'Xóa',
        'update' => 'Cập nhật',
        'cancel' => 'Hủy',
        'approver_required' => 'Vui lòng chọn người phê duyệt',
    ],
];
