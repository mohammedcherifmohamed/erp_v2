# Database Schema

This document describes all database tables, their columns, relationships, and the entity-relationship structure of the School ERP + LMS platform.

## Entity-Relationship Diagram

```
┌──────────┐     ┌───────────────┐     ┌──────────────────┐
│  Level   │────<│     Grade     │────<│     Classe       │
└──────────┘     └───────────────┘     │   (classes)      │
                                       └────────┬─────────┘
                            ┌───────────────────┼──────────────────┐
                            │                   │                  │
                      ┌─────▼──────┐    ┌───────▼───────┐  ┌──────▼──────┐
                      │   Course   │    │  Enrollment   │  │  Schedule   │
                      └─────┬──────┘    └───────┬───────┘  └─────────────┘
                            │                   │
                 ┌──────────┼──────────┐        │
                 │          │          │        │
          ┌──────▼───┐ ┌────▼────┐ ┌───▼──────▼───┐
          │   Quiz   │ │Attendance│ │    User      │
          └──────┬───┘ └─────────┘ │  (users)     │
                 │                  └──┬──┬──┬─────┘
          ┌──────▼──────┐              │  │  │
          │QuizQuestion │              │  │  │
          └─────────────┘              │  │  │
          ┌──────▼──────┐     ┌────────▼┐ │ ┌▼────────────┐
          │ QuizResult  │     │Student  │ │ │TeacherProfile│
          └─────────────┘     │Profile  │ │ └─────────────┘
                              └─────────┘ │
                                    ┌─────▼───────┐
                                    │ParentProfile│
                                    └─────────────┘
```

## Tables

### `users`

Central user table for all roles (admin, teacher, student, parent).

| Column              | Type        | Nullable | Description                     |
|---------------------|-------------|----------|---------------------------------|
| `id`                | bigint (PK) | No       | Primary key                     |
| `first_name`        | string      | No       | User's first name               |
| `last_name`         | string      | No       | User's last name                |
| `email`             | string      | No       | Unique email address            |
| `email_verified_at` | timestamp   | Yes      | Email verification timestamp    |
| `password`          | string      | No       | Bcrypt-hashed password          |
| `role`              | string      | No       | `admin`, `teacher`, `student`, `parent` |
| `avatar`            | string      | Yes      | Avatar file path                |
| `phone`             | string      | Yes      | Phone number                    |
| `remember_token`    | string      | Yes      | Session remember token          |
| `created_at`        | timestamp   | No       | Record creation time            |
| `updated_at`        | timestamp   | No       | Record update time              |

### `student_profiles`

Extended student information.

| Column              | Type        | Nullable | Description                     |
|---------------------|-------------|----------|---------------------------------|
| `id`                | bigint (PK) | No       | Primary key                     |
| `user_id`           | bigint (FK) | No       | References `users.id`           |
| `parent_id`         | bigint (FK) | Yes      | References `users.id` (parent)  |
| `arabic_name`       | string      | Yes      | Name in Arabic                  |
| `date_of_birth`     | date        | Yes      | Date of birth                   |
| `gender`            | string      | Yes      | Gender                          |
| `address`           | string      | Yes      | Address                         |
| `emergency_contact` | string      | Yes      | Emergency contact number        |
| `blood_type`        | string      | Yes      | Blood type                      |
| `allergies`         | string      | Yes      | Known allergies                 |
| `is_active`         | boolean     | No       | Active status (default: true)   |

### `teacher_profiles`

Extended teacher information with wallet and application status.

| Column              | Type        | Nullable | Description                     |
|---------------------|-------------|----------|---------------------------------|
| `id`                | bigint (PK) | No       | Primary key                     |
| `user_id`           | bigint (FK) | No       | References `users.id`           |
| `arabic_name`       | string      | Yes      | Name in Arabic                  |
| `gender`            | string      | Yes      | Gender                          |
| `date_of_birth`     | date        | Yes      | Date of birth                   |
| `nationality`       | string      | Yes      | Nationality                     |
| `id_card_number`    | string      | Yes      | ID card number                  |
| `hire_date`         | date        | Yes      | Date of hire                    |
| `hourly_rate`       | decimal(2)  | Yes      | Hourly payment rate             |
| `wallet_balance`    | decimal(2)  | No       | Available wallet balance        |
| `pending_balance`   | decimal(2)  | No       | Pending earnings                |
| `bio`               | text        | Yes      | Biography / description         |
| `cv_path`           | string      | Yes      | Path to uploaded CV             |
| `specialization`    | string      | Yes      | Teaching specialization         |
| `is_active`         | boolean     | No       | Active status                   |
| `status`            | string      | No       | `pending`, `approved`, `rejected` |

### `parent_profiles`

Extended parent information.

| Column                | Type        | Nullable | Description                     |
|-----------------------|-------------|----------|---------------------------------|
| `id`                  | bigint (PK) | No       | Primary key                     |
| `user_id`             | bigint (FK) | No       | References `users.id`           |
| `arabic_name`         | string      | Yes      | Name in Arabic                  |
| `profession`          | string      | Yes      | Profession                      |
| `company`             | string      | Yes      | Company name                    |
| `secondary_phone`     | string      | Yes      | Secondary phone number          |
| `relationship`        | string      | Yes      | Relationship to student         |
| `receive_notifications`| boolean    | No       | Notification preference         |

### `levels`

Academic levels (e.g., Primary, Middle, High School).

| Column       | Type        | Nullable | Description                     |
|--------------|-------------|----------|---------------------------------|
| `id`         | bigint (PK) | No       | Primary key                     |
| `name`       | string      | No       | Level name (English)            |
| `name_ar`    | string      | Yes      | Level name (Arabic)             |
| `code`       | string      | Yes      | Short code                      |
| `description`| text        | Yes      | Description                     |
| `is_active`  | boolean     | No       | Active status                   |
| `sort_order` | integer     | No       | Display order                   |

### `grades`

Academic grades within a level (e.g., Grade 1, Grade 2).

| Column       | Type        | Nullable | Description                     |
|--------------|-------------|----------|---------------------------------|
| `id`         | bigint (PK) | No       | Primary key                     |
| `level_id`   | bigint (FK) | No       | References `levels.id`          |
| `name`       | string      | No       | Grade name (English)            |
| `name_ar`    | string      | Yes      | Grade name (Arabic)             |
| `code`       | string      | Yes      | Short code                      |
| `description`| text        | Yes      | Description                     |
| `is_active`  | boolean     | No       | Active status                   |
| `sort_order` | integer     | No       | Display order                   |

### `classes`

Class sections (sections of a grade). Also serves as a "bundle" container for courses.

| Column                   | Type        | Nullable | Description                              |
|--------------------------|-------------|----------|------------------------------------------|
| `id`                     | bigint (PK) | No       | Primary key                              |
| `grade_id`               | bigint (FK) | No       | References `grades.id`                   |
| `name`                   | string      | No       | Class name (English)                     |
| `name_ar`                | string      | Yes      | Class name (Arabic)                      |
| `section`                | string      | Yes      | Section identifier (A, B, C)             |
| `capacity`               | integer     | No       | Maximum student capacity                 |
| `enrolled_count`         | integer     | No       | Current enrolled students count          |
| `is_public`              | boolean     | No       | Visible on public website                |
| `price`                  | decimal(2)  | Yes      | Base price                               |
| `reduction_price`        | decimal(2)  | Yes      | Reduced (discounted) price               |
| `bundle_price`           | decimal(2)  | Yes      | Bundle enrollment price                  |
| `bundle_discount_type`   | string      | Yes      | `percentage`, `fixed`, or `none`         |
| `bundle_discount_value`  | decimal(2)  | Yes      | Discount amount or percentage            |
| `show_bundle_on_landing` | boolean     | No       | Show bundle on landing page              |
| `image`                  | string      | Yes      | Class image path                         |
| `description`            | text        | Yes      | Description                              |
| `homeroom_teacher_id`    | bigint (FK) | Yes      | References `users.id`                    |
| `is_active`              | boolean     | No       | Active status                            |

### `courses`

Individual courses within a class.

| Column           | Type        | Nullable | Description                     |
|------------------|-------------|----------|---------------------------------|
| `id`             | bigint (PK) | No       | Primary key                     |
| `class_id`       | bigint (FK) | No       | References `classes.id`         |
| `name`           | string      | No       | Course name (English)           |
| `name_ar`        | string      | Yes      | Course name (Arabic)            |
| `code`           | string      | Yes      | Course code                     |
| `description`    | text        | Yes      | Description                     |
| `teacher_id`     | bigint (FK) | Yes      | References `users.id`           |
| `sessions_count` | integer     | Yes      | Number of sessions              |
| `credits`        | integer     | Yes      | Credit hours                    |
| `price`          | decimal(2)  | Yes      | Individual enrollment price     |
| `is_active`      | boolean     | No       | Active status                   |
| `show_on_landing`| boolean     | No       | Show on public landing page     |
| `duration`       | string      | Yes      | Course duration                 |
| `max_students`   | integer     | Yes      | Maximum students allowed        |
| `thumbnail`      | string      | Yes      | Thumbnail image path            |
| `enrolled_count` | integer     | No       | Current enrolled count          |

### `enrollments`

Student enrollment records (individual course or full class enrollment).

| Column            | Type        | Nullable | Description                           |
|-------------------|-------------|----------|---------------------------------------|
| `id`              | bigint (PK) | No       | Primary key                           |
| `student_id`      | bigint (FK) | No       | References `users.id`                 |
| `class_id`        | bigint (FK) | No       | References `classes.id`               |
| `course_id`       | bigint (FK) | Yes      | References `courses.id` (if per-course)|
| `status`          | string      | No       | `pending`, `approved`, `rejected`, `archived` |
| `rejection_reason`| text        | Yes      | Reason for rejection                  |
| `approved_by`     | bigint (FK) | Yes      | References `users.id` (approver)      |
| `approved_at`     | timestamp   | Yes      | Approval timestamp                    |
| `start_date`      | date        | Yes      | Enrollment start date                 |
| `end_date`        | date        | Yes      | Enrollment end date                   |
| `expires_at`      | timestamp   | Yes      | Pending enrollment expiration         |

### `section_enrollments`

Bundle enrollments — enrolling in an entire section (all courses within a class).

| Column             | Type        | Nullable | Description                           |
|--------------------|-------------|----------|---------------------------------------|
| `id`               | bigint (PK) | No       | Primary key                           |
| `student_id`       | bigint (FK) | No       | References `users.id`                 |
| `section_id`       | bigint (FK) | No       | References `classes.id`               |
| `bundle_price_paid`| decimal(2)  | Yes      | Bundle price at time of enrollment    |
| `start_date`       | date        | Yes      | Start date                            |
| `end_date`         | date        | Yes      | End date                              |
| `status`           | string      | No       | `pending`, `approved`, `rejected`, `archived` |
| `rejection_reason` | text        | Yes      | Reason for rejection                  |
| `approved_by`      | bigint (FK) | Yes      | References `users.id`                 |
| `approved_at`      | timestamp   | Yes      | Approval timestamp                    |
| `expires_at`       | timestamp   | Yes      | Expiration for pending enrollments    |

### `schedules`

Weekly timetable entries.

| Column       | Type        | Nullable | Description                     |
|--------------|-------------|----------|---------------------------------|
| `id`         | bigint (PK) | No       | Primary key                     |
| `course_id`  | bigint (FK) | No       | References `courses.id`         |
| `class_id`   | bigint (FK) | No       | References `classes.id`         |
| `teacher_id` | bigint (FK) | No       | References `users.id`           |
| `classroom`  | string      | Yes      | Classroom name/number           |
| `day_of_week`| string      | No       | Day name (monday, tuesday, etc.)|
| `start_time` | time        | No       | Start time (HH:MM)             |
| `end_time`   | time        | No       | End time (HH:MM)               |
| `is_active`  | boolean     | No       | Active status                   |

### `attendances`

Student attendance records.

| Column       | Type        | Nullable | Description                     |
|--------------|-------------|----------|---------------------------------|
| `id`         | bigint (PK) | No       | Primary key                     |
| `student_id` | bigint (FK) | No       | References `users.id`           |
| `course_id`  | bigint (FK) | No       | References `courses.id`         |
| `schedule_id`| bigint (FK) | Yes      | References `schedules.id`       |
| `date`       | date        | No       | Attendance date                 |
| `status`     | string      | No       | `present`, `absent`, `late`, `excused` |
| `notes`      | text        | Yes      | Attendance notes                |
| `marked_by`  | bigint (FK) | No       | References `users.id` (marker)  |

### `invoices`

Financial invoices linked to enrollments.

| Column                  | Type        | Nullable | Description                       |
|-------------------------|-------------|----------|-----------------------------------|
| `id`                    | bigint (PK) | No       | Primary key                       |
| `invoice_number`        | string      | No       | Unique invoice number (INV-YYYY-XXXXXX) |
| `student_id`            | bigint (FK) | No       | References `users.id`             |
| `parent_id`             | bigint (FK) | Yes      | References `users.id`             |
| `class_id`              | bigint (FK) | No       | References `classes.id`           |
| `section_enrollment_id` | bigint (FK) | Yes      | References `section_enrollments.id`|
| `total_amount`          | decimal(2)  | No       | Total invoice amount              |
| `reduction_amount`      | decimal(2)  | No       | Reduction/discount amount         |
| `reduction_reason`      | string      | Yes      | Reason for reduction              |
| `paid_amount`           | decimal(2)  | No       | Total amount paid so far          |
| `remaining_amount`      | decimal(2)  | No       | Remaining balance                 |
| `status`                | string      | No       | `unpaid`, `partial`, `paid`, `overdue` |
| `due_date`              | date        | No       | Payment due date                  |
| `description`           | text        | Yes      | Invoice description               |

### `payments`

Payment records against invoices.

| Column          | Type        | Nullable | Description                     |
|-----------------|-------------|----------|---------------------------------|
| `id`            | bigint (PK) | No       | Primary key                     |
| `invoice_id`    | bigint (FK) | No       | References `invoices.id`        |
| `collected_by`  | bigint (FK) | No       | References `users.id` (admin)   |
| `amount`        | decimal(2)  | No       | Payment amount                  |
| `payment_method`| string      | Yes      | Payment method                  |
| `transaction_id`| string      | Yes      | External transaction ID         |
| `notes`         | text        | Yes      | Payment notes                   |
| `paid_at`       | timestamp   | No       | Payment date/time               |

### `teacher_contracts`

Contract agreements between teachers and courses.

| Column         | Type        | Nullable | Description                           |
|----------------|-------------|----------|---------------------------------------|
| `id`           | bigint (PK) | No       | Primary key                           |
| `teacher_id`   | bigint (FK) | No       | References `users.id`                 |
| `course_id`    | bigint (FK) | No       | References `courses.id`               |
| `class_id`     | bigint (FK) | Yes      | References `classes.id`               |
| `contract_type`| string      | No       | `per_session`, `per_student`, `percentage`, `monthly` |
| `rate`         | decimal(2)  | No       | Payment rate (amount or percentage)   |
| `is_active`    | boolean     | No       | Active status                         |

### `teacher_withdrawals`

Teacher withdrawal requests from their wallet balance.

| Column          | Type        | Nullable | Description                     |
|-----------------|-------------|----------|---------------------------------|
| `id`            | bigint (PK) | No       | Primary key                     |
| `teacher_id`    | bigint (FK) | No       | References `users.id`           |
| `amount`        | decimal(2)  | No       | Withdrawal amount               |
| `status`        | string      | No       | `pending`, `approved`, `completed`, `rejected` |
| `payment_method`| string      | Yes      | Payment method                  |
| `account_number`| string      | Yes      | Bank account or wallet number   |
| `notes`         | text        | Yes      | Notes or rejection reason       |
| `processed_by`  | bigint (FK) | Yes      | References `users.id` (admin)   |
| `processed_at`  | timestamp   | Yes      | Processing timestamp            |

### `quizzes`

Quizzes created by teachers.

| Column              | Type        | Nullable | Description                     |
|---------------------|-------------|----------|---------------------------------|
| `id`                | bigint (PK) | No       | Primary key                     |
| `title`             | string      | No       | Quiz title                      |
| `description`       | text        | Yes      | Quiz description                |
| `course_id`         | bigint (FK) | No       | References `courses.id`         |
| `class_id`          | bigint (FK) | No       | References `classes.id`         |
| `teacher_id`        | bigint (FK) | No       | References `users.id`           |
| `total_points`      | integer     | No       | Total available points          |
| `passing_points`    | integer     | No       | Minimum passing points          |
| `time_limit_minutes`| integer     | Yes      | Time limit in minutes           |
| `available_from`    | timestamp   | Yes      | Start availability window       |
| `available_until`   | timestamp   | Yes      | End availability window         |
| `is_published`      | boolean     | No       | Published status                |

### `quiz_questions`

Questions within a quiz.

| Column          | Type        | Nullable | Description                       |
|-----------------|-------------|----------|-----------------------------------|
| `id`            | bigint (PK) | No       | Primary key                       |
| `quiz_id`       | bigint (FK) | No       | References `quizzes.id`           |
| `question`      | text        | No       | Question text                     |
| `type`          | string      | No       | `mcq`, `text`, etc.               |
| `options`       | json        | Yes      | Answer options (for MCQ)          |
| `correct_answer`| string      | Yes      | Correct answer                    |
| `points`        | integer     | No       | Points for this question          |
| `sort_order`    | integer     | No       | Display order                     |

### `quiz_results`

Student quiz submissions and results.

| Column            | Type        | Nullable | Description                     |
|-------------------|-------------|----------|---------------------------------|
| `id`              | bigint (PK) | No       | Primary key                     |
| `quiz_id`         | bigint (FK) | No       | References `quizzes.id`         |
| `student_id`      | bigint (FK) | No       | References `users.id`           |
| `score`           | integer     | No       | Achieved score                  |
| `total_points`    | integer     | No       | Total possible points           |
| `answers`         | json        | No       | Student's submitted answers     |
| `started_at`      | timestamp   | Yes      | Quiz start time                 |
| `submitted_at`    | timestamp   | Yes      | Submission time                 |
| `is_auto_corrected`| boolean    | No       | Whether auto-corrected          |
| `corrected_at`    | timestamp   | Yes      | Manual correction timestamp     |
| `corrected_by`    | bigint (FK) | Yes      | References `users.id`           |
| `feedback`        | text        | Yes      | Teacher feedback                |

### `announcements`

Announcements by teachers.

| Column         | Type        | Nullable | Description                     |
|----------------|-------------|----------|---------------------------------|
| `id`           | bigint (PK) | No       | Primary key                     |
| `title`        | string      | No       | Announcement title              |
| `content`      | text        | No       | Announcement content            |
| `author_id`    | bigint (FK) | No       | References `users.id`           |
| `class_id`     | bigint (FK) | Yes      | References `classes.id`         |
| `is_global`    | boolean     | No       | Visible to all classes          |
| `is_published` | boolean     | No       | Published status                |
| `published_at` | timestamp   | Yes      | Publication timestamp           |

### `audit_logs`

Audit trail for all operations.

| Column       | Type        | Nullable | Description                     |
|--------------|-------------|----------|---------------------------------|
| `id`         | bigint (PK) | No       | Primary key                     |
| `user_id`    | bigint (FK) | Yes      | References `users.id`           |
| `action`     | string      | No       | Action type (created, updated, deleted, login, logout) |
| `model_type` | string      | Yes      | Eloquent model class            |
| `model_id`   | bigint      | Yes      | Model record ID                 |
| `old_values` | json        | Yes      | Previous values                 |
| `new_values` | json        | Yes      | New values                      |
| `ip_address` | string      | Yes      | Client IP address               |
| `user_agent` | string      | Yes      | Client user agent               |

### `class_teacher` (pivot)

Many-to-many relationship between classes and teachers.

| Column       | Type        | Description                     |
|--------------|-------------|---------------------------------|
| `class_id`   | bigint (FK) | References `classes.id`         |
| `teacher_id` | bigint (FK) | References `users.id`           |
| `created_at` | timestamp   | Record creation time            |
| `updated_at` | timestamp   | Record update time              |

### `password_setup_tokens`

Tokens for admin-initiated account setup invitations.

| Column       | Type        | Description                     |
|--------------|-------------|---------------------------------|
| `id`         | bigint (PK) | Primary key                     |
| `token`      | string      | Unique setup token              |
| `user_id`    | bigint (FK) | References `users.id`           |
| `created_at` | timestamp   | Token creation time             |
| `updated_at` | timestamp   | Token update time               |

## Key Relationships

| Relationship                      | Type          | Description                                    |
|-----------------------------------|---------------|------------------------------------------------|
| Level → Grades                    | One-to-Many   | A level contains multiple grades               |
| Grade → Classes                   | One-to-Many   | A grade contains multiple class sections        |
| Classe → Courses                  | One-to-Many   | A class contains multiple courses              |
| Classe → Enrollments              | One-to-Many   | Students enroll in classes                     |
| Classe → SectionEnrollments       | One-to-Many   | Students enroll in bundles                     |
| Classe ↔ Teachers                 | Many-to-Many  | Teachers assigned to classes (pivot table)      |
| Course → Teacher                  | Many-to-One   | Each course has one assigned teacher           |
| Course → Schedules                | One-to-Many   | A course has multiple schedule entries          |
| Course → Attendances              | One-to-Many   | Attendance records per course                  |
| Course → Quizzes                  | One-to-Many   | Quizzes belong to a course                     |
| User → StudentProfile             | One-to-One    | Student extended info                          |
| User → TeacherProfile             | One-to-One    | Teacher extended info with wallet              |
| User → ParentProfile              | One-to-One    | Parent extended info                           |
| User → Enrollments                | One-to-Many   | Student's enrollments                          |
| User → Invoices                   | One-to-Many   | Student's invoices                             |
| Invoice → Payments                | One-to-Many   | Payments against an invoice                    |
| Quiz → QuizQuestions              | One-to-Many   | Questions within a quiz                        |
| Quiz → QuizResults                | One-to-Many   | Student results for a quiz                     |
| Teacher → TeacherContracts        | One-to-Many   | Contracts for teaching courses                 |
| Teacher → TeacherWithdrawals      | One-to-Many   | Withdrawal requests from wallet                |
