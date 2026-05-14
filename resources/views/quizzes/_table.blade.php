<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Cours</th>
                <th>Classe</th>
                <th>Questions</th>
                <th>Points</th>
                <th>Statut</th>
                <th>Créé le</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($quizzes as $quiz)
                <tr>
                    <td class="font-medium">{{ $quiz->title }}</td>
                    <td>{{ $quiz->course->name ?? '-' }}</td>
                    <td>{{ $quiz->course->classe->name ?? '-' }}</td>
                    <td>{{ $quiz->questions_count }}</td>
                    <td>{{ $quiz->total_points }}</td>
                    <td>
                        <span class="badge-{{ $quiz->is_published ? 'success' : 'warning' }}">
                            {{ $quiz->is_published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </td>
                    <td>{{ $quiz->created_at->format('M d, Y') }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn-sm btn-secondary">Modifier</a>
                            <a href="{{ route('teacher.quizzes.correct', $quiz) }}" class="btn-sm btn-success">Corriger</a>
                            <form method="POST" action="{{ route('teacher.quizzes.destroy', $quiz) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-8">Aucun quiz trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($quizzes->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $quizzes->appends(['query' => request('query')])->links() }}
    </div>
@endif
