<?php

namespace App\Models;

use App\Models\Transportation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinesstripSettlement extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'destinations',
        'number_of_days',
        'total_daily_allowance',
        'total_daily_unit',
        'total_daily_rate',
        'daily_allowance',
        'daily_unit',
        'daily_rate',
        'other_fees',
        'other_fees_unit',
        'other_fees_rate',
        'other_fees_note',
        'charged_to',
        'under_instruction_date',
        'under_instruction_approval_no',
        'other_fees_note',
        'other_fees_note',
        'file_path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function transportations()
    {
        return $this->hasMany(Transportation::class);
    }
}
