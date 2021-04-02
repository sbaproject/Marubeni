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

    public static function scopeSelectAllColumn($query)
    {
        $cols = [
            'history_approval.comment as content',
            'history_approval.created_at',
            'history_approval.status',
            'history_approval.step',
            'users.name as user_name',
        ];

        return $query->select($cols);
    }

    public static function scopeByConditions($query, array $conditions = [])
    {
        if (array_key_exists('application_id', $conditions)) {
            $query->where('history_approval.application_id', $conditions['application_id']);
        }

        if (array_key_exists('status', $conditions)) {
            $query->where('history_approval.status', $conditions['status']);
        }

        if (array_key_exists('step', $conditions)) {
            $query->where('history_approval.step', $conditions['step']);
        }

        return $query;
    }

    public static function scopeMakeOrder($query, array $orderBy = [])
    {
        if (count($orderBy) > 0) {
            return $query->orderBy($orderBy);
        }

        return $query->orderBy('history_approval.updated_at');
    }

    // 
    /**
     * Get approval histories
     * @param array $conditions Conditions to get
     * @return query
     */
    public static function getHistory(array $conditions)
    {
        return HistoryApproval::SelectAllColumn()
            ->join('users', 'users.id', 'history_approval.approved_by')
            ->ByConditions($conditions)
            ->MakeOrder();
    }
}
