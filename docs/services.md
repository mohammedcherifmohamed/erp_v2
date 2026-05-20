# Services Layer

This document describes the business logic encapsulated in the service classes under `app/Services/`. Controllers delegate complex operations to these services, keeping the controller layer thin and focused on HTTP concerns.

## Service Overview

| Service                      | Location                                    | Dependencies                           |
|------------------------------|---------------------------------------------|----------------------------------------|
| `AuditService`               | `app/Services/AuditService.php`             | None                                   |
| `EnrollmentService`          | `app/Services/EnrollmentService.php`        | AuditService, InvoiceService           |
| `SectionEnrollmentService`   | `app/Services/SectionEnrollmentService.php` | AuditService, InvoiceService           |
| `InvoiceService`             | `app/Services/InvoiceService.php`           | AuditService                           |
| `TeacherPaymentService`      | `app/Services/TeacherPaymentService.php`    | AuditService                           |
| `QuizService`                | `app/Services/QuizService.php`              | AuditService                           |
| `ScheduleService`            | `app/Services/ScheduleService.php`          | AuditService                           |

All services use constructor-based dependency injection and are resolved automatically by Laravel's service container.

---

## AuditService

Provides a unified interface for recording audit logs for all operations.

### Methods

#### `log(string $action, $model, ?array $oldValues, ?array $newValues): AuditLog`
Records a generic audit log entry.

#### `logCreate($model, array $data): AuditLog`
Logs a `created` action with the new values.

#### `logUpdate($model, array $oldValues, array $newValues): AuditLog`
Logs an `updated` action with both old and new values.

#### `logDelete($model, array $data): AuditLog`
Logs a `deleted` action with the deleted values.

#### `logAuth(string $action, ?array $data): AuditLog`
Logs authentication events (`login`, `logout`, `register`) without a model.

### Logged Information
Each audit log captures:
- `user_id` — Authenticated user performing the action
- `action` — Type of action (created, updated, deleted, login, logout, register)
- `model_type` — Fully-qualified Eloquent model class name
- `model_id` — ID of the affected record
- `old_values` / `new_values` — JSON snapshots of data before and after
- `ip_address` — Client IP address
- `user_agent` — Client browser user agent

---

## EnrollmentService

Manages the enrollment lifecycle for individual class or course enrollments.

### Methods

#### `submit(array $data): Enrollment`
Creates a new pending enrollment.

**Input:** `student_id`, `class_id`
**Process:**
1. Creates enrollment with `pending` status
2. Sets 7-day expiration (`expires_at`)
3. Logs the creation via AuditService
4. Returns enrollment with `student` and `classe` relationships loaded

#### `approve(Enrollment $enrollment, ?string $startDate, ?string $endDate): Enrollment`
Approves a pending enrollment.

**Process (within DB transaction):**
1. Updates enrollment status to `approved`, sets `approved_by` and `approved_at`
2. Sets `start_date` (default: today) and `end_date` (default: 1 year from today)
3. Increments the class's `enrolled_count`
4. Generates an invoice via InvoiceService
5. Logs the status change via AuditService

#### `reject(Enrollment $enrollment, string $reason): Enrollment`
Rejects an enrollment with a reason.

**Process (within DB transaction):**
1. Updates status to `rejected` with `rejection_reason`
2. Logs the status change

#### `archive(Enrollment $enrollment): Enrollment`
Archives an enrollment (sets status to `archived`).

#### `getPendingEnrollments(int $perPage = 15)`
Returns paginated pending enrollments with student profiles and class details.

#### `getEnrollmentsByClass(int $classId, int $perPage = 15)`
Returns paginated enrollments for a specific class.

#### `getEnrollmentsByStudent(int $studentId, int $perPage = 15)`
Returns paginated enrollments for a specific student.

#### `getAvailableSeats(Classe $classe): int`
Calculates remaining seats (`capacity - enrolled_count`).

#### `canEnroll(Classe $classe): bool`
Returns `true` if the class has available seats.

---

## SectionEnrollmentService

Manages bundle enrollments — enrolling a student in all courses of a section at once.

### Methods

#### `submit(User $student, Classe $section, ?int $courseId = null): SectionEnrollment`
Creates a pending bundle enrollment.

**Process (within DB transaction):**
1. Creates a `SectionEnrollment` with `pending` status
2. Calculates `bundle_price_paid` from the section's discounted price
3. Sets 7-day expiration
4. Logs the creation

#### `approve(SectionEnrollment $enrollment, ?string $startDate, ?string $endDate): SectionEnrollment`
Approves a bundle enrollment and auto-enrolls in all section courses.

**Process (within DB transaction):**
1. Updates enrollment status to `approved`
2. Increments section's `enrolled_count`
3. For each course in the section:
   - Creates an individual `Enrollment` with `approved` status (if not already enrolled)
   - Increments course's `enrolled_count`
4. Generates a bundle invoice via InvoiceService
5. Logs the status change

#### `reject(SectionEnrollment $enrollment, string $reason): SectionEnrollment`
Rejects a bundle enrollment with a reason.

#### `archive(SectionEnrollment $enrollment): SectionEnrollment`
Archives a bundle enrollment.

#### `getRemainingBundleSeats(Classe $section): int`
Calculates remaining bundle seats.

#### `canEnrollBundle(Classe $section): bool`
Returns `true` if the section has available bundle seats.

---

## InvoiceService

Handles invoice generation and payment processing.

### Methods

#### `generateForEnrollment(Enrollment $enrollment): Invoice`
Generates an invoice when an enrollment is approved.

**Pricing logic:**
- If the enrollment has a `course_id` → uses the course's price
- If the enrollment is for a full class → uses the class's `reduction_price` (if available), or `total_courses_price`, or `price`

**Invoice number format:** `INV-YYYY-XXXXXX` (zero-padded sequential)

#### `generateForSectionEnrollment(SectionEnrollment $enrollment): Invoice`
Generates an invoice for a bundle enrollment approval.

**Pricing logic:** Uses `bundle_price_paid` → `bundle_discounted_price` → `bundle_price` → `total_courses_price` (fallback chain)

#### `recordPayment(Invoice $invoice, float $amount, ?string $method, ?string $notes): Payment`
Records a payment against an invoice.

**Process (within DB transaction):**
1. Creates a `Payment` record
2. Updates invoice: `paid_amount`, `remaining_amount`, and `status`
3. Status determination:
   - `remaining <= 0` → `paid`
   - `paid > 0` → `partial`
   - Otherwise → `unpaid`

#### `getOverdueInvoices(int $perPage = 15)`
Returns paginated overdue invoices.

#### `getStudentInvoices(int $studentId, int $perPage = 15)`
Returns paginated invoices for a specific student.

#### `markOverdue(): int`
Batch-updates invoices with status `unpaid` or `partial` past their `due_date` to `overdue`.

---

## TeacherPaymentService

Manages teacher earnings calculation and withdrawal processing.

### Methods

#### `calculateTeacherEarnings(User $teacher, ?string $fromDate, ?string $toDate): array`
Calculates total earnings for a teacher across all active contracts.

**Return structure:**
```php
[
    'total' => float,           // Total earnings
    'breakdown' => [            // Per-contract breakdown
        ['contract' => TeacherContract, 'earnings' => float],
    ],
    'wallet_balance' => float,  // Current wallet balance
    'pending_balance' => float, // Pending balance
]
```

#### `calculateContractEarnings(TeacherContract $contract, ?string $fromDate, ?string $toDate): float`
Calculates earnings for a single contract based on its type:

| Contract Type  | Calculation                                              |
|----------------|----------------------------------------------------------|
| `per_session`  | Number of marked attendances × rate                      |
| `per_student`  | Class enrolled count × rate                              |
| `percentage`   | Sum of paid amounts from student invoices × (rate / 100) |
| `monthly`      | Fixed monthly rate                                       |

#### `processWithdrawal(User $teacher, float $amount, string $method, ?string $accountNumber): TeacherWithdrawal`
Creates a withdrawal request.

**Process (within DB transaction):**
1. Validates sufficient wallet balance
2. Decrements teacher's `wallet_balance`
3. Creates `TeacherWithdrawal` with `pending` status
4. Logs the creation

Throws `RuntimeException` if insufficient balance.

#### `approveWithdrawal(TeacherWithdrawal $withdrawal): TeacherWithdrawal`
Approves a pending withdrawal.

#### `completeWithdrawal(TeacherWithdrawal $withdrawal): TeacherWithdrawal`
Marks a withdrawal as completed.

#### `rejectWithdrawal(TeacherWithdrawal $withdrawal, string $reason): TeacherWithdrawal`
Rejects a withdrawal and refunds the amount to the teacher's wallet.

**Process (within DB transaction):**
1. Updates withdrawal status to `rejected` with reason
2. Increments teacher's `wallet_balance` by the withdrawal amount

#### `getPendingWithdrawals(int $perPage = 15)`
Returns paginated pending withdrawal requests.

---

## QuizService

Manages the quiz lifecycle including creation, submission, and correction.

### Methods

#### `createQuiz(array $data): Quiz`
Creates a quiz with questions.

**Process (within DB transaction):**
1. Creates the `Quiz` record
2. Creates `QuizQuestion` records for each question
3. Calculates `total_points` as the sum of all question points
4. Logs the creation

**Input fields:** `title`, `description`, `course_id`, `class_id`, `passing_points`, `time_limit_minutes`, `available_from`, `available_until`, `is_published`, `questions[]`

Each question: `question`, `type`, `options` (for MCQ), `correct_answer`, `points`

#### `submitQuiz(Quiz $quiz, array $answers): QuizResult`
Processes a student's quiz submission.

**Process (within DB transaction):**
1. Checks for existing submission (one attempt per student per quiz)
2. Auto-corrects MCQ questions by comparing against `correct_answer`
3. Text questions are NOT auto-corrected (marked for manual correction)
4. Creates `QuizResult` with score, answers, and `is_auto_corrected` flag

Throws `RuntimeException` if already submitted.

#### `correctTextAnswers(QuizResult $result, array $manualScores): QuizResult`
Allows teachers to manually score text-type questions.

**Process (within DB transaction):**
1. Retrieves text-type questions for the quiz
2. Adds manual scores to the existing auto-corrected score
3. Sets `corrected_at` and `corrected_by`

#### `publishQuiz(Quiz $quiz): Quiz`
Publishes a quiz (sets `is_published = true`).

#### `getQuizzesByTeacher(int $teacherId, int $perPage = 15)`
Returns paginated quizzes for a specific teacher.

#### `getQuizzesByClass(int $classId, int $perPage = 15)`
Returns paginated published quizzes for a specific class.

#### `getQuizResults(int $quizId, int $perPage = 15)`
Returns paginated results for a specific quiz.

---

## ScheduleService

Manages timetable entries with conflict detection.

### Methods

#### `create(array $data): Schedule`
Creates a new schedule entry after checking for conflicts.

**Input:** `course_id`, `class_id`, `teacher_id`, `classroom`, `day_of_week`, `start_time`, `end_time`, `is_active`

Throws `RuntimeException` if conflicts are detected.

#### `update(Schedule $schedule, array $data): Schedule`
Updates a schedule entry after checking for conflicts (excluding itself).

#### `checkConflicts(array $data, ?int $excludeId = null): Collection`
Detects three types of scheduling conflicts:

| Conflict Type | Description                                          |
|---------------|------------------------------------------------------|
| `teacher`     | Teacher is already scheduled during this time        |
| `classroom`   | Classroom is already booked during this time         |
| `class`       | Class already has a lesson during this time          |

Conflict detection uses time overlap logic (start within range, end within range, or fully encompassing).

#### `getWeeklySchedule(int $classId): Collection`
Returns a week-keyed collection of schedule entries for a class (Monday through Sunday).

#### `getTeacherSchedule(int $teacherId): Collection`
Returns all active schedule entries for a teacher, sorted by day and time.

#### `getStudentSchedule(int $classId): Collection`
Alias for `getWeeklySchedule()` — returns the schedule for a student's class.
