<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionEnrollment extends Model
{
    protected $fillable = [
        'student_id',
        'section_id',
        'bundle_price_paid',
        'start_date',
        'end_date',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'bundle_price_paid' => 'decimal:2',
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'approved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function section()
    {
        return $this->belongsTo(Classe::class, 'section_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
