# School ERP + LMS Platform

A comprehensive **Enterprise Resource Planning (ERP)** and **Learning Management System (LMS)** platform built with **Laravel 12**, designed for educational institutions to manage students, teachers, parents, courses, enrollments, scheduling, invoicing, quizzes, and more.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [User Roles](#user-roles)
- [Project Structure](#project-structure)
- [Documentation](#documentation)
- [Testing](#testing)
- [License](#license)

## Features

### Academic Management
- **Levels & Grades** — Hierarchical academic structure (Level → Grade → Class)
- **Classes (Sections)** — Manage class sections with capacity, pricing, and bundle discounts
- **Courses** — Course management with teacher assignments, pricing, and public listings
- **Scheduling** — Weekly timetable with conflict detection (teacher, classroom, class)

### Student Lifecycle
- **Self-Registration** — Students register via a public-facing landing page
- **Enrollment Workflow** — Pending → Approved / Rejected → Archived pipeline
- **Section (Bundle) Enrollment** — Enroll in an entire section's courses at a discounted bundle price
- **Attendance Tracking** — Per-course, per-session attendance with analytics

### Assessment & Learning
- **Quiz Engine** — Create quizzes with multiple question types (MCQ, text)
- **Auto-Correction** — MCQ answers are auto-graded; text answers support manual correction
- **Quiz Results** — Score tracking, feedback, and result history

### Financial Management
- **Invoicing** — Auto-generated invoices on enrollment approval with reduction/discount support
- **Payments** — Record partial or full payments against invoices
- **Overdue Tracking** — Automatic overdue detection with analytics
- **Teacher Wallet** — Per-session, per-student, percentage, or monthly contract-based earnings
- **Withdrawal System** — Teachers request withdrawals; admins approve/reject/complete

### Communication
- **Announcements** — Teachers publish global or class-specific announcements
- **Email Notifications** — Enrollment confirmations, approvals, rejections, welcome emails, account setup invitations

### Administration
- **Dashboard Analytics** — Role-specific dashboards with KPIs and statistics
- **Audit Logging** — Full audit trail for all CRUD operations
- **Role-Based Access Control** — Middleware-enforced role checking (Admin, Teacher, Student, Parent)
- **Teacher Applications** — Public teacher registration with CV upload and admin approval

### Public Website
- **Landing Page** — Showcase featured courses and bundles
- **Course Catalog** — Searchable, filterable course listing with level-based filtering
- **Course & Bundle Details** — Detailed pages with pricing, schedules, and enrollment

## Tech Stack

| Component       | Technology              |
|-----------------|------------------------|
| Framework       | Laravel 12 (PHP 8.2+)  |
| Database        | SQLite (default) / MySQL / PostgreSQL |
| Authentication  | Laravel Sanctum        |
| Frontend        | Blade Templates + Vite |
| Queue           | Database driver         |
| Cache           | Database driver         |
| Session         | Database driver         |
| Email           | Log driver (configurable) |

## Requirements

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x (for Vite asset compilation)
- **NPM** >= 9.x
- **SQLite** (default) or MySQL 8.0+ / PostgreSQL 15+

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/mohammedcherifmohamed/erp_v2.git
cd erp_v2
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database Setup

By default, the application uses SQLite:

```bash
touch database/database.sqlite
php artisan migrate
```

For MySQL/PostgreSQL, update the `DB_*` variables in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erp_v2
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Seed the Database (Optional)

```bash
php artisan db:seed
```

### 7. Build Frontend Assets

```bash
npm run build
```

## Configuration

Key environment variables in `.env`:

| Variable           | Description                          | Default          |
|--------------------|--------------------------------------|------------------|
| `APP_NAME`         | Application display name             | `Laravel`        |
| `APP_ENV`          | Environment (`local`, `production`)  | `local`          |
| `APP_DEBUG`        | Enable debug mode                    | `true`           |
| `APP_URL`          | Application base URL                 | `http://localhost` |
| `DB_CONNECTION`    | Database driver                      | `sqlite`         |
| `MAIL_MAILER`      | Mail transport driver                | `log`            |
| `QUEUE_CONNECTION` | Queue backend driver                 | `database`       |
| `SESSION_DRIVER`   | Session storage driver               | `database`       |
| `CACHE_STORE`      | Cache backend driver                 | `database`       |

## Running the Application

### Development Server

```bash
# Start the Laravel development server
php artisan serve

# In a separate terminal, start Vite for hot-reloading
npm run dev
```

The application will be available at `http://localhost:8000`.

### Queue Worker (for notifications)

```bash
php artisan queue:work
```

## User Roles

The platform supports four distinct user roles:

| Role       | Description                                                          |
|------------|----------------------------------------------------------------------|
| **Admin**  | Full system access — manages users, classes, enrollments, invoices   |
| **Teacher**| Manages courses, quizzes, attendance, announcements, withdrawals     |
| **Student**| Views schedule, takes quizzes, views invoices, enrolls in courses    |
| **Parent** | Views children's schedules, invoices, and academic information       |

For detailed role permissions, see [docs/user-roles.md](docs/user-roles.md).

## Project Structure

```
erp_v2/
├── app/
│   ├── Events/              # Domain events (enrollment, registration, etc.)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       # Admin panel controllers
│   │   │   ├── Auth/        # Authentication controllers
│   │   │   ├── Parent/      # Parent portal controllers
│   │   │   ├── Student/     # Student portal controllers
│   │   │   └── Teacher/     # Teacher portal controllers
│   │   ├── Middleware/      # Role-checking middleware
│   │   └── Requests/        # Form request validation
│   ├── Listeners/           # Event listeners (send notifications)
│   ├── Models/              # Eloquent models (22 models)
│   ├── Notifications/       # Email notification classes
│   ├── Policies/            # Authorization policies
│   ├── Providers/           # Service providers
│   └── Services/            # Business logic layer
├── config/                  # Application configuration
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── lang/                    # Localization files
├── public/                  # Public assets
├── resources/
│   └── views/               # Blade templates
├── routes/
│   ├── web.php              # Public & auth routes
│   ├── admin.php            # Admin routes
│   ├── teacher.php          # Teacher routes
│   ├── student.php          # Student routes
│   └── parent.php           # Parent routes
├── storage/                 # App storage
├── tests/                   # PHPUnit tests
├── docs/                    # Project documentation
├── composer.json            # PHP dependencies
├── package.json             # Node dependencies
└── vite.config.js           # Vite configuration
```

## Documentation

Comprehensive documentation is available in the [`docs/`](docs/) directory:

| Document                                              | Description                                      |
|-------------------------------------------------------|--------------------------------------------------|
| [Architecture](docs/architecture.md)                  | System architecture, design patterns, and layers |
| [Database Schema](docs/database.md)                   | Database tables, relationships, and ERD          |
| [API Reference](docs/api-reference.md)                | All routes, endpoints, and request/response docs |
| [User Roles & Permissions](docs/user-roles.md)        | Role-based access control details                |
| [Services](docs/services.md)                          | Business logic and service layer documentation   |
| [Events & Notifications](docs/events-notifications.md)| Event system and email notifications             |
| [Deployment Guide](docs/deployment.md)                | Production deployment instructions               |

## Testing

```bash
# Run the full test suite
php artisan test

# Run with coverage
php artisan test --coverage

# Run a specific test
php artisan test --filter=ExampleTest
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
