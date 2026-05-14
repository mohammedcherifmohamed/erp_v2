<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'first_name',
        'last_name',
        'avatar',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function parentProfile()
    {
        return $this->hasOne(ParentProfile::class);
    }

    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function children()
    {
        return $this->hasMany(StudentProfile::class, 'parent_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function approvedEnrollments()
    {
        return $this->hasMany(Enrollment::class, 'approved_by');
    }

    public function coursesTeaching()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function markedAttendances()
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    public function parentInvoices()
    {
        return $this->hasMany(Invoice::class, 'parent_id');
    }

    public function collectedPayments()
    {
        return $this->hasMany(Payment::class, 'collected_by');
    }

    public function teacherContracts()
    {
        return $this->hasMany(TeacherContract::class, 'teacher_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(TeacherWithdrawal::class, 'teacher_id');
    }

    public function processedWithdrawals()
    {
        return $this->hasMany(TeacherWithdrawal::class, 'processed_by');
    }

    public function quizzesCreated()
    {
        return $this->hasMany(Quiz::class, 'teacher_id');
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class, 'student_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'class_teacher', 'teacher_id', 'class_id')
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('studentProfile', fn($q) => $q->where('is_active', true))
            ->orWhereHas('teacherProfile', fn($q) => $q->where('is_active', true));
    }
}