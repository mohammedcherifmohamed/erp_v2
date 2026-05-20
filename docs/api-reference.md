# API Reference

This document lists all web routes and endpoints available in the School ERP + LMS platform, organized by role and route file.

## Public Routes (`web.php`)

These routes are accessible without authentication (unless noted).

| Method | URI                                      | Name                        | Description                              |
|--------|------------------------------------------|-----------------------------|------------------------------------------|
| GET    | `/`                                      | `home`                      | Landing page with featured bundles/courses|
| GET    | `/courses`                               | `courses`                   | Public course catalog (searchable)       |
| GET    | `/courses/{classe}`                      | `courses.details`           | Course/class detail page                 |
| GET    | `/bundles/{classe}`                      | `bundles.details`           | Bundle detail page                       |
| POST   | `/courses/{classe}/enroll`               | `courses.enroll`            | Enroll in a class (auth required)        |
| POST   | `/courses/{course}/enroll-course`        | `courses.enroll-course`     | Enroll in a single course (auth required)|
| POST   | `/courses/{classe}/enroll-bundle`        | `courses.enroll-bundle`     | Enroll in a bundle (auth required)       |
| GET    | `/enrollments/{enrollment}/success`      | `enrollments.success`       | Enrollment success page (auth required)  |
| GET    | `/section-enrollments/{sectionEnrollment}/success` | `section-enrollments.success` | Section enrollment success page |
| GET    | `/teacher/register`                      | `teacher.register`          | Teacher registration form                |
| POST   | `/teacher/register`                      | `teacher.register.store`    | Submit teacher application               |
| GET    | `/teacher/register/success`             | `teacher.register.success`  | Teacher registration success page        |

## Guest Routes (unauthenticated only)

| Method | URI                          | Name                    | Description                     |
|--------|------------------------------|-------------------------|---------------------------------|
| GET    | `/login`                     | `login`                 | Student/admin login page        |
| POST   | `/login`                     | —                       | Process login                   |
| GET    | `/teacher/login`             | `teacher.login`         | Teacher login page              |
| POST   | `/teacher/login`             | `teacher.login.submit`  | Process teacher login           |
| GET    | `/register`                  | `register`              | Student registration page       |
| POST   | `/register`                  | —                       | Process student registration    |
| GET    | `/password-setup/{token}`    | `password.setup`        | Password setup form (via invite)|
| POST   | `/password-setup/{token}`    | `password.setup.store`  | Submit password setup (throttled: 5/30min) |

## Authenticated Routes

| Method | URI            | Name         | Description                 |
|--------|----------------|--------------|-----------------------------|
| POST   | `/logout`      | `logout`     | Log out                     |
| GET    | `/dashboard`   | `dashboard`  | Role-based dashboard redirect|

---

## Admin Routes (`admin.php`)

**Prefix:** `/admin` | **Middleware:** `auth`, `role:is-admin` | **Name prefix:** `admin.`

### Dashboard

| Method | URI                | Name               | Description            |
|--------|--------------------|---------------------|------------------------|
| GET    | `/admin/dashboard` | `admin.dashboard`   | Admin dashboard with KPIs |

### Levels (CRUD)

| Method | URI                      | Name                 | Description         |
|--------|--------------------------|----------------------|---------------------|
| GET    | `/admin/levels`          | `admin.levels.index` | List all levels     |
| GET    | `/admin/levels/create`   | `admin.levels.create`| Create level form   |
| POST   | `/admin/levels`          | `admin.levels.store` | Store new level     |
| GET    | `/admin/levels/{level}`  | `admin.levels.show`  | View level details  |
| GET    | `/admin/levels/{level}/edit` | `admin.levels.edit` | Edit level form  |
| PUT    | `/admin/levels/{level}`  | `admin.levels.update`| Update level        |
| DELETE | `/admin/levels/{level}`  | `admin.levels.destroy`| Delete level       |

### Grades (CRUD)

| Method | URI                      | Name                 | Description         |
|--------|--------------------------|----------------------|---------------------|
| GET    | `/admin/grades`          | `admin.grades.index` | List all grades     |
| GET    | `/admin/grades/create`   | `admin.grades.create`| Create grade form   |
| POST   | `/admin/grades`          | `admin.grades.store` | Store new grade     |
| GET    | `/admin/grades/{grade}`  | `admin.grades.show`  | View grade details  |
| GET    | `/admin/grades/{grade}/edit` | `admin.grades.edit` | Edit grade form  |
| PUT    | `/admin/grades/{grade}`  | `admin.grades.update`| Update grade        |
| DELETE | `/admin/grades/{grade}`  | `admin.grades.destroy`| Delete grade       |

### Classes (CRUD)

| Method | URI                        | Name                  | Description          |
|--------|----------------------------|-----------------------|----------------------|
| GET    | `/admin/classes`           | `admin.classes.index` | List all classes     |
| GET    | `/admin/classes/create`    | `admin.classes.create`| Create class form    |
| POST   | `/admin/classes`           | `admin.classes.store` | Store new class      |
| GET    | `/admin/classes/{classe}`  | `admin.classes.show`  | View class details   |
| GET    | `/admin/classes/{classe}/edit` | `admin.classes.edit` | Edit class form   |
| PUT    | `/admin/classes/{classe}`  | `admin.classes.update`| Update class         |
| DELETE | `/admin/classes/{classe}`  | `admin.classes.destroy`| Delete class        |

### Courses (CRUD)

| Method | URI                        | Name                   | Description          |
|--------|----------------------------|------------------------|----------------------|
| GET    | `/admin/courses`           | `admin.courses.index`  | List all courses     |
| GET    | `/admin/courses/create`    | `admin.courses.create` | Create course form   |
| POST   | `/admin/courses`           | `admin.courses.store`  | Store new course     |
| GET    | `/admin/courses/{course}`  | `admin.courses.show`   | View course details  |
| GET    | `/admin/courses/{course}/edit` | `admin.courses.edit` | Edit course form  |
| PUT    | `/admin/courses/{course}`  | `admin.courses.update` | Update course        |
| DELETE | `/admin/courses/{course}`  | `admin.courses.destroy`| Delete course        |

### Enrollments

| Method | URI                                        | Name                         | Description                |
|--------|--------------------------------------------|------------------------------|----------------------------|
| GET    | `/admin/enrollments`                       | `admin.enrollments.index`    | List all enrollments       |
| GET    | `/admin/enrollments/pending`               | `admin.enrollments.pending`  | List pending enrollments   |
| GET    | `/admin/enrollments/create`                | `admin.enrollments.create`   | Create enrollment form     |
| POST   | `/admin/enrollments`                       | `admin.enrollments.store`    | Store new enrollment       |
| GET    | `/admin/enrollments/{enrollment}`          | `admin.enrollments.show`     | View enrollment details    |
| DELETE | `/admin/enrollments/{enrollment}`          | `admin.enrollments.destroy`  | Delete enrollment          |
| PATCH  | `/admin/enrollments/{enrollment}/approve`  | `admin.enrollments.approve`  | Approve enrollment         |
| POST   | `/admin/enrollments/{enrollment}/reject`   | `admin.enrollments.reject`   | Reject enrollment          |
| PATCH  | `/admin/enrollments/{enrollment}/archive`  | `admin.enrollments.archive`  | Archive enrollment         |

### Section Enrollments

| Method | URI                                                         | Name                                   | Description                   |
|--------|-------------------------------------------------------------|----------------------------------------|-------------------------------|
| GET    | `/admin/section-enrollments`                                | `admin.section-enrollments.index`      | List section enrollments      |
| GET    | `/admin/section-enrollments/pending`                        | `admin.section-enrollments.pending`    | List pending section enrollments |
| GET    | `/admin/section-enrollments/{sectionEnrollment}`            | `admin.section-enrollments.show`       | View details                  |
| PATCH  | `/admin/section-enrollments/{sectionEnrollment}/approve`    | `admin.section-enrollments.approve`    | Approve section enrollment    |
| POST   | `/admin/section-enrollments/{sectionEnrollment}/reject`     | `admin.section-enrollments.reject`     | Reject section enrollment     |
| PATCH  | `/admin/section-enrollments/{sectionEnrollment}/archive`    | `admin.section-enrollments.archive`    | Archive section enrollment    |

### Students (CRUD)

| Method | URI                            | Name                    | Description          |
|--------|--------------------------------|-------------------------|----------------------|
| GET    | `/admin/students`              | `admin.students.index`  | List all students    |
| GET    | `/admin/students/create`       | `admin.students.create` | Create student form  |
| POST   | `/admin/students`              | `admin.students.store`  | Store new student    |
| GET    | `/admin/students/{student}`    | `admin.students.show`   | View student details |
| GET    | `/admin/students/{student}/edit` | `admin.students.edit` | Edit student form    |
| PUT    | `/admin/students/{student}`    | `admin.students.update` | Update student       |
| DELETE | `/admin/students/{student}`    | `admin.students.destroy`| Delete student       |

### Teachers

| Method | URI                                    | Name                      | Description              |
|--------|----------------------------------------|---------------------------|--------------------------|
| GET    | `/admin/teachers`                      | `admin.teachers.index`    | List all teachers        |
| GET    | `/admin/teachers/pending`              | `admin.teachers.pending`  | List pending teachers    |
| GET    | `/admin/teachers/create`               | `admin.teachers.create`   | Create teacher form      |
| POST   | `/admin/teachers`                      | `admin.teachers.store`    | Store new teacher        |
| GET    | `/admin/teachers/{teacher}`            | `admin.teachers.show`     | View teacher details     |
| GET    | `/admin/teachers/{teacher}/edit`       | `admin.teachers.edit`     | Edit teacher form        |
| PUT    | `/admin/teachers/{teacher}`            | `admin.teachers.update`   | Update teacher           |
| DELETE | `/admin/teachers/{teacher}`            | `admin.teachers.destroy`  | Delete teacher           |
| PATCH  | `/admin/teachers/{teacher}/approve`    | `admin.teachers.approve`  | Approve teacher          |
| POST   | `/admin/teachers/{teacher}/reject`     | `admin.teachers.reject`   | Reject teacher           |

### Parents (CRUD)

| Method | URI                            | Name                    | Description          |
|--------|--------------------------------|-------------------------|----------------------|
| GET    | `/admin/parents`               | `admin.parents.index`   | List all parents     |
| GET    | `/admin/parents/create`        | `admin.parents.create`  | Create parent form   |
| POST   | `/admin/parents`               | `admin.parents.store`   | Store new parent     |
| GET    | `/admin/parents/{parent}`      | `admin.parents.show`    | View parent details  |
| GET    | `/admin/parents/{parent}/edit` | `admin.parents.edit`    | Edit parent form     |
| PUT    | `/admin/parents/{parent}`      | `admin.parents.update`  | Update parent        |
| DELETE | `/admin/parents/{parent}`      | `admin.parents.destroy` | Delete parent        |

### Schedules

| Method | URI                                   | Name                       | Description               |
|--------|---------------------------------------|----------------------------|---------------------------|
| GET    | `/admin/schedules`                    | `admin.schedules.index`    | List all schedules        |
| GET    | `/admin/schedules/create`             | `admin.schedules.create`   | Create schedule form      |
| POST   | `/admin/schedules`                    | `admin.schedules.store`    | Store new schedule        |
| GET    | `/admin/schedules/{schedule}`         | `admin.schedules.show`     | View schedule details     |
| GET    | `/admin/schedules/{schedule}/edit`    | `admin.schedules.edit`     | Edit schedule form        |
| PUT    | `/admin/schedules/{schedule}`         | `admin.schedules.update`   | Update schedule           |
| DELETE | `/admin/schedules/{schedule}`         | `admin.schedules.destroy`  | Delete schedule           |
| GET    | `/admin/schedules/weekly/{class?}`    | `admin.schedules.weekly`   | Weekly schedule view      |

### Attendances

| Method | URI                                          | Name                            | Description                |
|--------|----------------------------------------------|---------------------------------|----------------------------|
| GET    | `/admin/attendances`                         | `admin.attendances.index`       | List all attendances       |
| GET    | `/admin/attendances/create`                  | `admin.attendances.create`      | Create attendance form     |
| POST   | `/admin/attendances/store`                   | `admin.attendances.store`       | Store attendance records   |
| GET    | `/admin/attendances/{attendance}`            | `admin.attendances.show`        | View attendance details    |
| GET    | `/admin/attendances/{attendance}/edit`       | `admin.attendances.edit`        | Edit attendance form       |
| PUT    | `/admin/attendances/{attendance}`            | `admin.attendances.update`      | Update attendance          |
| DELETE | `/admin/attendances/{attendance}`            | `admin.attendances.destroy`     | Delete attendance          |
| GET    | `/admin/attendances/by-course/{course}`      | `admin.attendances.by-course`   | Attendance by course       |
| GET    | `/admin/attendances/analytics`               | `admin.attendances.analytics`   | Attendance analytics       |

### Invoices

| Method | URI                                         | Name                          | Description                  |
|--------|---------------------------------------------|-------------------------------|------------------------------|
| GET    | `/admin/invoices`                           | `admin.invoices.index`        | List all invoices            |
| GET    | `/admin/invoices/create`                    | `admin.invoices.create`       | Create invoice form          |
| POST   | `/admin/invoices`                           | `admin.invoices.store`        | Store new invoice            |
| GET    | `/admin/invoices/{invoice}`                 | `admin.invoices.show`         | View invoice details         |
| DELETE | `/admin/invoices/{invoice}`                 | `admin.invoices.destroy`      | Delete invoice               |
| POST   | `/admin/invoices/{invoice}/payments`        | `admin.invoices.payments`     | Record payment on invoice    |
| POST   | `/admin/invoices/{invoice}/reduction`       | `admin.invoices.reduction`    | Apply reduction/discount     |
| GET    | `/admin/invoices/overdue`                   | `admin.invoices.overdue`      | List overdue invoices        |

### Teacher Contracts (CRUD)

| Method | URI                                            | Name                              | Description              |
|--------|------------------------------------------------|-----------------------------------|--------------------------|
| GET    | `/admin/teacher-contracts`                     | `admin.teacher-contracts.index`   | List all contracts       |
| GET    | `/admin/teacher-contracts/create`              | `admin.teacher-contracts.create`  | Create contract form     |
| POST   | `/admin/teacher-contracts`                     | `admin.teacher-contracts.store`   | Store new contract       |
| GET    | `/admin/teacher-contracts/{teacherContract}`   | `admin.teacher-contracts.show`    | View contract details    |
| GET    | `/admin/teacher-contracts/{teacherContract}/edit` | `admin.teacher-contracts.edit` | Edit contract form       |
| PUT    | `/admin/teacher-contracts/{teacherContract}`   | `admin.teacher-contracts.update`  | Update contract          |
| DELETE | `/admin/teacher-contracts/{teacherContract}`   | `admin.teacher-contracts.destroy` | Delete contract          |

### Teacher Withdrawals

| Method | URI                                                        | Name                                    | Description              |
|--------|------------------------------------------------------------|-----------------------------------------|--------------------------|
| GET    | `/admin/teacher-withdrawals`                               | `admin.teacher-withdrawals.index`       | List all withdrawals     |
| GET    | `/admin/teacher-withdrawals/{teacherWithdrawal}`           | `admin.teacher-withdrawals.show`        | View withdrawal details  |
| PATCH  | `/admin/teacher-withdrawals/{teacherWithdrawal}/approve`   | `admin.teacher-withdrawals.approve`     | Approve withdrawal       |
| PATCH  | `/admin/teacher-withdrawals/{teacherWithdrawal}/complete`  | `admin.teacher-withdrawals.complete`    | Mark as completed        |
| POST   | `/admin/teacher-withdrawals/{teacherWithdrawal}/reject`    | `admin.teacher-withdrawals.reject`      | Reject withdrawal        |

---

## Teacher Routes (`teacher.php`)

**Prefix:** `/teacher` | **Middleware:** `auth`, `role:is-teacher` | **Name prefix:** `teacher.`

### Dashboard

| Method | URI                  | Name                | Description              |
|--------|----------------------|---------------------|--------------------------|
| GET    | `/teacher/dashboard` | `teacher.dashboard` | Teacher dashboard        |

### Quizzes (CRUD)

| Method | URI                                          | Name                             | Description              |
|--------|----------------------------------------------|----------------------------------|--------------------------|
| GET    | `/teacher/quizzes`                           | `teacher.quizzes.index`          | List teacher's quizzes   |
| GET    | `/teacher/quizzes/create`                    | `teacher.quizzes.create`         | Create quiz form         |
| POST   | `/teacher/quizzes`                           | `teacher.quizzes.store`          | Store new quiz           |
| GET    | `/teacher/quizzes/{quiz}`                    | `teacher.quizzes.show`           | View quiz details        |
| GET    | `/teacher/quizzes/{quiz}/edit`               | `teacher.quizzes.edit`           | Edit quiz form           |
| PUT    | `/teacher/quizzes/{quiz}`                    | `teacher.quizzes.update`         | Update quiz              |
| DELETE | `/teacher/quizzes/{quiz}`                    | `teacher.quizzes.destroy`        | Delete quiz              |
| PATCH  | `/teacher/quizzes/{quiz}/publish`            | `teacher.quizzes.publish`        | Publish quiz             |
| GET    | `/teacher/quizzes/{quiz}/correct`            | `teacher.quizzes.correct`        | Manual correction form   |
| POST   | `/teacher/quizzes/{quiz}/submit-correction`  | `teacher.quizzes.submit-correction` | Submit manual corrections |

### Announcements (CRUD)

| Method | URI                                          | Name                                | Description              |
|--------|----------------------------------------------|--------------------------------------|--------------------------|
| GET    | `/teacher/announcements`                     | `teacher.announcements.index`        | List announcements       |
| GET    | `/teacher/announcements/create`              | `teacher.announcements.create`       | Create announcement form |
| POST   | `/teacher/announcements`                     | `teacher.announcements.store`        | Store announcement       |
| GET    | `/teacher/announcements/{announcement}`      | `teacher.announcements.show`         | View announcement        |
| GET    | `/teacher/announcements/{announcement}/edit` | `teacher.announcements.edit`         | Edit announcement form   |
| PUT    | `/teacher/announcements/{announcement}`      | `teacher.announcements.update`       | Update announcement      |
| DELETE | `/teacher/announcements/{announcement}`      | `teacher.announcements.destroy`      | Delete announcement      |

### Attendance

| Method | URI                                      | Name                          | Description                 |
|--------|------------------------------------------|-------------------------------|-----------------------------|
| GET    | `/teacher/attendances`                   | `teacher.attendances.index`   | List teacher's attendances  |
| GET    | `/teacher/attendances/mark/{course}`     | `teacher.attendances.mark`    | Mark attendance for course  |
| POST   | `/teacher/attendances/store`             | `teacher.attendances.store`   | Store attendance records    |
| GET    | `/teacher/attendances/history/{course}`  | `teacher.attendances.history` | View attendance history     |

### Withdrawals

| Method | URI                       | Name                       | Description                 |
|--------|---------------------------|----------------------------|-----------------------------|
| GET    | `/teacher/withdrawals`    | `teacher.withdrawals.index`| List teacher's withdrawals  |
| POST   | `/teacher/withdrawals`    | `teacher.withdrawals.store`| Submit withdrawal request   |

### Schedule

| Method | URI                  | Name                | Description              |
|--------|----------------------|---------------------|--------------------------|
| GET    | `/teacher/schedule`  | `teacher.schedule`  | View teacher's schedule  |

---

## Student Routes (`student.php`)

**Prefix:** `/student` | **Middleware:** `auth`, `role:is-student` | **Name prefix:** `student.`

| Method | URI                                 | Name                       | Description                 |
|--------|-------------------------------------|----------------------------|-----------------------------|
| GET    | `/student/dashboard`                | `student.dashboard`        | Student dashboard           |
| GET    | `/student/quizzes`                  | `student.quizzes.index`    | List available quizzes      |
| GET    | `/student/quizzes/{quiz}/take`      | `student.quizzes.take`     | Take a quiz                 |
| POST   | `/student/quizzes/{quiz}/submit`    | `student.quizzes.submit`   | Submit quiz answers         |
| GET    | `/student/quizzes/{quiz}/results`   | `student.quizzes.results`  | View quiz results           |
| GET    | `/student/schedule`                 | `student.schedule`         | View student's schedule     |
| GET    | `/student/invoices`                 | `student.invoices.index`   | List student's invoices     |
| GET    | `/student/invoices/{invoice}`       | `student.invoices.show`    | View invoice details        |

---

## Parent Routes (`parent.php`)

**Prefix:** `/parent` | **Middleware:** `auth`, `role:is-parent` | **Name prefix:** `parent.`

| Method | URI                                             | Name                            | Description                     |
|--------|-------------------------------------------------|---------------------------------|---------------------------------|
| GET    | `/parent/dashboard`                             | `parent.dashboard`              | Parent dashboard                |
| GET    | `/parent/children`                              | `parent.children`               | List children                   |
| GET    | `/parent/children/{student}/invoices`           | `parent.children.invoices`      | View child's invoices           |
| GET    | `/parent/children/invoices/{invoice}`           | `parent.children.invoices.show` | View specific invoice           |
| GET    | `/parent/children/{student}/schedule`           | `parent.children.schedule`      | View child's schedule           |

---

## API Routes (`api.php`)

**Middleware:** `auth:sanctum`

| Method | URI          | Description              |
|--------|--------------|--------------------------|
| GET    | `/api/user`  | Get authenticated user   |
