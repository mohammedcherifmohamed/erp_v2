<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'arabic_name',
        'profession',
        'company',
        'secondary_phone',
        'relationship',
        'receive_notifications',
    ];

    protected function casts(): array
    {
        return [
            'receive_notifications' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}