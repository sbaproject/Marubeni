
<?php

return [
	/**
	 * Roles
	 */
	'role' => [
		'Admin' => 99,
		'Staff' => 1,
		'GM' => 2,
		'PIC' => 3,
		'DGD' => 4,
		'GD' => 5
	],
	/**
	 * Locations
	 */
	'location' => [
		'hn' => 0,
		'hcm' => 1
	],
	/**
	 * Approval status
	 */
	'approval' => [
		'on' => 1,
		'off' => 0
	],
	/**
	 * Determine type of alert message
	 */
	'keymsg' => [
		'success' => 'key_msg_success',
		'error' => 'key_msg_error'
	],
	/**
	 * Paginator
	 */
	'paginator' => [
		'items' => 10 // Items per page
	],
	/**
	 * Employee NO : length of fillzero -- ex: 00001
	 */
	'num_fillzero' => 5,
	/**
	 * Code of Leave
	 */
	'code_leave' => [
		'AL' => 0, // ANNUAL LEAVE
		'UL' => 1, // UNPAID LEAVE
		'CL' => 2, // COMPASSIONATE LEAVE
		'WL' => 3, // WEDDING LEAVE
		'PL' => 4, // PERIODIC LEAVE
		'ML' => 5, // MATERNITY LEAVE
		'SL' => 6, // SICK LEAVE
	],
	/**
	 * Type leave of SICK LEAVE code
	 */
	'paid_type' => [
		'AL' => 1, // Annual Leave
		'UL' => 0  // Unpaid Leave
    ],
    /**
     * Forms
     */
    'form' => [
        'leave' => 1,           // Leave Application
        'biz_trip' => 2,        // Business Trip Application
		'entertaiment' => 3,    // Entertaiment Application
		// prefix of form
		'prefix' => [
			1 => 'LA',
			2 => 'BT',
			3 => 'EA',
		]
    ],
    /**
     * applications
     */
    'application' => [
        'status' => [
            'applying' => 0,
            'approvel' => 1,
            'approvel_un' => 2,
            'approvel_in' => 3,
            'draft' => -3,
            'declined' => -1,
            'reject' => -2,
            'completed' => 99,
            'all' => 999,
        ],
	],
	/**
	 * Budgets
	 */
	'budget' => [
		'position' => [
			'po' => 1,
			'not_po' => 2,
			'economy' => 3,
			'business' => 4,
        ],
        'budget_type' => [
			'business' => 2,
			'entertaiment' => 3,
        ],
        'step_type' => [
			'application' => 1,
			'settlement' => 2,
        ],
	]
];
