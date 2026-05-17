<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class TeacherApplied
{
    use Dispatchable;

    public function __construct(public User $teacher) {}
}
