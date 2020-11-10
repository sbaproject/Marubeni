<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Budget;
use App\Libs\Common;
use Carbon\Carbon;

class AdminBudgetController extends Controller
{
    public function index(Request $request)
    {
    }

    public function show()
    {
        // Bushiness Trip - Assignment - Economy Class
        $amount1 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.application'))->where('position', config('const.budget.position.economy'))->get('amount')->first();

        // Bushiness Trip - Assignment - Business Class
        $amount2 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.application'))->where('position',  config('const.budget.position.business'))->get('amount')->first();

        // Bushiness Trip - Settlement - Economy Class
        $amount3 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.settlement'))->where('position',  config('const.budget.position.economy'))->get('amount')->first();

        // Bushiness Trip - Settlement - Business Class
        $amount4 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.settlement'))->where('position',  config('const.budget.position.business'))->get('amount')->first();

        // ENTERTAINMENT - Assignment - Not PO
        $amount5 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.application'))->where('position', config('const.budget.position.not_po'))->get('amount')->first();

        // ENTERTAINMENT - Assignment - PO
        $amount6 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.application'))->where('position', config('const.budget.position.po'))->first();

        // ENTERTAINMENT - Settlement - Not PO
        $amount7 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.settlement'))->where('position', config('const.budget.position.not_po'))->get('amount')->first();

        // ENTERTAINMENT - Settlement - PO
        $amount8 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.settlement'))->where('position', config('const.budget.position.po'))->get('amount')->first();

        return view('admin.budget', compact('amount1', 'amount2', 'amount3', 'amount4', 'amount5', 'amount6', 'amount7', 'amount8'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'amount1'   => 'numeric|gt:-1|digits_between:0,10',
            'amount2'   => 'numeric|gt:-1|digits_between:0,10',
            'amount3'   => 'numeric|gt:-1|digits_between:0,10',
            'amount4'   => 'numeric|gt:-1|digits_between:0,10',
            'amount5'   => 'numeric|gt:-1|digits_between:0,10',
            'amount6'   => 'numeric|gt:-1|digits_between:0,10',
            'amount7'   => 'numeric|gt:-1|digits_between:0,10',
            'amount8'   => 'numeric|gt:-1|digits_between:0,10',
        ]);

        $data = $request->input();
        $req_arr = $request->all();

        // Bushiness Trip - Assignment - Economy Class
        $amount1 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.application'))->where('position', config('const.budget.position.economy'))->get()->first();
        $amount1->amount = $data['amount1'];
        $amount1->updated_by = Auth::user()->id;
        $amount1->updated_at = Carbon::now();
        $amount1->save();

        // Bushiness Trip - Assignment - Business Class

        $amount2 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.application'))->where('position',  config('const.budget.position.business'))->get()->first();
        $amount2->amount = $data['amount2'];
        $amount2->updated_by = Auth::user()->id;
        $amount2->updated_at = Carbon::now();
        $amount2->save();

        // Bushiness Trip - Settlement - Economy Class
        $amount3 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.settlement'))->where('position',  config('const.budget.position.economy'))->get()->first();
        $amount3->amount = $data['amount3'];
        $amount3->updated_by = Auth::user()->id;
        $amount3->updated_at = Carbon::now();
        $amount3->save();

        // Bushiness Trip - Settlement - Business Class
        $amount4 = Budget::where('budget_type', config('const.budget.budget_type.business'))->where('step_type', config('const.budget.step_type.settlement'))->where('position',  config('const.budget.position.business'))->get()->first();
        $amount4->amount = $data['amount4'];
        $amount4->updated_by = Auth::user()->id;
        $amount4->updated_at = Carbon::now();
        $amount4->save();

        // ENTERTAINMENT - Assignment - Not PO
        $amount5 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.application'))->where('position', config('const.budget.position.not_po'))->get()->first();
        $amount5->amount = $data['amount5'];
        $amount5->updated_by = Auth::user()->id;
        $amount5->updated_at = Carbon::now();
        $amount5->save();

        // ENTERTAINMENT - Assignment - PO
        $amount6 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.application'))->where('position', config('const.budget.position.po'))->get()->first();
        $amount6->amount = $data['amount6'];
        $amount6->updated_by = Auth::user()->id;
        $amount6->updated_at = Carbon::now();
        $amount6->save();

        // ENTERTAINMENT - Settlement - Not PO
        $amount7 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.settlement'))->where('position', config('const.budget.position.not_po'))->get()->first();
        $amount7->amount = $data['amount7'];
        $amount7->updated_by = Auth::user()->id;
        $amount7->updated_at = Carbon::now();
        $amount7->save();

        // ENTERTAINMENT - Settlement - PO
        $amount8 = Budget::where('budget_type', config('const.budget.budget_type.entertainment'))->where('step_type', config('const.budget.step_type.settlement'))->where('position', config('const.budget.position.po'))->get()->first();
        $amount8->amount = $data['amount8'];
        $amount8->updated_by = Auth::user()->id;
        $amount8->updated_at = Carbon::now();
        $amount8->save();

        return Common::redirectBackWithAlertSuccess(__('msg.save_success'), compact('amount1', 'amount2', 'amount3', 'amount4', 'amount5', 'amount6', 'amount7', 'amount8'));
    }
}
