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
        'search' => 'Search',
        'login' => 'Login',
        'logout' => 'Logout',
        'apply' => 'Apply',
        'draft' => 'Draft',
        'export' => 'Export PDF',
        'approval' => 'Approval',
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
    'paginator' => [
        'prev' => 'Previous page',
        'next' => 'Next page',
        'first' => 'First page',
        'last' => 'Last page',
    ],
    'action' => 'Action',
    '_no_' => 'NO.',
    'email_address' => 'E-Mail address',
    'change_pass' => 'Change Password',
    'file' => 'File',
    'choose_file' => 'Choose file',
    /**
     * Form
     */
    'form' => [
        'leave' => 'Leave',
        'biz_trip' => 'Business Trip',
        'entertaiment' => 'Entertaiment',
        // get by id
        '1' => 'Leave',
        '2' => 'Business Trip',
        '3' => 'Entertaiment',
    ],
    /**
     * Menu
     */
    'menu' => [
        'application' => 'APPLICATION',
        'application_list' => 'Application List',
        'draft' => 'DRAFT',
        'status' => 'STATUS',
        'applying' => 'Applying',
        'approved_un' => 'Approved / Under',
        'approved_un2' => ' Payment',
        'approved_in' => 'Approved / In-Processing',
        'approved_in2' => 'Of Payment',
        'declined' => 'Declined',
        'reject' => 'Reject',
        'completed' => 'Completed',
        'approval' => 'APPROVAL',
        'settings' => 'SETTINGS',
        'company_registration' => 'Company Registration',
        'change_password' => 'Change Password',
        'employee_setting' => 'Employee Setting',
        'budget_setting' => 'Budget Setting',
        'approval_flow_setting' => 'Approval Flow Setting',
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
        'borne_by' => 'Expenses to BE Borne by',
        'comment' => 'Comment',
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
    ],
];
