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
        'show_on_landing',
        'duration',
        'max_students',
        'thumbnail',
        'enrolled_count',
    ];

    protected function casts(): array
    {
        return [
            'sessions_count' => 'integer',
            'credits' => 'integer',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'show_on_landing' => 'boolean',
            'max_students' => 'integer',
            'enrolled_count' => 'integer',
        ];
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function section()
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

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function getRemainingSeatsAttribute(): int
    {
        if (!$this->max_students) {
            return 999;
        }
        return $this->max_students - $this->enrolled_count;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeShowOnLanding($query)
    {
        return $query->where('show_on_landing', true);
    }
}