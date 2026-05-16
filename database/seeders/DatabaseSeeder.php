<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'School',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+212 6 00 00 00 00',
        ]);

        // Teachers
        $teacher1 = User::create([
            'first_name' => 'Fatima',
            'last_name' => 'Zahra',
            'email' => 'teacher1@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+212 6 11 11 11 11',
        ]);
        $teacher1->teacherProfile()->create([
            'gender' => 'female',
            'specialization' => 'Mathematics',
            'hire_date' => now()->subYears(3),
            'hourly_rate' => 150,
            'wallet_balance' => 5000,
        ]);

        $teacher2 = User::create([
            'first_name' => 'Mohammed',
            'last_name' => 'Ali',
            'email' => 'teacher2@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+212 6 22 22 22 22',
        ]);
        $teacher2->teacherProfile()->create([
            'gender' => 'male',
            'specialization' => 'Physics',
            'hire_date' => now()->subYears(5),
            'hourly_rate' => 180,
            'wallet_balance' => 8500,
        ]);

        $teacher3 = User::create([
            'first_name' => 'Amina',
            'last_name' => 'Benziane',
            'email' => 'teacher3@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+212 6 33 33 33 33',
        ]);
        $teacher3->teacherProfile()->create([
            'gender' => 'female',
            'specialization' => 'Arabic Language',
            'hire_date' => now()->subYears(4),
            'hourly_rate' => 130,
            'wallet_balance' => 3200,
        ]);

        // Parents
        $parent1 = User::create([
            'first_name' => 'Ahmed',
            'last_name' => 'Bennani',
            'email' => 'parent1@example.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'phone' => '+212 6 44 44 44 44',
        ]);
        $parent1->parentProfile()->create([
            'profession' => 'Engineer',
            'relationship' => 'father',
        ]);

        $parent2 = User::create([
            'first_name' => 'Sara',
            'last_name' => 'Fassi',
            'email' => 'parent2@example.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'phone' => '+212 6 55 55 55 55',
        ]);
        $parent2->parentProfile()->create([
            'profession' => 'Doctor',
            'relationship' => 'mother',
        ]);

        // Students
        $student1 = User::create([
            'first_name' => 'Youssef',
            'last_name' => 'Bennani',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+212 6 66 66 66 66',
        ]);
        $student1->studentProfile()->create([
            'arabic_name' => 'يوسف بناني',
            'date_of_birth' => '2010-03-15',
            'gender' => 'male',
            'parent_id' => $parent1->id,
        ]);

        $student2 = User::create([
            'first_name' => 'Lina',
            'last_name' => 'Bennani',
            'email' => 'student2@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+212 6 77 77 77 77',
        ]);
        $student2->studentProfile()->create([
            'arabic_name' => 'لينا بناني',
            'date_of_birth' => '2012-07-22',
            'gender' => 'female',
            'parent_id' => $parent1->id,
        ]);

        $student3 = User::create([
            'first_name' => 'Omar',
            'last_name' => 'Fassi',
            'email' => 'student3@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '+212 6 88 88 88 88',
        ]);
        $student3->studentProfile()->create([
            'arabic_name' => 'عمر فاسي',
            'date_of_birth' => '2011-11-08',
            'gender' => 'male',
            'parent_id' => $parent2->id,
        ]);

        // Academic Structure
        $level1 = Level::create([
            'name' => 'Primary School',
            'name_ar' => 'الابتدائي',
            'code' => 'PRI',
            'sort_order' => 1,
        ]);

        $level2 = Level::create([
            'name' => 'Middle School',
            'name_ar' => 'الإعدادي',
            'code' => 'MID',
            'sort_order' => 2,
        ]);

        $level3 = Level::create([
            'name' => 'High School',
            'name_ar' => 'الثانوي',
            'code' => 'HIG',
            'sort_order' => 3,
        ]);

        // Grades
        $grade1 = Grade::create([
            'level_id' => $level1->id,
            'name' => 'Grade 1',
            'name_ar' => 'السنة الأولى',
            'code' => 'PRI-1',
            'sort_order' => 1,
        ]);

        $grade2 = Grade::create([
            'level_id' => $level1->id,
            'name' => 'Grade 2',
            'name_ar' => 'السنة الثانية',
            'code' => 'PRI-2',
            'sort_order' => 2,
        ]);

        $grade3 = Grade::create([
            'level_id' => $level2->id,
            'name' => 'Grade 7',
            'name_ar' => 'السنة الأولى إعدادي',
            'code' => 'MID-1',
            'sort_order' => 1,
        ]);

        // Classes
        $class1 = Classe::create([
            'grade_id' => $grade1->id,
            'name' => 'Section A',
            'name_ar' => 'الشعبة أ',
            'capacity' => 30,
            'enrolled_count' => 2,
            'is_public' => true,
            'price' => 5000,
            'reduction_price' => 4500,
            'homeroom_teacher_id' => $teacher1->id,
        ]);

        $class2 = Classe::create([
            'grade_id' => $grade1->id,
            'name' => 'Section B',
            'name_ar' => 'الشعبة ب',
            'capacity' => 30,
            'enrolled_count' => 1,
            'is_public' => true,
            'price' => 5000,
            'homeroom_teacher_id' => $teacher2->id,
        ]);

        $class3 = Classe::create([
            'grade_id' => $grade3->id,
            'name' => 'Section A',
            'name_ar' => 'الشعبة أ',
            'capacity' => 30,
            'enrolled_count' => 0,
            'is_public' => true,
            'price' => 6000,
            'homeroom_teacher_id' => $teacher3->id,
        ]);

        // Courses
        $course1 = Course::create([
            'class_id' => $class1->id,
            'name' => 'Mathematics',
            'name_ar' => 'الرياضيات',
            'code' => 'MATH-PRI1-A',
            'teacher_id' => $teacher1->id,
            'sessions_count' => 40,
            'credits' => 4,
            'price' => 3000,
        ]);

        $course2 = Course::create([
            'class_id' => $class1->id,
            'name' => 'Physics',
            'name_ar' => 'الفيزياء',
            'code' => 'PHY-PRI1-A',
            'teacher_id' => $teacher2->id,
            'sessions_count' => 30,
            'credits' => 3,
            'price' => 2500,
        ]);

        $course3 = Course::create([
            'class_id' => $class2->id,
            'name' => 'Mathematics',
            'name_ar' => 'الرياضيات',
            'code' => 'MATH-PRI1-B',
            'teacher_id' => $teacher1->id,
            'sessions_count' => 40,
            'credits' => 4,
            'price' => 3000,
        ]);

        // Enrollments
        $student1->enrollments()->create([
            'class_id' => $class1->id,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        $student2->enrollments()->create([
            'class_id' => $class1->id,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        $student3->enrollments()->create([
            'class_id' => $class2->id,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        // Schedules
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $times = [
            ['08:00', '09:30'],
            ['09:45', '11:15'],
            ['11:30', '13:00'],
        ];

        $scheduleData = [
            ['course_id' => 1, 'class_id' => 1, 'teacher_id' => 1, 'classroom' => 'Room 101', 'day_of_week' => 'monday', 'start_time' => '08:00', 'end_time' => '09:30'],
            ['course_id' => 1, 'class_id' => 1, 'teacher_id' => 1, 'classroom' => 'Room 101', 'day_of_week' => 'wednesday', 'start_time' => '08:00', 'end_time' => '09:30'],
            ['course_id' => 2, 'class_id' => 1, 'teacher_id' => 2, 'classroom' => 'Lab 1', 'day_of_week' => 'tuesday', 'start_time' => '09:45', 'end_time' => '11:15'],
            ['course_id' => 2, 'class_id' => 1, 'teacher_id' => 2, 'classroom' => 'Lab 1', 'day_of_week' => 'thursday', 'start_time' => '09:45', 'end_time' => '11:15'],
            ['course_id' => 3, 'class_id' => 2, 'teacher_id' => 1, 'classroom' => 'Room 102', 'day_of_week' => 'monday', 'start_time' => '11:30', 'end_time' => '13:00'],
            ['course_id' => 3, 'class_id' => 2, 'teacher_id' => 1, 'classroom' => 'Room 102', 'day_of_week' => 'wednesday', 'start_time' => '11:30', 'end_time' => '13:00'],
        ];

        foreach ($scheduleData as $data) {
            \App\Models\Schedule::create($data);
        }

        // Teacher contracts
        \App\Models\TeacherContract::create([
            'teacher_id' => 2,
            'course_id' => 1,
            'contract_type' => 'per_session',
            'rate' => 150,
        ]);

        \App\Models\TeacherContract::create([
            'teacher_id' => 3,
            'course_id' => 2,
            'contract_type' => 'per_session',
            'rate' => 180,
        ]);

        // Invoices
        $invoice1 = \App\Models\Invoice::create([
            'invoice_number' => 'INV-2024-000001',
            'student_id' => 5,
            'parent_id' => 3,
            'class_id' => 1,
            'total_amount' => 5000,
            'paid_amount' => 5000,
            'remaining_amount' => 0,
            'status' => 'paid',
            'due_date' => now()->subDays(10),
            'description' => 'Tuition fee for Section A - Grade 1',
        ]);

        $invoice2 = \App\Models\Invoice::create([
            'invoice_number' => 'INV-2024-000002',
            'student_id' => 6,
            'parent_id' => 3,
            'class_id' => 1,
            'total_amount' => 5000,
            'paid_amount' => 2500,
            'remaining_amount' => 2500,
            'status' => 'partial',
            'due_date' => now()->addDays(20),
            'description' => 'Tuition fee for Section A - Grade 1',
        ]);

        $invoice3 = \App\Models\Invoice::create([
            'invoice_number' => 'INV-2024-000003',
            'student_id' => 7,
            'parent_id' => 4,
            'class_id' => 2,
            'total_amount' => 5000,
            'paid_amount' => 0,
            'remaining_amount' => 5000,
            'status' => 'unpaid',
            'due_date' => now()->addDays(15),
            'description' => 'Tuition fee for Section B - Grade 1',
        ]);

        // Payments
        \App\Models\Payment::create([
            'invoice_id' => 1,
            'collected_by' => 1,
            'amount' => 5000,
            'payment_method' => 'bank_transfer',
            'paid_at' => now()->subDays(5),
        ]);

        \App\Models\Payment::create([
            'invoice_id' => 2,
            'collected_by' => 1,
            'amount' => 2500,
            'payment_method' => 'cash',
            'paid_at' => now()->subDays(3),
        ]);

        // Announcements
        \App\Models\Announcement::create([
            'title' => 'Welcome to the new school year!',
            'content' => 'We are excited to welcome all students and parents to the 2024-2025 school year. Classes will begin on September 15th. Please make sure all enrollment documents are completed.',
            'author_id' => 1,
            'is_global' => true,
            'is_published' => true,
            'published_at' => now(),
        ]);

        \App\Models\Announcement::create([
            'title' => 'Math Competition Registration',
            'content' => 'Registration for the annual Mathematics competition is now open. Interested students should sign up by October 1st.',
            'author_id' => 2,
            'class_id' => 1,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@school.com / password');
        $this->command->info('Teacher: teacher1@school.com / password');
        $this->command->info('Student: student1@example.com / password');
        $this->command->info('Parent: parent1@example.com / password');
    }
}