<?php

namespace App\Models;

use App\Events\EnrollmentApproved;
use App\Events\EnrollmentRejected;
use App\Events\EnrollmentSubmitted;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'course_id',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'start_date',
        'end_date',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'expires_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Enrollment $enrollment) {
            if ($enrollment->status === 'pending') {
                event(new EnrollmentSubmitted($enrollment));
            }
        });

        static::updated(function (Enrollment $enrollment) {
            if ($enrollment->wasChanged('status')) {
                match ($enrollment->status) {
                    'approved' => event(new EnrollmentApproved($enrollment)),
                    'rejected' => event(new EnrollmentRejected($enrollment)),
                    default => null,
                };
            }
        });
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
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