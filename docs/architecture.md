# Architecture

This document describes the system architecture, design patterns, and layer responsibilities of the School ERP + LMS platform.

## High-Level Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Public Website                        │
│         Landing Page · Course Catalog · Enrollment       │
├─────────────────────────────────────────────────────────┤
│                    Blade Templates                       │
│   Layouts: app.blade.php · auth.blade.php · sidebar     │
├─────────────────────────────────────────────────────────┤
│                     Route Layer                          │
│  web.php · admin.php · teacher.php · student.php ·       │
│  parent.php · api.php                                    │
├─────────────────────────────────────────────────────────┤
│                   Middleware Layer                        │
│         auth · role:is-admin · role:is-teacher ·         │
│         role:is-student · role:is-parent · throttle      │
├─────────────────────────────────────────────────────────┤
│                  Controller Layer                         │
│   Admin/ · Teacher/ · Student/ · Parent/ · Auth/         │
│   DashboardController · LandingPageController            │
├─────────────────────────────────────────────────────────┤
│              Form Request Validation                      │
│   Store*Request · Update*Request · LoginRequest          │
├─────────────────────────────────────────────────────────┤
│              Authorization (Policies)                     │
│   EnrollmentPolicy · SchedulePolicy · QuizPolicy ·      │
│   InvoicePolicy · PaymentPolicy · AttendancePolicy       │
├─────────────────────────────────────────────────────────┤
│                   Service Layer                           │
│   EnrollmentService · InvoiceService · QuizService ·     │
│   ScheduleService · TeacherPaymentService ·              │
│   SectionEnrollmentService · AuditService                │
├─────────────────────────────────────────────────────────┤
│                   Model Layer                             │
│        22 Eloquent Models with relationships              │
├─────────────────────────────────────────────────────────┤
│             Events & Listeners                            │
│   Domain Events → Listeners → Notifications              │
├─────────────────────────────────────────────────────────┤
│                    Database                               │
│          SQLite / MySQL / PostgreSQL                      │
└─────────────────────────────────────────────────────────┘
```

## Design Patterns

### 1. Service Layer Pattern

Business logic is encapsulated in dedicated service classes under `app/Services/`. Controllers remain thin, delegating complex operations to services.

**Services:**
| Service                      | Responsibility                                           |
|------------------------------|----------------------------------------------------------|
| `EnrollmentService`          | Enrollment submission, approval, rejection, archiving     |
| `SectionEnrollmentService`   | Bundle enrollment workflow with per-course auto-enrollment|
| `InvoiceService`             | Invoice generation, payment recording, overdue detection  |
| `TeacherPaymentService`      | Teacher earnings calculation, withdrawal processing       |
| `QuizService`                | Quiz creation, submission, auto-correction, publishing    |
| `ScheduleService`            | Timetable management with conflict detection              |
| `AuditService`               | Audit log recording for all CRUD operations               |

### 2. Repository-Free Approach

The application uses Eloquent models directly with query scopes instead of a separate repository layer. Models define reusable scopes like `scopeActive()`, `scopePending()`, `scopeByRole()`.

### 3. Event-Driven Architecture

Domain events decouple core operations from side effects (e.g., sending notifications):

```
StudentRegistered      → SendStudentWelcomeNotification
TeacherApplied         → SendTeacherApplicationConfirmation
EnrollmentSubmitted    → SendEnrollmentConfirmation
EnrollmentApproved     → SendEnrollmentApprovalNotification
EnrollmentRejected     → SendEnrollmentRejectionNotification
AccountCreatedByAdmin  → SendAccountSetupInvitation
```

### 4. Policy-Based Authorization

Laravel Policies enforce fine-grained access control. Each policy maps to a model and defines per-action permissions based on the user's role and ownership.

### 5. Form Request Validation

Validation logic is extracted into dedicated `FormRequest` classes under `app/Http/Requests/`, keeping controllers clean:

- `LoginRequest`
- `StoreEnrollmentRequest`
- `StoreQuizRequest`
- `StorePaymentRequest`
- `StoreScheduleRequest`
- `StoreAttendanceRequest`
- `StoreAnnouncementRequest`
- `StoreTeacherContractRequest`
- `StoreLevelRequest` / `UpdateLevelRequest`
- `StoreGradeRequest` / `UpdateGradeRequest`
- `StoreClasseRequest` / `UpdateClasseRequest`
- `StoreCourseRequest`
- `StoreAnswerRequest`
- `StoreWithdrawalRequest`

## Layer Responsibilities

### Routes

Routes are organized by role into separate files:

| File            | Prefix      | Middleware                  | Purpose                         |
|-----------------|-------------|-----------------------------|---------------------------------|
| `web.php`       | `/`         | `auth`, `guest`             | Public pages, auth, dashboard   |
| `admin.php`     | `/admin`    | `auth`, `role:is-admin`     | Admin panel operations          |
| `teacher.php`   | `/teacher`  | `auth`, `role:is-teacher`   | Teacher portal                  |
| `student.php`   | `/student`  | `auth`, `role:is-student`   | Student portal                  |
| `parent.php`    | `/parent`   | `auth`, `role:is-parent`    | Parent portal                   |
| `api.php`       | `/api`      | `auth:sanctum`              | API endpoints                   |

### Controllers

Controllers are namespaced by role:

- **`Admin/`** — Full CRUD for all resources (students, teachers, parents, levels, grades, classes, courses, enrollments, schedules, attendances, invoices, contracts, withdrawals, section enrollments)
- **`Teacher/`** — Quiz management, announcements, attendance marking, withdrawals
- **`Student/`** — Quiz taking, schedule viewing, invoice viewing
- **`Parent/`** — Children overview, invoice viewing, schedule viewing
- **`Auth/`** — Login, registration, password setup

### Models

The 22 Eloquent models define the data layer with relationships, scopes, accessors, and casts. See [database.md](database.md) for the full schema.

### Views

Blade templates are organized by feature:

```
resources/views/
├── layouts/          # Base layouts (app, auth, sidebar)
├── dashboard/        # Role-specific dashboards
├── auth/             # Login, register, password setup
├── public/           # Landing page, course catalog
├── levels/           # Level CRUD views
├── grades/           # Grade CRUD views
├── classes/          # Class CRUD views
├── courses/          # Course CRUD views
├── enrollments/      # Enrollment management views
├── schedules/        # Schedule management views
├── attendances/      # Attendance views
├── invoices/         # Invoice & payment views
├── quizzes/          # Quiz management & taking views
├── announcements/    # Announcement views
├── students/         # Student management views
├── teachers/         # Teacher management views
├── parents/          # Parent management views
├── parent/           # Parent portal views
├── section-enrollments/ # Section enrollment views
├── teacher-contracts/   # Contract management views
└── teacher-withdrawals/ # Withdrawal management views
```

## Transaction Safety

All critical multi-step operations are wrapped in database transactions (`DB::transaction()`):
- Enrollment approval (update status → increment count → generate invoice → audit log)
- Payment recording (create payment → update invoice amounts/status → audit log)
- Withdrawal processing (decrement wallet → create withdrawal → audit log)
- Quiz submission (create result → auto-correct → audit log)

## Security

- **Password Hashing** — Bcrypt with 12 rounds (configurable)
- **CSRF Protection** — Built-in Laravel middleware
- **Rate Limiting** — Password setup endpoint throttled to 5 requests per 30 minutes
- **Role Middleware** — `CheckRole` middleware enforces access per route group
- **Policy Authorization** — Fine-grained per-action authorization
- **Sanctum API Auth** — Token-based API authentication
- **Input Validation** — All user input validated via Form Request classes
