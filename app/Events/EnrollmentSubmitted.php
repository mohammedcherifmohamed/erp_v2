<?php

namespace App\Events;

use App\Models\Enrollment;
use Illuminate\Foundation\Events\Dispatchable;

class EnrollmentSubmitted
{
    use Dispatchable;

    public function __construct(public Enrollment $enrollment) {}
}
