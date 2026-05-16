<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'grade_id',
        'name',
        'name_ar',
        'section',
        'capacity',
        'enrolled_count',
        'is_public',
        'price',
        'reduction_price',
        'image',
        'description',
        'homeroom_teacher_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'enrolled_count' => 'integer',
            'is_public' => 'boolean',
            'price' => 'decimal:2',
            'reduction_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'class_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'class_id', 'student_id')
            ->wherePivot('status', 'approved')
            ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'class_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'class_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_teacher', 'class_id', 'teacher_id')
            ->withTimestamps();
    }

    public function getRemainingSeatsAttribute()
    {
        return $this->capacity - $this->enrolled_count;
    }

    public function getTotalCoursesPriceAttribute()
    {
        return $this->courses->sum('price');
    }

    public function hasReductionAttribute(): bool
    {
        return !is_null($this->reduction_price) && $this->reduction_price < $this->total_courses_price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}