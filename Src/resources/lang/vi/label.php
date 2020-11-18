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
    'yes' => 'Có',
    'no' => 'Không',
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
     * Menu
     */
    'menu' => [
        'application' => 'LOẠI ĐƠN XIN',
        'application_list' => 'TRẠNG THÁI',
        'draft' => 'LƯU NHÁP',
        'status' => 'TRẠNG THÁI',
        'applying' => 'Đơn Đang Xin',
        'approved_un' => 'Đơn Đã Duyệt / ',
        'approved_un2' => 'Chưa Thanh Toán',
        'approved_in' => 'Đơn Đã Duyệt / ',
        'approved_in2' => 'Đang Thanh Toán',
        'declined' => 'Đơn Trả Về',
        'reject' => 'Đơn Khước Từ',
        'completed' => 'Đơn Hoàn Tất',
        'approval' => 'DUYỆT ĐƠN',
        'settings' => 'SETTINGS',
        'company_registration' => 'Đăng Ký Doanh Nghiệp',
        'change_password' => 'Thay Đổi Mật Khẩu',
        'employee_setting' => 'Đăng Ký Nhân Viên',
        'budget_setting' => 'Ngân Sách',
        'approval_flow_setting' => 'Quy Trình Phê Duyệt',
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
    /**
     * Business Application
     */
    'business' => [
        'departure' => 'Điểm đi',
        'arrival' => 'Điểm đến',
        'method' => 'Số chuyến',
        'application_no' => 'Mã đơn',
        'trip_destination' => 'Nơi công tác',
        'date_trip' => 'Thời gian',
        'transportation' => 'Hành trình & Phương tiện',
        'accommodation' => 'Nơi ở',
        'accompany' => 'Người đi cùng',
        'borne_by' => 'Chi phí chịu bởi',
        'comment' => 'Nội dung công tác',
    ],
    /**
     * Entertainment Application
     */
    'entertainment' => [
        'application_no' => 'Mã đơn',
        'entertainment_dt' => 'Ngày giờ',
        'place' => 'Địa điểm',
        'during_trip' => 'Đang trong chuyến công tác',
        'check_row' => 'Xác nhận việc tuân thủ luật pháp',
        'entertainment_times' => 'Số lần tiếp khách trong năm vừa qua',
        'existence_projects' => 'Dự án đang tồn tại',
        'includes_family' => 'Thành phần tham dự bao gồm cả gia đình/ bạn bè',
        'project_name' => 'Tên dự án',
        'entertainment_reason' => 'Lý do tiếp khách',
        'entertainment_person' => 'Tổng số người tham gia dự tính',
        'est_amount' => 'Chi phí dự tính',
        'reason_budget_over' => 'Mô tả nếu số tiền mỗi người vượt quá 4 triệu đồng (PO: 2 triệu đồng)',
        'cp_name' => 'Tên công ty',
        'title' => 'Chức danh',
        'name_attendants' => 'Người tham dự',
        'details_dutles' => 'Chi tiết nhiệm vụ',
        'if_need' => 'Nếu có',
        'persons' => 'Số người',
        'per_person_excluding_vnd' => 'Trên mỗi người(Không bao gồm VAT)',
        'entrainment_infomation' => 'Thông tin giải trí',
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
        'list_application' => 'Danh sách Tất Cả Đơn',
        'application_no' => 'Số Thứ Tự',
        'application_name' => 'Loại Đơn',
        'status' => 'Trạng Thái',
        'apply_date' => 'Ngày Nộp Đơn',
        'view_details' => 'Xem Chi tiết',
        'applying' => 'Đơn Đang Xin',
        'approval' => 'Đơn Phê Duyệt',
        'declined' => 'Đơn Trả Về',
        'reject' => 'Đơn Khước Từ',
        'completed' => 'Đơn Hoàn Tất',
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
        'list_of_applying_documents' => 'Danh Sách Đơn Đang Xin',
        'list_of_approval_documents' => 'Danh Sách Đơn Phê Duyệt',
        'list_of_approval_un_documents' => 'Danh Sách Đơn Đã Duyệt / Chưa Thanh Toán',
        'list_of_approval_in_documents' => 'Danh Sách Đơn Đã Duyệt / Đang Thanh Toán',
        'list_of_declined_documents' => 'Danh Sách Đơn Trả Về',
        'list_of_reject_documents' => 'Danh Sách Đơn Khước từ',
        'list_of_completed_documents' => 'Danh Sách Đơn Hoàn Tất',
        'no' => 'Số Thứ Tự',
        'application_type' => 'Loại Đơn',
        'apply_date' => 'Ngày Nộp Đơn',
        'next_approver' => 'Người Phê Duyệt Kế Tiếp',
        'view_details' => 'Xem Chi tiết',
    ],
    /**
     * Company
     */
    'company' => [
        'keyword' => 'Từ Khóa',
        'company_registration' => 'Thông Tin Công Ty',
        //forms
        'no' => 'Số',
        'company_information' => 'Thông Tin Công Ty',
        'company_id' => 'ID Công Ty',
        'company_name' => 'Tên Công Ty',
        'company_country' => 'Quốc Gia',
        'company_tell' => 'Điện Thoại',
        'company_address' => 'Địa Chỉ',
        'att_information' => 'Thông Tin Người Đề Nghị',
        'att_name' => 'Tên Người Đề nghị',
        'att_department' => 'Phòng Ban',
        'att_email' => 'E-mail',
        'text' => 'Ghi Chú',
        'action' => 'Chức Năng',
        'name' => 'Tên',
        'check_exist_com_name' => 'Tên Công ty đã tồn tại',
    ],
    /**
     * Budget
     */
    'budget' => [
        'business_trip' => 'CHI PHÍ CÔNG TÁC',
        'assignment' => 'Chấp Thuận',
        'settlement' => 'Thanh Toán',
        'economy_class' => 'Hạng Phổ Thông',
        'business_class' => 'Hạng Thương Gia',
        'pre_approvel_settlement_for_entertainment_free' => 'ĐỀ NGHỊ CHẬP THUẬN TRƯỚC & THANH TOÁN CHI PHÍ TIẾP KHÁCH',
        'not_po' => 'Not PO',
        'po' => 'PO',
    ],
    /**
     * Flow
     */
    'flow' => [
        'approval_flow_list' => 'Danh Sách Quy Trình Phê Duyệt',
        'no' => 'Số',
        'flow_name' => 'Tên Quy Trình',
        'step' => 'Bước',
        'final_approver' => 'Người Phê Duyệt Cuối',
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
    /**
     * Login
     */
    'login' => [
        //form login
        'title_login_pass' => 'ĐĂNG NHẬP',
        'email_address' => 'E-Mail',
        'password' => 'Mật khẩu',
        'remember' => 'Nhớ mật khẩu',
        'btn_login' => 'Đăng nhập',
        'link_forgot_pass' => 'Quên mật khẩu?',
        //form fogot password
        'title_reset_pass' => 'ĐẶT LẠI MẬT KHẨU',
        'btn_send_pass' => 'Xác nhận',
        //form create new password
        'title_create_pass' => 'TẠO MỚI MẬT KHẨU',
        'confirm_password' => 'Xác nhận mật khẩu',
        'btn_create_pass' => 'Xác nhận',
    ],
    /**
     * checkip
     */
    'checkip' => [
        //form
        'title' => 'XÁC NHẬN TRUY CẬP MẠNG NGOÀI',
        'content' => 'Mã xác nhận đã gửi đến: ',
        'enter_code' => 'Nhập mã',
        //btn
        'btn_confirm' => 'Xác nhận',
        'btn_back' => 'Trở về',
        //Validation
        'valid_not_compare' => 'Mã không Chính Xác, Vui lòng thử lại !',
        'valid_expired' => 'MÃ ĐÃ HẾT HIỆU LỰC, ĐÃ GỬI LẠI MÃ MỚI !  Vui lòng thử lại !',
        //Mail content
        'mail_subject' => 'Xác Nhận Truy Cập Mạng Ngoài',
        'mail_content' => 'Mã Xác Nhận Truy Cập Mạng Ngoài',
        'mail_the_code_is' => 'Mã xác nhận: ',
    ],
];
