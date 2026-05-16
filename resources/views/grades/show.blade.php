@extends('layouts.app')

@section('title', $grade->name)
@section('page-title', $grade->name)
@section('page-subtitle', 'Détails de la classe')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Sections dans cette classe</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom de la section</th>
                                <th>Section</th>
                                <th>Capacité</th>
                                <th>Enseignant</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grade->classes as $class)
                                <tr>
                                    <td class="font-medium">{{ $class->name }}</td>
                                    <td>{{ $class->section ?? '-' }}</td>
                                    <td>{{ $class->capacity }}</td>
                                    <td>{{ $class->homeroomTeacher->full_name ?? '-' }}</td>
                                    <td>
                                        <span class="badge-{{ $class->is_active ? 'success' : 'danger' }}">
                                            {{ $class->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td><a href="{{ route('admin.classes.show', $class) }}" class="text-sm text-primary-600">Voir</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-gray-500 py-8">Aucune section dans cette classe</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Détails</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Niveau</p>
                    <p class="font-medium">{{ $grade->level->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Code</p>
                    <p class="font-medium">{{ $grade->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nom arabe</p>
                    <p class="font-medium">{{ $grade->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="text-sm text-gray-700">{{ $grade->description ?? 'Aucune description' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ordre</p>
                    <p class="font-medium">{{ $grade->sort_order }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nombre de sections</p>
                    <p class="font-medium">{{ $grade->classes->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    <span class="badge-{{ $grade->is_active ? 'success' : 'danger' }}">{{ $grade->is_active ? 'Actif' : 'Inactif' }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Créé le</p>
                    <p class="text-sm">{{ $grade->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.grades.edit', $grade) }}" class="btn-primary">Modifier la classe</a>
        <a href="{{ route('admin.grades.index') }}" class="btn-outline">Retour aux classes</a>
    </div>
</div>
@endsection
