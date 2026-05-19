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
        'bundle_price',
        'bundle_discount_type',
        'bundle_discount_value',
        'show_bundle_on_landing',
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
            'bundle_price' => 'decimal:2',
            'bundle_discount_value' => 'decimal:2',
            'show_bundle_on_landing' => 'boolean',
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

    public function sectionEnrollments()
    {
        return $this->hasMany(SectionEnrollment::class, 'section_id');
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

    public function hasBundleDiscountAttribute(): bool
    {
        return $this->bundle_discount_type !== 'none' && $this->bundle_discount_value > 0;
    }

    public function getBundleDiscountedPriceAttribute(): ?float
    {
        if (!$this->has_bundle_discount || !$this->bundle_price) {
            return $this->bundle_price;
        }

        return match ($this->bundle_discount_type) {
            'percentage' => $this->bundle_price * (1 - $this->bundle_discount_value / 100),
            'fixed' => max(0, $this->bundle_price - $this->bundle_discount_value),
            default => $this->bundle_price,
        };
    }

    public function getBundleSavingsAttribute(): ?float
    {
        return $this->total_courses_price - ($this->bundle_discounted_price ?? $this->total_courses_price);
    }

    public function getBundleSavingsPercentAttribute(): ?int
    {
        if (!$this->total_courses_price || $this->total_courses_price <= 0) {
            return 0;
        }
        return (int) round(($this->bundle_savings / $this->total_courses_price) * 100);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeShowBundleOnLanding($query)
    {
        return $query->where('show_bundle_on_landing', true);
    }
}