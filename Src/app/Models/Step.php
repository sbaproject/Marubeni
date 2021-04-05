<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    public static function getApproversByGroupId($groupId)
    {
        $cols = [
            'steps.approver_id',
            'steps.approver_type',
            'users.email as approver_mail'
        ];

        return Step::select($cols)
            ->join('users', 'users.id', 'steps.approver_id')
            ->where('group_id', $groupId)
            ->orderBy('steps.approver_type')
            ->orderBy('steps.order')
            ->get();
    }

    public static function getNextApproverCurrentStep($groupId, $nextOrder)
    {
        $cols = [
            'steps.approver_id',
            'steps.approver_type',
            'users.email as approver_mail',
            'users.name as approver_name'
        ];
        return Step::select($cols)
            ->join('users', 'users.id', 'steps.approver_id')
            ->where('group_id', $groupId)
            ->where('select_order', $nextOrder)
            ->first();
    }
}
