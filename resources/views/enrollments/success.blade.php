@extends('layouts.app')

@section('title', 'Inscription envoyée')
@section('page-title', 'Inscription envoyée avec succès')
@section('page-subtitle', 'Votre demande est en cours de traitement')

@section('content')
<div class="max-w-2xl mx-auto text-center py-12">
    <div class="card p-12">
        <div class="w-20 h-20 bg-success-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 mb-2">Demande envoyée !</h2>

        @if($enrollment->course_id)
            <p class="text-gray-500 mb-6">
                Votre demande d'inscription au cours <strong>{{ $enrollment->course->name }}</strong> a été soumise avec succès.
                Un administrateur va examiner votre demande sous peu.
            </p>
        @else
            <p class="text-gray-500 mb-6">
                Votre demande d'inscription au forfait complet <strong>{{ $enrollment->classe->name }}</strong> a été soumise avec succès.
                Un administrateur va examiner votre demande sous peu.
            </p>
        @endif

        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left space-y-3">
            @if($enrollment->course_id)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Type d'inscription</span>
                    <span class="badge-primary">Cours individuel</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Cours</span>
                    <span class="text-sm font-medium">{{ $enrollment->course->name }}</span>
                </div>
                @if($enrollment->course->price)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Prix</span>
                        <span class="text-sm font-semibold">{{ number_format($enrollment->course->price, 2) }} DA</span>
                    </div>
                @endif
            @else
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Type d'inscription</span>
                    <span class="badge-success">Forfait complet</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Section</span>
                    <span class="text-sm font-medium">{{ $enrollment->classe->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Cours inclus</span>
                    <span class="text-sm font-medium">{{ $enrollment->classe->courses->count() }} cours</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Prix total</span>
                    <span class="text-sm font-semibold">
                        @if($enrollment->classe->has_reduction)
                            <span class="text-gray-400 line-through">{{ number_format($enrollment->classe->total_courses_price, 2) }} DA</span>
                            <span class="text-danger-600 font-bold">{{ number_format($enrollment->classe->reduction_price, 2) }} DA</span>
                        @else
                            {{ number_format($enrollment->classe->total_courses_price, 2) }} DA
                        @endif
                    </span>
                </div>
            @endif
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Niveau</span>
                <span class="text-sm font-medium">{{ $enrollment->classe->grade->name ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Cycle</span>
                <span class="text-sm font-medium">{{ $enrollment->classe->grade->level->name ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Statut</span>
                <span class="badge-warning">En attente de validation</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Date de la demande</span>
                <span class="text-sm font-medium">{{ $enrollment->created_at->format('d/m/Y à H:i') }}</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('student.dashboard') }}" class="btn-primary">Aller au tableau de bord</a>
            <a href="{{ route('courses') }}" class="btn-outline">Parcourir d'autres cours</a>
        </div>
    </div>
</div>
@endsection