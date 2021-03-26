<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoryApproval extends Model
{
    use HasFactory, Notifiable;

    protected $table = "history_approval";

    /**
     * Get all comments of application
     * @param int $applicationId Application ID
     * @return \Illuminate\Support\Collection
     */
    public static function getApplicationComments($applicationId)
    {
        $cols = [
            'history_approval.comment as content',
            'history_approval.created_at',
            'users.name as user_name'
        ];
        return HistoryApproval::select($cols)
            ->join('users', 'users.id', 'history_approval.approved_by')
            ->where('history_approval.application_id', $applicationId)
            ->orderBy('history_approval.updated_at')
            ->get();
    }
}
