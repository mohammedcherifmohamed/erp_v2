@extends('layouts.app')

@section('title', $teacher->full_name)
@section('page-title', $teacher->full_name)
@section('page-subtitle', 'Profil de l\'enseignant')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="card lg:col-span-1">
            <div class="card-body text-center space-y-3">
                <div class="avatar avatar-xl bg-primary-100 text-primary-700 mx-auto text-2xl">
                    {{ $teacher->initials }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $teacher->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $teacher->email }}</p>
                </div>
                <span class="badge-{{ $teacher->is_active ? 'success' : 'danger' }} inline-block">
                    {{ $teacher->is_active ? 'Actif' : 'Inactif' }}
                </span>
                <div class="text-left space-y-2 pt-3 border-t">
                    <div><p class="text-sm text-gray-500">Téléphone</p><p class="text-sm font-medium">{{ $teacher->phone ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Genre</p><p class="text-sm font-medium">{{ ucfirst($teacher->gender ?? '-') }}</p></div>
                    <div><p class="text-sm text-gray-500">Spécialisation</p><p class="text-sm font-medium">{{ $teacher->specialization ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Nationalité</p><p class="text-sm font-medium">{{ $teacher->nationality ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Taux horaire</p><p class="text-sm font-medium">{{ number_format($teacher->hourly_rate ?? 0, 2) }}</p></div>
                    <div><p class="text-sm text-gray-500">Solde du portefeuille</p><p class="text-sm font-semibold text-primary-600">{{ number_format($teacher->wallet_balance ?? 0, 2) }}</p></div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-3 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Cours</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Code</th>
                                    <th>Section</th>
                                    <th>Séances</th>
                                    <th>Statut</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->coursesTeaching as $course)
                                    <tr>
                                        <td class="font-medium">{{ $course->name }}</td>
                                        <td><span class="badge-gray">{{ $course->code }}</span></td>
                                        <td>{{ $course->class->name ?? '-' }}</td>
                                        <td>{{ $course->sessions_count }}</td>
                                        <td>
                                            <span class="badge-{{ $course->is_active ? 'success' : 'danger' }}">{{ $course->is_active ? 'Actif' : 'Inactif' }}</span>
                                        </td>
                                        <td><a href="{{ route('admin.courses.show', $course) }}" class="text-sm text-primary-600">Voir</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-gray-500 py-8">Aucun cours attribué</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Emploi du temps</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Jour</th>
                                    <th>Cours</th>
                                    <th>Section</th>
                                    <th>Heure</th>
                                    <th>Salle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->schedules as $schedule)
                                    <tr>
                                        <td class="font-medium">{{ $schedule->day_of_week }}</td>
                                        <td>{{ $schedule->course->name ?? '-' }}</td>
                                        <td>{{ $schedule->course->class->name ?? '-' }}</td>
                                        <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                        <td>{{ $schedule->classroom ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-gray-500 py-8">Aucun emploi du temps</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Contrats</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Taux</th>
                                    <th>Cours / Section</th>
                                    <th>Statut</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->teacherContracts as $contract)
                                    <tr>
                                        <td class="font-medium">{{ ucfirst($contract->contract_type) }}</td>
                                        <td>{{ number_format($contract->rate, 2) }}</td>
                                        <td>{{ $contract->course->name ?? $contract->class->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge-{{ $contract->is_active ? 'success' : 'danger' }}">{{ $contract->is_active ? 'Actif' : 'Inactif' }}</span>
                                        </td>
                                        <td><a href="{{ route('admin.teacher-contracts.show', $contract) }}" class="text-sm text-primary-600">Voir</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-gray-500 py-8">Aucun contrat</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Transactions du portefeuille</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Montant</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->walletTransactions as $txn)
                                    <tr>
                                        <td>{{ $txn->created_at->format('M d, Y') }}</td>
                                        <td>{{ $txn->description }}</td>
                                        <td class="font-medium {{ $txn->amount > 0 ? 'text-success-600' : 'text-danger-600' }}">
                                            {{ $txn->amount > 0 ? '+' : '' }}{{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td><span class="badge-{{ $txn->type === 'credit' ? 'success' : 'danger' }}">{{ ucfirst($txn->type) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-gray-500 py-8">Aucune transaction</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn-primary">Modifier l'enseignant</a>
        <a href="{{ route('admin.teachers.index' ) }}" class="btn-outline">Retour aux enseignants</a>
    </div>
</div>
@endsection
