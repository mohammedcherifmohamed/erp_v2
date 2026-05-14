<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'class_id',
        'is_global',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_global' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where(function ($q) use ($classId) {
            $q->where('class_id', $classId)->orWhere('is_global', true);
        });
    }
}