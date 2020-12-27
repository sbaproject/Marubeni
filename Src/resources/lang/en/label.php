<?php

/**
     * Label
     */

return [

    /**
     * Button
     */
    'button' => [
        'update' => 'Update',
        'change' => 'Change',
        'register' => 'Register',
        'addnew' => 'Add',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'cancel' => 'Cancel',
        'accept' => 'Accept',
        'search' => 'Search',
        'login' => 'Login',
        'logout' => 'Logout',
        'apply' => 'Apply',
        'draft' => 'Draft',
        'export' => 'Export PDF',
        'approval' => 'Approval',
        'preview' => 'Preview',
        'reject' => 'Reject',
        'declined' => 'Decline',
        'processing' => 'Processing',
    ],
    'rememberme' => 'Remember me',
    'forgotpassword' => 'Forgot your password ?',
    'success' => 'Success',
    'fail' => 'Fail',
    'hn' => 'Ha Noi',
    'hcm' => 'Ho Chi Minh',
    'select' => 'Select',
    'on' => 'ON',
    'off' => 'OFF',
    'action' => 'Action',
    '_no_' => 'NO.',
    'email_address' => 'E-Mail address',
    'change_pass' => 'Change Password',
    'file' => 'File',
    'choose_file' => 'Choose file',
    'yes' => 'Yes',
    'no' => 'No',
    'or' => 'Or',
    'keyword' => 'Keyword',
    'application_no' => 'Application No',
    'applicant_name' => 'Applicant name',
    'search_by_app_no_applicant' => 'Search by Application No or Applicant name',
    'pending_approval' => 'Pending Approval Application',
    'application_info' => 'Application Information',
    'no_attached_file' => 'No attached file',
    'comment' => 'Comment',
    'comments' => 'Comments',
    'comment_here' => 'Type your comment here...',
    'applicant' => 'Applicant',
    'confirming_title' => 'Confirm',
    'times' => 'Times',
    'step' => 'Step',
    'status_caption' => 'Status',
    'title' => [
        'user' => [
            'list' => 'Employees',
            'edit' => 'Employee Edit',
            'add' => 'Employee Add',
        ],
        'approval' => [
            'list' => 'Pending approval list',
        ],
    ],
    /**
     * Paginator
     */
    'paginator' => [
        'prev' => 'Previous page',
        'next' => 'Next page',
        'first' => 'First page',
        'last' => 'Last page',
        'show_result_label' => 'Showing :from - :to of :total items.',
    ],
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
    'form' => [
        'leave' => 'Leave',
        'biz_trip' => 'Business Trip',
        'entertainment' => 'Entertainment',
        // get by id
        '1' => 'Leave',
        '2' => 'Business Trip',
        '3' => 'Entertainment',
    ],
    /**
     * Menu
     */
    'menu' => [
        'dashboard' => 'Dashboard',
        'application' => 'Application',
        'application_list' => 'Application List',
        'draft' => 'Draft',
        'status' => 'Status',
        'applying' => 'Applying',
        'approved_un' => 'Approved / Under',
        'approved_un2' => ' Payment',
        'approved_in' => 'Approved / In-Processing',
        'approved_in2' => 'Of Payment',
        'declined' => 'Declined',
        'reject' => 'Reject',
        'completed' => 'Completed',
        'approval' => 'Approval',
        'settings' => 'Settings',
        'company_registration' => 'Company Registration',
        'company_list' => 'Company Management',
        'change_password' => 'Change Password',
        'employee_setting' => 'Employees',
        'budget_setting' => 'Budgets',
        'approval_flow_setting' => 'Approval Flows',
    ],
    /**
     * Application
     */
    'application' => [
        'status' => [
            'applying' => 'Applying',
            'approval' => 'Approval',
            'reject' => 'Rejected',
            'decline' => 'Declined',
            'complete' => 'Completed',
            'draft' => 'Draft',
        ],
    ],
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
        'caption' => [
            'code_leave' => 'Code of Leave',
            'reason_leave' => 'Reason for leave',
            'paid_type' => 'Type of leave to switch to',
            'date_leave' => 'Date of leave',
            'time_leave' => 'Time of leave',
            'maternity_leave' => 'Maternity leave',
            'annual_leave' => 'Annual leave',
            'subsequent' => 'Subsequent',
            'file_path' => 'Attached file',
            'entitled_year' => 'Entitled this year',
            'used_this_year' => 'Used this year',
            'take_this_time' => 'Take this time',
            'remaining' => 'Remaining',
            'days' => 'Days',
            'hours' => 'Hours',
            'date_from' => 'Date of leave (From)',
            'date_to' => 'Date of leave (To)',
            'maternity_from' => 'Maternity leave (From)',
            'maternity_to' => 'Maternity leave (To)',
            'days_use' => 'Take this time (Days)',
        ],
    ],
    /**
     * Business Application
     */
    'business' => [
        'departure' => 'Departure',
        'arrival' => 'Arrival',
        'method' => 'Flight No',
        'application_no' => 'Application No',
        'trip_destination' => 'Trip Destinations',
        'date_trip' => 'Date of Trip',
        'transportation' => 'Itinerary & Transportation',
        'accommodation' => 'Accommodation',
        'accompany' => 'Accompany',
        'borne_by' => 'Expenses to be charged by',
        'comment' => 'Comment',
        'budget_position' => 'Budget type',
    ],
    /**
     * Entertainment Application
     */
    'entertainment' => [
        'application_no' => 'Application No',
        'entertainment_dt' => 'Date & Time',
        'place' => 'Place',
        'during_trip' => 'During biz trip',
        'check_row' => 'Confirmation of Compliance with Laws',
        'entertainment_times' => 'No. of Entertainment for past 1 year',
        'existence_projects' => 'Existence of projects',
        'includes_family' => 'Including Family/Friend',
        'project_name' => 'Project Name',
        'entertainment_reason' => 'Reason for the Entertainment',
        'entertainment_reason_other' => 'Other reason for the Entertainment',
        'entertainment_person' => 'Total Number of Person',
        'est_amount' => 'Estimated Amount',
        'reason_budget_over' => 'Describe if the amount per person exceeds 4mil VND (PO:2mil VND)',
        'cp_name' => 'Company Name',
        'title' => 'Title',
        'name_attendants' => 'Name of Attendants',
        'details_dutles' => 'Details of duties',
        'if_need' => 'If need',
        'persons' => 'Persons',
        'per_person_excluding_vnd' => 'Per Person(Excluding VAT)',
        'entrainment_infomation' => 'Entertainment Infomation',
        'budget_position' => 'Budget type',
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
    'application_list' => 'Application List',
    'leave_application' => 'Leave application',
    'biz_application' => 'Business Trip Application',
    'entertaiment_application' => 'Entertaiment Application',
    /**
     * Date From - Date To (Search)
     */
    'from' => 'From',
    'to' => 'To',
    'date' => 'Date',
    'date_from' => 'From',
    'date_to' => 'To',
    /**
     * Dashboard
     */
    'dashboard' => [
        //form
        'list_application' => 'List of All Applications',
        'application_no' => 'Application No',
        'application_name' => 'Application Name',
        'status' => 'Status',
        'apply_date' => 'Apply Date',
        'view_details' => 'View Details',
        'applying' => 'Applying',
        'approval' => 'Approval',
        'declined' => 'Declined',
        'reject' => 'Reject',
        'completed' => 'Completed',
    ],
    /**
     * Draft
     */
    'draft' => [
        //Draft
        'no' => 'No',
        'application_name' => 'Application Type',
        'date_create' => 'Apply Date',
        'action' => 'Actions',
        'list' => 'Draft list',
    ],
    /**
     * Status
     */
    'status' => [
        //forms
        'list_of_applying_documents' => 'List of Applying Documents',
        'list_of_approval_documents' => 'List of Approved Documents',
        'list_of_approval_un_documents' => 'List of Approved/Under payment Documents',
        'list_of_approval_in_documents' => 'List of Approved/in-processing of payment Documents',
        'list_of_declined_documents' => 'List of Declined Documents',
        'list_of_reject_documents' => 'List of Reject Documents',
        'list_of_completed_documents' => 'List of Completed Documents',
        'no' => 'No',
        'application_type' => 'Application Type',
        'apply_date' => 'Apply Date',
        'next_approver' => 'Next Approver',
        'view_details' => 'View Details',
    ],
    /**
     * Company
     */
    'company' => [
        'keyword' => 'Keyword',
        'company_registration' => 'Company Registration',
        //forms
        'no' => 'No',
        'company_information' => 'Company Information',
        'company_id' => 'Company ID',
        'company_name' => 'Company Name',
        'company_country' => 'Country',
        'company_tell' => 'Tell',
        'company_address' => 'Address',
        'att_information' => 'Attendants Information',
        'att_name' => 'Attendants Name',
        'att_department' => 'Department',
        'att_email' => 'E-mail',
        'text' => 'Text',
        'action' => 'Actions',
        'name' => 'Name',
        'check_exist_com_name' => "This Company's Name has existed !",
    ],
    /**
     * Budget
     */
    'budget' => [
        'business_trip' => 'BUSINESS TRIP',
        'assignment' => 'Assignment',
        'settlement' => 'Settlement',
        'economy_class' => 'Economy Class',
        'business_class' => 'Business Class',
        'pre_approvel_settlement_for_entertainment_free' => 'PRE-APPROVAL & SETTLEMENT FOR ENTERTAINMENT FEE',
        'not_po' => 'Not PO',
        'po' => 'PO',
    ],
    /**
     * Flow
     */
    'flow' => [
        'approval_flow_list' => 'Approval Flow List',
        'no' => 'No',
        'flow_name' => 'Flow Name',
        'step' => 'Step',
        'final_approver' => 'Final Approver',
        'Actions' => 'Actions',
        'approval_flow_setting' => 'Approval Flow Setting',
        'approval_no' => 'Approval No',
        'approval_flow_name' => 'Approval Flow Name',
        'application_form' => 'Application Form',
        'type' => 'Type',
        'applicant_role' => 'Applicant Role',
        'budget_for_per_person' => 'Budget for per person',
        'less_or_equal_than' => 'Less Or Equal than',
        'greater_than' => 'Greater than',
        'approver' => 'Approver',
        'step' => 'Step',
        'destination' => 'Destination',
        'add' => 'Add',
        'submit' => 'Submit',
        'delete' => 'Delete',
        'update' => 'Update',
        'cancel' => 'cancel',
        'approver_required' => 'Please select a approver',
        'destination_invalid_begin' => 'The destination must start with the TO',
        'destination_invalid_end' => 'The destination must end with the TO',
        'can_not_update' => 'Approval Flow has been used. You can not update.',
    ],
    /**
     * Login
     */
    'login' => [
        //form login
        'title_login_pass' => 'LOGIN',
        'email_address' => 'E-Mail',
        'password' => 'Password',
        'remember' => 'Remember me',
        'btn_login' => 'Login',
        'link_forgot_pass' => 'Forgot Password?',
        //form fogot password
        'title_reset_pass' => 'RESET PASSWORD',
        'btn_send_pass' => 'Confirm',
        //form create new password
        'title_create_pass' => 'CREATE NEW PASSWORD',
        'confirm_password' => 'Confirm Password',
        'btn_create_pass' => 'Confirm',
    ],
    /**
     * checkip
     */
    'checkip' => [
        //form
        'title' => 'CONFIRM ACCESS EXTERNAL NETWORK',
        'content' => 'The Code has sent to : ',
        'enter_code' => 'Enter Code',
        //btn
        'btn_confirm' => 'Confirm',
        'btn_back' => 'Back',
        //Validation
        'valid_not_compare' => 'The Code is not Correct, Please try again !',
        'valid_expired' => 'THE CODE HAD EXPRIRED, SENT A NEW CODE!  Please try again !',
        //Mail content
        'mail_subject' => 'Confirm Access External Network',
        'mail_content' => 'The Code Confirm Access External Network',
        'mail_the_code_is' => 'The Code is: ',
    ],
];
