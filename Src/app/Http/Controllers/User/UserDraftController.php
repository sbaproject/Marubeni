<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Libs\Common;
use App\Models\Leave;
use App\Models\Entertaiment;
use App\Models\Businesstrip;

use function PHPUnit\Framework\isNull;

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
        $app_draft = Application::find($id);
        if (!empty($app_draft)) {
            $app_draft->delete();
        }

        $leave = Leave::find($id);
        if (!empty($leave)) {
            $leave->delete();
        }

        $enter = Entertaiment::find($id);
        if (!empty($enter)) {
            $enter->delete();
        }

        $busi = Businesstrip::find($id);
        if (!empty($busi)) {
            $busi->delete();
        }

        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }
}
