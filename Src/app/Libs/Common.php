<?php

namespace App\Libs;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
				$user->timestamps = false; // disable updated_at field
				$user->save();
			}
		} else {
			Session::put('set_locale', $locale);
		}
	}

	/**
	 * Get route of modified application
	 */
	public static function getRouteEditApplication($appId, $formId)
	{
		if ($formId === config('const.form.leave')) {
			return route('user.leave.show', $appId);
		} elseif ($formId === config('const.form.biz_trip')) {
			return route('user.business.show', $appId);
		} elseif ($formId === config('const.form.entertainment')) {
			return route('user.entertainment.show', $appId);
		}
		return '';
	}

	/**
	 * Get route of preview application
	 */
	public static function getRoutePreviewApplication($appId, $formId)
	{
		if ($formId === config('const.form.leave')) {
			return route('user.leave.preview', $appId);
		} elseif ($formId === config('const.form.biz_trip')) {
			return route('user.business.preview', $appId);
		} elseif ($formId === config('const.form.entertainment')) {
			return route('user.entertainment.preview', $appId);
		}
		return '';
	}

	/**
	 * Redirect to home page
	 */
	public static function redirectHome()
	{
		return redirect(static::getHomeUrl());
	}

	/**
	 * Get URL of Home page
	 */
	public static function getHomeUrl()
	{
		if (Auth::check()) {
			// for admin
			if (Gate::allows('admin-gate')) {
				return route('admin.dashboard');
			}
			// for user
			return route('user.dashboard');
		} else {
			return route('login');
		}
	}

	/**
	 * Get list of sorting header columns.
	 * @param Request $request :  $request object of page.
	 * @param array $sort_name_cols : array of name columns want to order. Example: ['col_name_1' => 'caption_1','col_name_2' => 'caption_2',...].
	 * @param int $default_sort_col : default sorting column that based on array $sort_name_cols. Begin of index is 0.
	 * @param int $default_sort_direction : default sorting direction . 0 -> asc | 1 -> desc.
	 * @return object include 's' is order column | 'd' is order direction | 'headers' is list of title header columns.
	 */
	public static function getSortable($request, $sort_name_cols, $default_sort_col, $default_sort_direction, $isOrderRaw = false /*, $icon_show = 1*/)
	{
		$sort_directs = ['asc', 'desc'];

		$sort = (object)[
			's'			=> array_keys($sort_name_cols)[$default_sort_col],
			'd'			=> $sort_directs[$default_sort_direction],
			'order_by'	=> null,
			'headers' 	=> null
		];

		if ($request->query("s") != null && $request->query("d") != null) {
			try {
				$sort->s = array_keys($sort_name_cols)[(int)$request->query("s")];
				$sort->d = $sort_directs[(int)$request->query("d")];
			} catch (\Throwable $th) {
				$sort->s = $default_sort_col;
				$sort->s = $default_sort_direction;
			}
		}

		$sort->s = $sort->s != null ? $sort->s : array_keys($sort_name_cols)[$default_sort_col];
		$sort->d = $sort->d != null ? $sort->d : $sort_directs[$default_sort_direction];

		if ($isOrderRaw) {
			$sort->order_by = "{$sort->s} {$sort->d}";
		} else {
			$sort->order_by = [$sort->s => $sort->d];
		}

		$queryStrings = $request->query();

		foreach ($sort_name_cols as $key => $value) {

			$title				= $value;
			$icon_direction		= ' <i class="fa fa-sort fa-1" aria-hidden="true"></i>';
			$queryStrings['s']	= array_search($key, array_keys($sort_name_cols));
			$queryStrings['d']	= 0;
			$activeCls			= ''; // active class CSS(current sorting column)

			if ($sort->s == $key) {
				if (array_search($sort->d, $sort_directs) == 0) {
					$title				= $value;
					$icon_direction		= ' ▲';
					$queryStrings['d']	= 1;
				} else {
					$title				= $value;
					$icon_direction		= ' ▼';
					$queryStrings['d']	= 0;
				}
				$activeCls = 'stb-selected';
			}
			$url = url()->current() . '?' . http_build_query($queryStrings);
			// if (!$icon_show) $icon_direction = '';
			$prop = (object)[
				'title' => "<a href='{$url}' style='display:none'>{$title}</a><span>{$title}</span>{$icon_direction}",
				'activeCls' => $activeCls
			];

			$sort->headers[$key] = $prop;
		}

		return $sort;
	}
}
