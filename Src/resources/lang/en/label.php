<?php

/**
 * Label
 */

return [
    /**
     * Button
     */
    'button_update'     => 'Update',
    'button_change'     => 'Change',
    'button_register'   => 'Register',
    'button_addnew'     => 'Add',
    'button_edit'       => 'Edit',
    'button_delete'     => 'Delete',
    'button_restore'    => 'Restore',
    'button_skip'       => 'Skip approver',
    'button_cancel'     => 'Cancel',
    'button_accept'     => 'Accept',
    'button_search'     => 'Search',
    'button_login'      => 'Login',
    'button_logout'     => 'Logout',
    'button_apply'      => 'Apply',
    'button_draft'      => 'Draft',
    'button_export'     => 'Export PDF',
    'button_approval'   => 'Approval',
    'button_preview'    => 'Preview',
    'button_reject'     => 'Reject',
    'button_declined'   => 'Decline',
    'button_processing' => 'Processing...',

    'rememberme'        => 'Remember me',
    'forgotpassword'    => 'Forgot your password ?',
    'success'           => 'Success',
    'fail'              => 'Fail',
    'hn'                => 'Ha Noi',
    'hcm'               => 'Ho Chi Minh',
    'select'            => 'Select',
    'on'                => 'ON',
    'off'               => 'OFF',
    'action'            => 'Action',
    '_no_'              => 'NO.',
    'email_address'     => 'E-Mail address',
    'change_pass'       => 'Change Password',
    'file'              => 'File',
    'choose_file'       => 'Choose file',
    'yes'               => 'Yes',
    'no'                => 'No',
    'or'                => 'Or',
    'keyword'           => 'Keyword',
    'application_no'    => 'Application No',
    'applicant_name'    => 'Applicant name',
    'pending_approval'  => 'Pending Approval Application',
    'application_info'  => 'Application Information',
    'no_attached_file'  => 'No attached file',
    'comment'           => 'Comment',
    'comments'          => 'Comments',
    'comment_here'      => 'Type your comment here...',
    'applicant'         => 'Applicant',
    'confirming_title'  => 'Confirm',
    'times'             => 'Times',
    'step'              => 'Step',
    'status_caption'    => 'Status',
    'reason'            => 'Reason',
    'search_by_app_no_applicant' => 'Search by Application No or Applicant name',

    'popup_title_skip_approver' => 'Skip approval of ?',

    'title_user_list'           => 'Employees',
    'title_user_edit'           => 'Employee Edit',
    'title_user_add'            => 'Employee Add',
    'title_approval_list'       => 'Pending approval list',

    'only_show_deleted_users'   => 'Show deleted users only',

    /**
     * Paginator
     */
    'paginator_prev'                => 'Previous page',
    'paginator_next'                => 'Next page',
    'paginator_first'               => 'First page',
    'paginator_last'                => 'Last page',
    'paginator_show_result_label'   => 'Showing :from - :to of :total items.',

    /**
     * Step Type
     */
    'step_type' => [
        1 => 'Application',
        2 => 'Settlement',
    ],

    /**
     * Form
     */
    'form_leave'            => 'Leave',
    'form_biz_trip'         => 'Business Trip',
    'form_entertainment'    => 'Entertainment',
    'form' => [
        // get by id
        '1' => 'Leave',
        '2' => 'Business Trip',
        '3' => 'Entertainment',
    ],

    /**
     * Menu
     */
    'menu_dashboard'                => 'Dashboard',
    'menu_application'              => 'Application',
    'menu_application_list'         => 'Application List',
    'menu_draft'                    => 'Draft',
    'menu_status'                   => 'Status',
    'menu_applying'                 => 'Applying',
    'menu_approved_un'              => 'Approved / Under',
    'menu_approved_un2'             => 'Payment',
    'menu_approved_in'              => 'Approved / In-Processing',
    'menu_approved_in2'             => 'Of Payment',
    'menu_declined'                 => 'Declined',
    'menu_reject'                   => 'Reject',
    'menu_completed'                => 'Completed',
    'menu_approval'                 => 'Approval',
    'menu_settings'                 => 'Settings',
    'menu_company_registration'     => 'Company Registration',
    'menu_company_list'             => 'Company Management',
    'menu_change_password'          => 'Change Password',
    'menu_employee_setting'         => 'Employees',
    'menu_budget_setting'           => 'Budgets',
    'menu_approval_flow_setting'    => 'Approval Flows',
    'menu_user_info'                => 'Account Information',

    /**
     * Approval acion
     */
    'approval_action_approval'  => 'Approved',
    'approval_action_reject'    => 'Rejected',
    'approval_action_decline'   => 'Declined',
    'approval_action_complete'  => 'Completed',
    'approval_action_skipped'   => 'Skipped',

    /**
     * Application
     */
    'application_status_applying'   => 'Applying',
    'application_status_approval'   => 'Approval',
    'application_status_reject'     => 'Rejected',
    'application_status_decline'    => 'Declined',
    'application_status_complete'   => 'Completed',
    'application_status_draft'      => 'Draft',

    /**
     * Leave Application
     */
    'leave' => [
        // Code of Leave
        'code_leave' => [
            'AL' => 'ANNUAL LEAVE',
            'UL' => 'UNPAID LEAVE',
            'CL' => 'COMPASSIONATE LEAVE',
            'WL' => 'WEDDING LEAVE',
            'PL' => 'PERIODIC LEAVE',
            'ML' => 'MATERNITY LEAVE',
            'SL' => 'SICK LEAVE',
        ],
        // Type leave of SICK LEAVE code
        'paid_type' => [
            'AL' => 'Annual Leave',
            'UL' => 'Unpaid Leave'
        ],
        'caption_code_leave'        => 'Code of Leave',
        'caption_reason_leave'      => 'Reason for leave',
        'caption_paid_type'         => 'Type of leave to switch to',
        'caption_date_leave'        => 'Date of leave',
        'caption_time_leave'        => 'Time of leave',
        'caption_maternity_leave'   => 'Maternity leave',
        'caption_annual_leave'      => 'Annual leave',
        'caption_subsequent'        => 'Subsequent',
        'caption_file_path'         => 'Attached file',
        'caption_entitled_year'     => 'Entitled this year',
        'caption_used_this_year'    => 'Used this year',
        'caption_take_this_time'    => 'Take this time',
        'caption_remaining'         => 'Remaining',
        'caption_days'              => 'Days',
        'caption_hours'             => 'Hours',
        'caption_date_from'         => 'Date of leave (From)',
        'caption_date_to'           => 'Date of leave (To)',
        'caption_maternity_from'    => 'Maternity leave (From)',
        'caption_maternity_to'      => 'Maternity leave (To)',
        'caption_days_use'          => 'Take this time (Days)',
    ],

    /**
     * Business Application
     */
    'business_departure'        => 'Departure',
    'business_arrival'          => 'Arrival',
    'business_method'           => 'Flight No',
    'business_application_no'   => 'Application No',
    'business_trip_destination' => 'Trip Destinations',
    'business_date_trip'        => 'Date of Trip',
    'business_transportation'   => 'Itinerary & Transportation',
    'business_accommodation'    => 'Accommodation',
    'business_accompany'        => 'Accompany',
    'business_borne_by'         => 'Expenses to be charged by',
    'business_comment'          => 'Comment',
    'business_budget_position'  => 'Budget type',

    /**
     * Entertainment Application
     */
    'entertainment_application_no'              => 'Application No',
    'entertainment_entertainment_dt'            => 'Date & Time',
    'entertainment_place'                       => 'Place',
    'entertainment_during_trip'                 => 'During biz trip',
    'entertainment_check_row'                   => 'Confirmation of Compliance with Laws',
    'entertainment_entertainment_times'         => 'No. of Entertainment for past 1 year',
    'entertainment_existence_projects'          => 'Existence of projects',
    'entertainment_includes_family'             => 'Including Family/Friend',
    'entertainment_project_name'                => 'Project Name',
    'entertainment_entertainment_reason'        => 'Reason for the Entertainment',
    'entertainment_entertainment_reason_other'  => 'Other reason for the Entertainment',
    'entertainment_entertainment_person'        => 'Total Number of Person',
    'entertainment_est_amount'                  => 'Estimated Amount',
    'entertainment_reason_budget_over'          => 'Describe if the amount per person exceeds 4mil VND (PO:2mil VND)',
    'entertainment_cp_name'                     => 'Company Name',
    'entertainment_title'                       => 'Title',
    'entertainment_name_attendants'             => 'Name of Attendants',
    'entertainment_details_dutles'              => 'Details of duties',
    'entertainment_if_need'                     => 'If need',
    'entertainment_persons'                     => 'Persons',
    'entertainment_per_person_excluding_vnd'    => 'Per Person(Excluding VAT)',
    'entertainment_entrainment_infomation'      => 'Entertainment Infomation',
    'entertainment_budget_position'             => 'Budget type',
    'entertainment' => [
        'reason' => [
            1   => 'Dinner (private sector)',
            2   => 'Dinner (PO)',
            3   => 'Golf (private)-AH burden',
            4   => 'Golf (Private)-Sales Department Burden',
            5   => 'Golf (PO)',
            6   => 'Gift (President and above)',
            7   => 'Gift (Specific Director or Executive Officer)',
            8   => 'Gifts (Other Directors or Executive Officers)',
            9   => 'Gift (manager or person in charge)',
            10  => 'Other',
        ],
    ],

    //Type Application
    'application_list'          => 'Application List',
    'leave_application'         => 'Leave application',
    'biz_application'           => 'Business Trip Application',
    'entertaiment_application'  => 'Entertaiment Application',

    /**
     * Date From - Date To (Search)
     */
    'from'      => 'From',
    'to'        => 'To',
    'date'      => 'Date',
    'date_from' => 'From',
    'date_to'   => 'To',

    /**
     * Dashboard
     */
    'dashboard_list_application'    => 'List of All Applications',
    'dashboard_application_no'      => 'Application No',
    'dashboard_application_name'    => 'Application Name',
    'dashboard_status'              => 'Status',
    'dashboard_apply_date'          => 'Apply Date',
    'dashboard_view_details'        => 'View Details',
    'dashboard_applying'            => 'Applying',
    'dashboard_approval'            => 'Approval',
    'dashboard_declined'            => 'Declined',
    'dashboard_reject'              => 'Reject',
    'dashboard_completed'           => 'Completed',

    /**
     * Draft
     */
    'draft_no'                  => 'No',
    'draft_application_name'    => 'Application Type',
    'draft_date_create'         => 'Apply Date',
    'draft_action'              => 'Actions',
    'draft_list'                => 'Draft list',

    /**
     * Status
     */
    'status_list_of_applying_documents'     => 'List of Applying Documents',
    'status_list_of_approval_documents'     => 'List of Approved Documents',
    'status_list_of_approval_un_documents'  => 'List of Approved/Under payment Documents',
    'status_list_of_approval_in_documents'  => 'List of Approved/in-processing of payment Documents',
    'status_list_of_declined_documents'     => 'List of Declined Documents',
    'status_list_of_reject_documents'       => 'List of Reject Documents',
    'status_list_of_completed_documents'    => 'List of Completed Documents',
    'status_no'                             => 'No',
    'status_application_type'               => 'Application Type',
    'status_apply_date'                     => 'Apply Date',
    'status_next_approver'                  => 'Next Approver',
    'status_view_details'                   => 'View Details',

    /**
     * Company
     */
    'company_keyword'               => 'Keyword',
    'company_company_registration'  => 'Company Registration',
    'company_no'                    => 'No',
    'company_company_information'   => 'Company Information',
    'company_company_id'            => 'Company ID',
    'company_company_name'          => 'Company Name',
    'company_company_country'       => 'Country',
    'company_company_tell'          => 'Tell',
    'company_company_address'       => 'Address',
    'company_att_information'       => 'Attendants Information',
    'company_att_name'              => 'Attendants Name',
    'company_att_department'        => 'Department',
    'company_att_email'             => 'E-mail',
    'company_text'                  => 'Text',
    'company_action'                => 'Actions',
    'company_name'                  => 'Name',
    'company_check_exist_com_name'  => "This Company's Name has existed !",

    /**
     * Budget
     */
    'budget_business_trip' => 'BUSINESS TRIP',
    'budget_assignment' => 'Assignment',
    'budget_settlement' => 'Settlement',
    'budget_economy_class' => 'Economy Class',
    'budget_business_class' => 'Business Class',
    'budget_not_po' => 'Not PO',
    'budget_po' => 'PO',
    'budget_pre_approvel_settlement_for_entertainment_free' => 'PRE-APPROVAL & SETTLEMENT FOR ENTERTAINMENT FEE',

    /**
     * Flow
     */
    'flow_approval_flow_list'       => 'Approval Flow List',
    'flow_no'                       => 'No',
    'flow_flow_name'                => 'Flow Name',
    'flow_step'                     => 'Step',
    'flow_final_approver'           => 'Final Approver',
    'flow_actions'                  => 'Actions',
    'flow_approval_flow_setting'    => 'Approval Flow Setting',
    'flow_approval_no'              => 'Approval No',
    'flow_approval_flow_name'       => 'Approval Flow Name',
    'flow_application_form'         => 'Application Form',
    'flow_type'                     => 'Type',
    'flow_applicant_role'           => 'Applicant Role',
    'flow_budget_for_per_person'    => 'Budget for per person',
    'flow_less_or_equal_than'       => 'Less Or Equal than',
    'flow_greater_than'             => 'Greater than',
    'flow_approver'                 => 'Approver',
    'flow_step'                     => 'Step',
    'flow_destination'              => 'Destination',
    'flow_add'                      => 'Add',
    'flow_submit'                   => 'Submit',
    'flow_delete'                   => 'Delete',
    'flow_update'                   => 'Update',
    'flow_cancel'                   => 'cancel',
    'flow_approver_required'        => 'Please select a approver',
    'flow_destination_invalid_begin' => 'The destination must start with the TO',
    'flow_destination_invalid_end'  => 'The destination must end with the TO',
    'flow_can_not_update'           => 'Approval Flow has been used. You can not update.',

    /**
     * Login
     */
    'login_title_login_pass'    => 'LOGIN',
    'login_email_address'       => 'E-Mail',
    'login_password'            => 'Password',
    'login_remember'            => 'Remember me',
    'login_btn_login'           => 'Login',
    'login_link_forgot_pass'    => 'Forgot Password?',
    'login_link_login'          => 'Login Page',
    'login_title_reset_pass'    => 'RESET PASSWORD',
    'login_btn_send_pass'       => 'Confirm',
    'login_title_create_pass'   => 'CREATE NEW PASSWORD',
    'login_confirm_password'    => 'Confirm Password',
    'login_btn_create_pass'     => 'Confirm',

    /**
     * checkip
     */
    'checkip_title'             => 'CONFIRM ACCESS EXTERNAL NETWORK',
    'checkip_content'           => 'The Code has sent to : ',
    'checkip_enter_code'        => 'Enter Code',
    'checkip_btn_confirm'       => 'Confirm',
    'checkip_btn_back'          => 'Back',
    'checkip_valid_not_compare' => 'The Code is not Correct, Please try again !',
    'checkip_valid_expired'     => 'THE CODE HAD EXPRIRED, SENT A NEW CODE!  Please try again !',
    'checkip_mail_subject'      => 'Confirm Access External Network',
    'checkip_mail_content'      => 'The Code Confirm Access External Network',
    'checkip_mail_the_code_is'  => 'The Code is: ',
];
