<?php

namespace App\Events;

use App\Models\Enrollment;
use Illuminate\Foundation\Events\Dispatchable;

class EnrollmentRejected
{
    use Dispatchable;

    public function __construct(public Enrollment $enrollment) {}
}
