@extends('layouts.app')

@section('title', 'Candidatures en attente')
@section('page-title', 'Candidatures enseignants')
@section('page-subtitle', 'Examiner et approuver les nouvelles candidatures')

@section('content')
<div class="card">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500">{{ $teachers->total() }} candidature(s) en attente</span>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="btn-outline">Tous les enseignants</a>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Spécialisation</th>
                        <th>Expérience</th>
                        <th>Genre</th>
                        <th>CV</th>
                        <th>Postulé le</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td class="font-medium">{{ $teacher->full_name }}</td>
                            <td class="text-gray-500">{{ $teacher->email }}</td>
                            <td>{{ $teacher->phone ?? '-' }}</td>
                            <td>{{ $teacher->teacherProfile->specialization ?? '-' }}</td>
                            <td>{{ $teacher->teacherProfile->bio ? (preg_match('/(\d+)\s*ans?/i', $teacher->teacherProfile->bio, $m) ? $m[1] . ' ans' : '-') : '-' }}</td>
                            <td>{{ $teacher->teacherProfile->gender ? ucfirst($teacher->teacherProfile->gender) : '-' }}</td>
                            <td>
                                @if($teacher->teacherProfile->cv_path)
                                    <a href="{{ asset('storage/' . $teacher->teacherProfile->cv_path) }}" target="_blank" class="text-sm text-primary-600 hover:underline">Voir CV</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-500">{{ $teacher->created_at->format('M d, Y') }}</td>
                            <td class="text-right">
                                <div class="flex flex-col items-end gap-2">
                                    <form method="POST" action="{{ route('admin.teachers.approve', $teacher) }}" class="inline" onsubmit="return confirm('Approuver cet enseignant ?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-sm btn-success">Approuver</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.teachers.reject', $teacher) }}" class="flex items-center gap-2" onsubmit="return confirm('Refuser cette candidature ?')">
                                        @csrf
                                        <input type="text" name="rejection_reason" placeholder="Motif (optionnel)" class="input text-xs w-32">
                                        <button type="submit" class="btn-sm btn-danger">Refuser</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 py-8">Aucune candidature en attente</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($teachers->hasPages())
        <div class="card-footer border-t px-6 py-4">
            {{ $teachers->links() }}
        </div>
    @endif
</div>
@endsection
