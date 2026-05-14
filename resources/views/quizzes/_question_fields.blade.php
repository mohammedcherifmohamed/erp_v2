@php
    $i = $index ?? 0;
    $q = $q ?? null;
@endphp
<div class="question-item">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-700">Question {{ $i + 1 }}</span>
        <button type="button" onclick="removeQuestion(this)" class="text-sm text-danger-600 hover:text-danger-800">&times; Remove</button>
    </div>
    <div class="space-y-2">
        <textarea name="questions[{{ $i }}][text]" rows="2" class="input" placeholder="Question text" required>{{ old("questions.$i.text", $q['text'] ?? '') }}</textarea>
        <div class="grid grid-cols-3 gap-2">
            <select name="questions[{{ $i }}][type]" class="input">
                <option value="mcq" {{ (old("questions.$i.type", $q['type'] ?? '') === 'mcq') ? 'selected' : '' }}>Multiple Choice</option>
                <option value="true_false" {{ (old("questions.$i.type", $q['type'] ?? '') === 'true_false') ? 'selected' : '' }}>True/False</option>
                <option value="text" {{ (old("questions.$i.type", $q['type'] ?? '') === 'text') ? 'selected' : '' }}>Text Answer</option>
            </select>
            <input type="text" name="questions[{{ $i }}][correct_answer]" value="{{ old("questions.$i.correct_answer", $q['correct_answer'] ?? '') }}" class="input" placeholder="Correct answer">
            <input type="number" name="questions[{{ $i }}][points]" value="{{ old("questions.$i.points", $q['points'] ?? 1) }}" class="input" placeholder="Points" min="0" onchange="updateTotalPoints()">
        </div>
        <div class="options-field" style="{{ (old("questions.$i.type", $q['type'] ?? '') === 'mcq') ? '' : 'display:none' }}">
            <textarea name="questions[{{ $i }}][options]" rows="2" class="input" placeholder="MCQ options (one per line)">{{ old("questions.$i.options", $q['options'] ?? '') }}</textarea>
        </div>
        @error("questions.$i.text") <p class="text-sm text-danger-600">{{ $message }}</p> @enderror
    </div>
</div>
