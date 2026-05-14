<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School ERP') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <header class="absolute top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">SchoolERP</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="btn-outline">Sign In</a>
                    <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero --}}
        <section class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight">
                        Modern School
                        <span class="text-primary-600">Management Platform</span>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto">
                        A comprehensive ERP + LMS system for educational institutions. Manage students, teachers, classes, attendance, payments, and more — all in one place.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="btn-primary btn-lg">Start Free Trial</a>
                        <a href="#features" class="btn-outline btn-lg">Learn More</a>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                    <div class="text-center p-6">
                        <div class="text-3xl font-bold text-primary-600">5,000+</div>
                        <div class="text-sm text-gray-500 mt-1">Students</div>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-3xl font-bold text-primary-600">500+</div>
                        <div class="text-sm text-gray-500 mt-1">Teachers</div>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-3xl font-bold text-primary-600">200+</div>
                        <div class="text-sm text-gray-500 mt-1">Classes</div>
                    </div>
                    <div class="text-center p-6">
                        <div class="text-3xl font-bold text-primary-600">50+</div>
                        <div class="text-sm text-gray-500 mt-1">Institutions</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="features" class="py-20 bg-gray-50 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900">Everything you need to run your school</h2>
                    <p class="mt-4 text-gray-500">Complete ERP + LMS solution for modern educational institutions</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="card p-6">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Academic Management</h3>
                        <p class="mt-2 text-sm text-gray-500">Manage levels, grades, classes, courses, and schedules with ease.</p>
                    </div>
                    <div class="card p-6">
                        <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Student & Teacher Portal</h3>
                        <p class="mt-2 text-sm text-gray-500">Dedicated dashboards for students, teachers, and parents.</p>
                    </div>
                    <div class="card p-6">
                        <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h3v6m6-6a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Payments & Invoicing</h3>
                        <p class="mt-2 text-sm text-gray-500">Track payments, generate invoices, and manage finances.</p>
                    </div>
                    <div class="card p-6">
                        <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Attendance Tracking</h3>
                        <p class="mt-2 text-sm text-gray-500">Mark and monitor attendance with detailed analytics.</p>
                    </div>
                    <div class="card p-6">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Quiz System</h3>
                        <p class="mt-2 text-sm text-gray-500">Create quizzes with auto-correction for multiple choice questions.</p>
                    </div>
                    <div class="card p-6">
                        <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
                        <p class="mt-2 text-sm text-gray-500">Keep everyone informed with targeted announcements.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} SchoolERP. All rights reserved.
            </div>
        </footer>
    </main>
</body>
</html>