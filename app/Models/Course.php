<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'class_id',
        'name',
        'name_ar',
        'code',
        'description',
        'teacher_id',
        'sessions_count',
        'credits',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sessions_count' => 'integer',
            'credits' => 'integer',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function teacherContracts()
    {
        return $this->hasMany(TeacherContract::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}