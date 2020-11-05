<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Libs\Common;

class UserDraftController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;

        $list_application_draft =  Application::where('created_by', $userId)
        ->where('applications.status', config('const.application.status.draft'))
        ->whereNull('applications.deleted_at')
        ->orderBy('created_at', 'DESC')->paginate(10);



        return view('user.draft', compact('list_application_draft'));
    }

    public function delete($id)
    {
        $user = Application::find($id);
        $user->delete();
        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }
}
