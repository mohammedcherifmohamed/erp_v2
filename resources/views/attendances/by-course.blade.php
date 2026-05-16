@extends('layouts.app')

@section('title', 'Présence pour ' . $course->name)
@section('page-title', 'Présence pour ' . $course->name)
@section('page-subtitle', $course->class->name ?? '')

@section('content')
<div class="max-w-4xl">
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">{{ $date->format('l, F d, Y') }}</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="input w-40">
                <button type="submit" class="btn-secondary">Changer la date</button>
            </form>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.attendances.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-medium text-gray-900">Étudiants ({{ $students->count() }})</p>
                        <div class="flex gap-2">
                            <button type="button" onclick="setAll('present')" class="btn-sm btn-success">Tous présents</button>
                            <button type="button" onclick="setAll('absent')" class="btn-sm btn-danger">Tous absents</button>
                            <button type="button" onclick="setAll('late')" class="btn-sm btn-warning">Tous en retard</button>
                            <button type="button" onclick="setAll('excused')" class="btn-sm btn-outline">Tous excusés</button>
                        </div>
                    </div>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th class="text-center">Présent</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">En retard</th>
                                    <th class="text-center">Excusé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    @php
                                        $existing = $existingAttendances[$student->id] ?? null;
                                        $status = $existing ? $existing->status : 'present';
                                    @endphp
                                    <tr>
                                        <td class="font-medium">{{ $student->full_name }}</td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="present" {{ $status === 'present' ? 'checked' : '' }} class="radio-status">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="absent" {{ $status === 'absent' ? 'checked' : '' }} class="radio-status">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="late" {{ $status === 'late' ? 'checked' : '' }} class="radio-status">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="excused" {{ $status === 'excused' ? 'checked' : '' }} class="radio-status">
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-gray-500 py-4">Aucun étudiant dans cette classe</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @error('attendance') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.attendances.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Enregistrer les présences</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setAll(status) {
        document.querySelectorAll('.radio-status').forEach(function(radio) {
            if (radio.value === status) radio.checked = true;
        });
    }
</script>
@endpush
@endsection
