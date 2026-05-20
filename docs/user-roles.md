# User Roles & Permissions

This document describes the four user roles in the School ERP + LMS platform, their capabilities, and the authorization policies that enforce access control.

## Role Overview

| Role       | Dashboard             | Registrable By                  | Route Prefix | Middleware         |
|------------|-----------------------|---------------------------------|--------------|--------------------|
| **Admin**  | Admin Dashboard       | System (manual creation)        | `/admin`     | `role:is-admin`    |
| **Teacher**| Teacher Dashboard     | Public registration + Admin approval | `/teacher`   | `role:is-teacher`  |
| **Student**| Student Dashboard     | Self-registration on public site| `/student`   | `role:is-student`  |
| **Parent** | Parent Dashboard      | Admin creates and links to children | `/parent`    | `role:is-parent`   |

## How Roles Are Assigned

The `role` field on the `users` table determines each user's role. It is set at creation time:

- **Student** — Set to `student` during self-registration via `/register`
- **Teacher** — Set to `teacher` during public application via `/teacher/register` (requires admin approval)
- **Parent** — Set to `parent` when an admin creates a parent account
- **Admin** — Set to `admin` when an admin creates an admin account (or via seeder)

## Role Checking

The `CheckRole` middleware (`app/Http/Middleware/CheckRole.php`) maps role strings to User model methods:

| Middleware Param | Model Method  | Checks              |
|------------------|---------------|----------------------|
| `is-admin`       | `isAdmin()`   | `role === 'admin'`   |
| `is-teacher`     | `isTeacher()` | `role === 'teacher'` |
| `is-student`     | `isStudent()` | `role === 'student'` |
| `is-parent`      | `isParent()`  | `role === 'parent'`  |

Unauthorized access returns HTTP 403.

---

## Admin Permissions

Admins have the broadest access and can manage all system resources.

### Academic Management
| Action                         | Allowed |
|--------------------------------|---------|
| Manage levels (CRUD)           | Yes     |
| Manage grades (CRUD)           | Yes     |
| Manage classes (CRUD)          | Yes     |
| Manage courses (CRUD)          | Yes     |

### User Management
| Action                         | Allowed |
|--------------------------------|---------|
| Manage students (CRUD)         | Yes     |
| Manage teachers (CRUD)         | Yes     |
| Approve/reject teacher applications | Yes |
| Manage parents (CRUD)          | Yes     |
| Create accounts with setup invitation | Yes |

### Enrollment Management
| Action                         | Allowed |
|--------------------------------|---------|
| View all enrollments           | Yes     |
| Approve enrollments            | Yes     |
| Reject enrollments             | Yes     |
| Archive enrollments            | Yes     |
| Delete enrollments             | Yes     |
| Manage section (bundle) enrollments | Yes |

### Scheduling
| Action                         | Allowed |
|--------------------------------|---------|
| Manage schedules (CRUD)        | Yes     |
| View weekly schedule           | Yes     |

### Attendance
| Action                         | Allowed |
|--------------------------------|---------|
| View all attendance records    | Yes     |
| Create attendance records      | Yes     |
| Edit attendance records        | Yes     |
| View attendance analytics      | Yes     |
| View attendance by course      | Yes     |

### Finance
| Action                         | Allowed |
|--------------------------------|---------|
| View all invoices              | Yes     |
| Create invoices                | Yes     |
| Record payments                | Yes     |
| Apply reductions               | Yes     |
| View overdue invoices          | Yes     |
| Delete invoices                | Yes     |

### Teacher Contracts & Withdrawals
| Action                         | Allowed |
|--------------------------------|---------|
| Manage teacher contracts (CRUD)| Yes     |
| View all withdrawal requests   | Yes     |
| Approve withdrawals            | Yes     |
| Complete withdrawals           | Yes     |
| Reject withdrawals             | Yes     |

### Dashboard KPIs
- Total students, teachers, parents
- Total classes and courses
- Pending enrollments count
- Total and unpaid invoices
- Total revenue and pending revenue
- Recent enrollments
- Published announcements

---

## Teacher Permissions

Teachers can manage their own courses, quizzes, attendance, and financial information.

### Quizzes
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| View own quizzes               | Yes                              |
| Create quizzes                 | Yes                              |
| Edit own quizzes               | Yes                              |
| Delete own quizzes             | Yes                              |
| Publish quizzes                | Yes (own quizzes only)           |
| Correct text answers           | Yes (for own quizzes)            |

### Announcements
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| Create announcements           | Yes                              |
| Edit own announcements         | Yes                              |
| Delete own announcements       | Yes                              |
| Publish global announcements   | Yes                              |

### Attendance
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| Mark attendance for own courses| Yes                              |
| View attendance history        | Yes (own courses)                |

### Financial
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| View wallet balance            | Yes                              |
| View pending balance           | Yes                              |
| Submit withdrawal requests     | Yes                              |
| View own withdrawal history    | Yes                              |

### Schedule
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| View own teaching schedule     | Yes                              |

### Dashboard KPIs
- Total courses assigned
- Total schedule entries
- Total quizzes created
- Wallet balance and pending balance
- Today's schedule
- Course list with details

---

## Student Permissions

Students have read-focused access to their academic data.

### Quizzes
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| View available quizzes         | Yes (published, enrolled class)  |
| Take quizzes                   | Yes (one attempt per quiz)       |
| View own quiz results          | Yes                              |

### Schedule
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| View own class schedule        | Yes                              |

### Invoices
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| View own invoices              | Yes                              |
| View invoice details           | Yes                              |

### Enrollment
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| Enroll in public courses       | Yes (via landing page)           |
| Enroll in bundles              | Yes (via landing page)           |
| View enrollment status         | Yes                              |

### Dashboard KPIs
- Total approved enrollments
- Total courses enrolled
- Upcoming schedule entries
- Pending invoices count
- Today's schedule
- Recent announcements

---

## Parent Permissions

Parents have read-only access to their children's academic and financial data.

### Children
| Action                         | Allowed                          |
|--------------------------------|----------------------------------|
| View list of children          | Yes                              |
| View child's invoices          | Yes                              |
| View specific invoice details  | Yes                              |
| View child's schedule          | Yes                              |

### Dashboard KPIs
- List of children with their details

---

## Authorization Policies

Policies provide fine-grained access control at the model level:

### `EnrollmentPolicy`
- `viewAny`: Admin or Teacher
- `view`: Admin, or the enrollment's student
- `create`: Student
- `approve` / `reject`: Admin only
- `delete`: Admin only

### `SchedulePolicy`
- `viewAny`: Admin, Teacher, or Student
- `view`: Admin, the schedule's teacher, or a student in the class
- `create` / `update` / `delete`: Admin only

### `AttendancePolicy`
- `viewAny`: Admin or Teacher
- `view`: Admin, or the attendance's student
- `create`: Teacher or Admin
- `update`: Admin only

### `InvoicePolicy`
- `viewAny`: Admin only
- `view`: Admin, the invoice's student, or the invoice's parent
- `create` / `update` / `delete`: Admin only

### `PaymentPolicy`
- `create`: Admin only
- `view`: Admin, the payment's student, or the payment's parent

### `QuizPolicy`
- `viewAny`: Admin, Teacher, or Student
- `view`: Admin, the quiz's teacher, or an enrolled student (published quizzes only)
- `create`: Teacher only
- `update` / `delete`: Admin, or the quiz's teacher
- `publish`: The quiz's teacher only

### `UserPolicy`
- `viewAny` / `create` / `update` / `delete`: Admin only
- `view`: Admin, or the user viewing themselves
