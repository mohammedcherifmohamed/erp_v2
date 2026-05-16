<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id',
        'arabic_name',
        'gender',
        'date_of_birth',
        'nationality',
        'id_card_number',
        'hire_date',
        'hourly_rate',
        'wallet_balance',
        'pending_balance',
        'bio',
        'cv_path',
        'specialization',
        'is_active',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'hourly_rate' => 'decimal:2',
            'wallet_balance' => 'decimal:2',
            'pending_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->user->avatar) {
            return asset('storage/' . $this->user->avatar);
        }

        $gender = $this->gender ?? 'male';
        $name = urlencode($this->user->full_name);
        $style = $gender === 'female' ? 'adventurer' : 'adventurer-neutral';
        return "https://api.dicebear.com/7.x/{$style}/svg?seed={$name}";
    }
}