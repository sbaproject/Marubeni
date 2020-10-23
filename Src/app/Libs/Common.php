<?php

namespace App\Libs;

use Illuminate\Support\Facades\Session;
use function PHPUnit\Framework\isEmpty;

class Common {
	/**
	 * redirect to back location with alert success message
	 * @param string|null $msg - alert success message
	 */
	public static function redirectBackWithAlertSuccess($msg = null){
		// show alert success message on the top of the form
		if(isEmpty($msg)){
			$msg = __('msg.save_success');
		}
		Session::flash(KEY_SUCCESS, $msg);
		return redirect()->back();
	}

	/**
	 * redirect to view with alert fail message
	 * @param mixed $viewName - view of name
	 * @param array $compact - compact data (passing data to blade view)
	 * @param string $msg - alert fail message
	 */
	public static function redirectViewWithAlertFail($viewName, $compact = [], $msg = null){
		// show alert fail message on the top of the form
		if (isEmpty($msg)) {
			$msg = __('msg.save_fail');
		}
		Session::flash(KEY_ERROR, $msg);
		// return view
		return view($viewName, $compact);
	}
}