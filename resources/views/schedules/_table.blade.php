<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Cours</th>
                <th>Classe</th>
                <th>Enseignant</th>
                <th>Salle</th>
                <th>Jour</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($schedules as $schedule)
                <tr>
                    <td class="font-medium">{{ $schedule->course->name ?? '-' }}</td>
                    <td>{{ $schedule->course->classe->name ?? '-' }}</td>
                    <td>{{ $schedule->course->teacher->full_name ?? '-' }}</td>
                    <td>{{ $schedule->classroom ?? '-' }}</td>
                    <td><span class="badge-gray">
    @switch($schedule->day_of_week)
        @case('monday') Lundi @break
        @case('tuesday') Mardi @break
        @case('wednesday') Mercredi @break
        @case('thursday') Jeudi @break
        @case('friday') Vendredi @break
        @case('saturday') Samedi @break
        @case('sunday') Dimanche @break
        @default {{ $schedule->day_of_week }}
    @endswitch
</span></td>
                    <td>{{ $schedule->start_time->format('H:i') }}</td>
                    <td>{{ $schedule->end_time->format('H:i') }}</td>
                    <td>
                        <span class="badge-{{ $schedule->is_active ? 'success' : 'danger' }}">
                            {{ $schedule->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-8">Aucun horaire trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($schedules->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $schedules->appends(['query' => request('query')])->links() }}
    </div>
@endif
