<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'student_id',
        'parent_id',
        'class_id',
        'section_enrollment_id',
        'total_amount',
        'reduction_amount',
        'reduction_reason',
        'paid_amount',
        'remaining_amount',
        'status',
        'due_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'reduction_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'due_date' => 'date:Y-m-d',
        ];
    }

    public function netAmount(): float
    {
        return $this->total_amount - $this->reduction_amount;
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function sectionEnrollment()
    {
        return $this->belongsTo(SectionEnrollment::class, 'section_enrollment_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->whereIn('status', ['unpaid', 'partial'])
                    ->where('due_date', '<', now());
            });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}