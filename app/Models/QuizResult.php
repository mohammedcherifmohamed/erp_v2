<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = [
        'quiz_id',
        'student_id',
        'score',
        'total_points',
        'answers',
        'started_at',
        'submitted_at',
        'is_auto_corrected',
        'corrected_at',
        'corrected_by',
        'feedback',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'json',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'corrected_at' => 'datetime',
            'is_auto_corrected' => 'boolean',
        ];
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function corrector()
    {
        return $this->belongsTo(User::class, 'corrected_by');
    }
}