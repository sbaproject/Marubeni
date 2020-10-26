
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
		'items' => 5 // Items per page
	],
	/**
	 * Employee NO : length of fillzero -- ex: 00001
	 */
	'num_fillzero' => 5
];
