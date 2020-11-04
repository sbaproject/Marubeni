<?php

namespace App\Libs;

use App\Models\User;
use Illuminate\Support\Str;
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
		if (empty($msg)) {
			$msg = __('msg.save_success');
		}
		Session::flash(config('const.keymsg.success'), $msg);
	}

	/**
	 * Show aler fail message on the top of the form
	 */
	public static function setAlertFail($msg = null)
	{
		if (empty($msg)) {
			$msg = __('msg.save_fail');
		}
		Session::flash(config('const.keymsg.error'), $msg);
	}

	/**
	 * Set locale by selected locale from user
	 */
	public static function setLocale($locale = null)
	{
		if (empty($locale)) {

			// default locale
			$locale = config('app.locale');

			if (Auth::check()) {
				if (Session::has('set_locale')) {
					$locale = Session::get('set_locale');
					Session::forget('set_locale');
				} else {
					$locale = Auth::user()->locale;
				}
			} else if (Session::has('set_locale')) {
				$locale = Session::get('set_locale');
			} else {
				// get current language of client browser
				if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
					$langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
					$checklanguages = ['vi', 'en'];
					foreach ($langs as $value) {
						$lang = substr($value, 0, 2);
						if (in_array(Str::lower($lang), $checklanguages)) {
							$locale = $lang;
							break;
						}
					}
				}
				Session::put('set_locale', $locale);
			}
		}

		// set new locale for app
		App::setlocale($locale);

		// save new locale to user
		if (Auth::check()) {
			$user = User::find(Auth::user()->id);
			if ($locale !== $user->locale) {
				$user->locale = $locale;
				$user->save();
			}
		} else {
			Session::put('set_locale', $locale);
		}
	}
}
