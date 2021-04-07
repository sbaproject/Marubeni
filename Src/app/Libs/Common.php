<?php

namespace App\Libs;

use App\Models\User;
use Illuminate\Support\Str;
use App\Jobs\SendMailBackGround;
use App\Mail\ApplicationNoticeMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

	/**
	 * Generate status application with badge html style
	 * @param int $status Current status of application
	 * @param int $currentStep Current step of application
	 * @return string status badge html
	 */
	public static function generateStatusApplicationBadgeStyle($status, $currentStep)
	{
		if ($status >= 0 && $status <= 98 && $currentStep == config('const.application.step_type.application')) {
			$statusName = __('label.application_status_applying');
			$statusBadgeCss = 'badge-success';
		} elseif ($status == config('const.application.status.declined')) {
			$statusName = __('label.application_status_decline');
			$statusBadgeCss = 'badge-warning';
		} elseif ($status == config('const.application.status.reject')) {
			$statusName = __('label.application_status_reject');
			$statusBadgeCss = 'badge-secondary';
		} elseif ($status == config('const.application.status.completed')) {
			$statusName = __('label.application_status_complete');
			$statusBadgeCss = 'badge-primary';
		} elseif ($status == config('const.application.status.draft')) {
			$statusName = __('label.application_status_draft');
			$statusBadgeCss = 'badge-info';
		} else {
			$statusName = __('label.application_status_approval');
			$statusBadgeCss = 'badge-danger';
		}

		$statusBadgeHtml = "<span class='badge {$statusBadgeCss}'>{$statusName}</span>";

		return $statusBadgeHtml;
	}

	public static function generateBadgeByApprovalStatus($status, $step)
	{

		if ($status >= 0 && $status <= 98 && $step == config('const.application.step_type.application')) {
			$statusName = __('label.approval_action_approval');
			$statusBadgeCss = 'badge-success';
		} elseif ($status == config('const.application.status.completed') && $step == config('const.application.step_type.settlement')) {
			$statusName = __('label.approval_action_complete');
			$statusBadgeCss = 'badge-primary';
		} elseif ($status == config('const.application.status.declined')) {
			$statusName = __('label.approval_action_decline');
			$statusBadgeCss = 'badge-warning';
		} elseif ($status == config('const.application.status.reject')) {
			$statusName = __('label.approval_action_reject');
			$statusBadgeCss = 'badge-danger';
		} else {
			$statusName = __('label.approval_action_approval');
			$statusBadgeCss = 'badge-success';
		}

		$statusBadgeHtml = "<span class='badge {$statusBadgeCss}'>{$statusName}</span>";

		return $statusBadgeHtml;
	}

	/**
	 * Detect mobile device
	 */
	public static function detectMobile(){
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));
	}

	/**
	 * Detect Microsoft Edge Browser
	 */
	public static function detectEdgeBrowser(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		return preg_match('/Edge/i', $user_agent) || preg_match('/Edga/i', $user_agent)  || preg_match('/Edgi/i', $user_agent);
	}

	/**
	 * Detect client is accsessing by mobile device (with MS Edge Browser only).
	 */
	public static function isMobileWithMSEdgeBrowser() {
		return Common::detectMobile() && Common::detectEdgeBrowser();
	}

	/**
	 * Get auto-increment id of table
	 * @param string $schemaName Name of schema (database name)
	 * @param string $tableName Table wants to get auto-increment id
	 * @return int Auto-increment id
	 */
	public static function getAutoIncrementId($schemaName, $tableName){

		return \Illuminate\Support\Facades\DB::table('INFORMATION_SCHEMA.TABLES')
				->select('AUTO_INCREMENT')
				->where('table_schema', $schemaName)
				->where('table_name', $tableName)
				->first()->AUTO_INCREMENT;
	}

	public static function sendApplicationNoticeMail($mailTpl, $title, $to, $cc, $msgParams){

		// $mailTpl = 'mails.mail_application_notice';
		$mailable = new ApplicationNoticeMail($mailTpl, $title, $msgParams);
		
		// send to queue
		// if using queue, must to run command: php aritsan queue:listen --queue=[queue_name_here]
		// SendMailBackGround::dispatch($mailable, $to, $cc)->onQueue(config('const.queue_application_notice_mail_name'));
		
		// run job immediately (not send to job)
		// run with current response so it take long time
		SendMailBackGround::dispatchNow($mailable, $to, $cc);
	}
}
