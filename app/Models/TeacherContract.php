<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherContract extends Model
{
    protected $fillable = [
        'teacher_id',
        'course_id',
        'class_id',
        'contract_type',
        'rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}