@extends('layouts.app')

@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord Admin')
@section('page-subtitle', 'Aperçu de votre établissement éducatif')

@section('content')
<div class="space-y-6">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Total Étudiants</p>
                    <p class="stat-value">{{ $stats['total_students'] }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.students.index') }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                Voir tous les étudiants &rarr;
            </a>
        </div>

        <div class="stat-card animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Total Enseignants</p>
                    <p class="stat-value">{{ $stats['total_teachers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.teachers.index') }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                Voir tous les enseignants &rarr;
            </a>
        </div>

        <div class="stat-card animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Inscriptions en attente</p>
                    <p class="stat-value text-warning-600">{{ $stats['pending_enrollments'] }}</p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.enrollments.pending') }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                Voir les demandes &rarr;
            </a>
        </div>

        <div class="stat-card animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Revenu total</p>
                    <p class="stat-value">{{ number_format($stats['total_revenue'], 2) }} MAD</p>
                </div>
                <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.invoices.index') }}" class="mt-3 inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                Voir les finances &rarr;
            </a>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="card p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Sections</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total_classes'] }}</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-success-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Cours</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total_courses'] }}</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-warning-50 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h3v6m6-6a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Factures impayées</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['unpaid_invoices'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Enrollments --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Inscriptions récentes</h3>
                <a href="{{ route('admin.enrollments.index') }}" class="text-sm text-primary-600 hover:text-primary-700">Tout voir</a>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Classe</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEnrollments as $enrollment)
                                <tr>
                                    <td class="font-medium">{{ $enrollment->student->full_name }}</td>
                                    <td>{{ $enrollment->classe->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge-{{ $enrollment->status === 'approved' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td class="text-gray-500">{{ $enrollment->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-500 py-8">Aucune inscription pour le moment</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Announcements --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Annonces récentes</h3>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <h4 class="font-medium text-gray-900">{{ $announcement->title }}</h4>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($announcement->content, 100) }}</p>
                            <p class="text-xs text-gray-400 mt-2">Par {{ $announcement->author->full_name }} &middot; {{ $announcement->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">Aucune annonce pour le moment</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection