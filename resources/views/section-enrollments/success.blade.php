@extends('layouts.app')

@section('title', 'Inscription au forfait réussie')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4">
    <div class="card max-w-2xl w-full text-center">
        <div class="card-body">
            <div class="w-20 h-20 bg-success-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Demande d'inscription envoyée !</h1>
            <p class="mt-3 text-gray-500">
                Votre demande d'inscription au forfait <strong>{{ $sectionEnrollment->section->name }}</strong> a bien été reçue.
            </p>

            <div class="mt-8 bg-gray-50 rounded-xl p-6 text-left">
                <h3 class="font-semibold text-gray-900 mb-4">Récapitulatif</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Forfait</span>
                        <span class="font-medium text-gray-900">{{ $sectionEnrollment->section->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Cours inclus</span>
                        <span class="font-medium text-gray-900">{{ $sectionEnrollment->section->courses->count() }} cours</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Montant</span>
                        <span class="font-medium text-gray-900">{{ number_format($sectionEnrollment->bundle_price_paid, 2) }} DA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Statut</span>
                        <span class="badge-warning">En attente de validation</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('student.dashboard') }}" class="btn-primary">Mon tableau de bord</a>
                <a href="{{ route('courses') }}" class="btn-outline">Parcourir d'autres cours</a>
            </div>
        </div>
    </div>
</div>
@endsection
