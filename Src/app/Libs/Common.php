<?php

namespace App\Libs;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Session;

class Common
{
	/**
	 * redirect to back location with alert success message
	 * @param string|null $msg - alert success message
	 */
	public static function redirectBackWithAlertSuccess($msg = null)
	{
		// show alert success message on the top of the form
		static::setAlertSuccess($msg);

		return redirect()->back();
	}

	/**
	 * redirect to back location with alert fail message
	 * @param string|null $msg - alert fail message
	 */
	public static function redirectBackWithAlertFail($msg = null)
	{
		// show fail success message on the top of the form
		static::setAlertFail($msg);

		return redirect()->back();
	}

	/**
	 * redirect to special routing with alert success message
	 * @param string|null $msg - alert success message
	 */
	public static function redirectRouteWithAlertSuccess($route, $msg = null)
	{
		// show alert success message on the top of the form
		static::setAlertSuccess($msg);

		return redirect()->route($route);
	}

	/**
	 * redirect to special routing with alert fail message
	 * @param string|null $msg - alert fail message
	 */
	public static function redirectRouteWithAlertFail($route, $msg = null)
	{
		// show alert fail message on the top of the form
		static::setAlertFail($msg);

		return redirect()->route($route);
	}

	/**
	 * redirect to view with alert fail message
	 * @param mixed $viewName - view of name
	 * @param array $compact - compact data (passing data to blade view)
	 * @param string $msg - alert fail message
	 */
	public static function redirectViewWithAlertFail($viewName, $compact = [], $msg = null)
	{
		// show alert fail message on the top of the form
		static::setAlertFail($msg);
		// return view
		return view($viewName, $compact);
	}

	/**
	 * Show aler success message on the top of the form
	 */
	public static function setAlertSuccess($msg = null)
	{
		if (isEmpty($msg)) {
			$msg = __('msg.save_success');
		}
		Session::flash(config('const.keymsg.success'), $msg);
	}

	/**
	 * Show aler fail message on the top of the form
	 */
	public static function setAlertFail($msg = null)
	{
		if (isEmpty($msg)) {
			$msg = __('msg.save_fail');
		}
		Session::flash(config('const.keymsg.error'), $msg);
	}

	/**
	 * Set locale by selected locale from user
	 */
	public static function setLocale($locale = null)
	{
		if (!Auth::check()) {
			return;
		}

		if (empty($locale)) {
			// if (Session::has('locale')) {
			// 	$locale = Session::get('locale');
			// } else {
			// 	$locale = Auth::user()->locale;
			// }
			$locale = Auth::user()->locale;
		}

		// set new locale for app
		App::setlocale($locale);
		
		// set locale session
		/*Session::put('locale', $locale);*/

		// save new locale to user
		$user = User::find(Auth::user()->id);
		$user->locale = $locale;
		$user->save();
	}
}
