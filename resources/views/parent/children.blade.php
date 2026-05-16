@extends('layouts.app')

@section('title', 'Mes enfants')
@section('page-title', 'Mes enfants')
@section('page-subtitle', 'Consulter les informations de vos enfants')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($children as $child)
        <div class="card">
            <div class="card-body text-center space-y-3">
                <div class="avatar avatar-xl bg-primary-100 text-primary-700 mx-auto text-2xl">
                    {{ $child->initials }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $child->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $child->class->name ?? 'Aucune classe' }}</p>
                </div>
                <span class="badge-{{ $child->is_active ? 'success' : 'danger' }} inline-block">
                    {{ $child->is_active ? 'Actif' : 'Inactif' }}
                </span>
                <div class="text-left space-y-2 pt-3 border-t">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Taux de présence</span>
                        <span class="text-sm font-medium">{{ $child->attendance_rate ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Factures à venir</span>
                        <span class="text-sm font-medium text-danger-600">{{ $child->pending_invoices_count ?? 0 }}</span>
                    </div>
                </div>
                <div class="pt-2 space-y-2">
                    <a href="{{ route('parent.children.schedule', $child) }}" class="btn-outline w-full justify-center">Voir l'emploi du temps</a>
                    <a href="{{ route('parent.children.invoices', $child) }}" class="btn-outline w-full justify-center">Voir les factures</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="card">
                <div class="card-body text-center py-12">
                    <p class="text-gray-500">Aucun enfant lié à votre compte</p>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection
