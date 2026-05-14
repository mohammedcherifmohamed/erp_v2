@extends('layouts.app')

@section('title', $child->full_name . '\'s Schedule')
@section('page-title', $child->full_name . '\'s Schedule')
@section('page-subtitle', 'Weekly class schedule')

@section('content')
<div class="space-y-4">
    @php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $colors = ['bg-blue-50 border-blue-200', 'bg-green-50 border-green-200', 'bg-yellow-50 border-yellow-200', 'bg-purple-50 border-purple-200', 'bg-pink-50 border-pink-200', 'bg-indigo-50 border-indigo-200', 'bg-gray-50 border-gray-200'];
    @endphp

    <div class="grid grid-cols-7 gap-2">
        @foreach($days as $index => $day)
            <div class="border rounded-lg {{ $colors[$index] }} min-h-[300px]">
                <div class="p-2 border-b border-inherit bg-white/50 rounded-t-lg">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase">{{ $day }}</h3>
                </div>
                <div class="p-1 space-y-1">
                    @forelse(($schedulesByDay[$day] ?? collect()) as $schedule)
                        <div class="bg-white rounded p-2 shadow-sm border text-xs space-y-1 cursor-default hover:shadow-md transition-shadow">
                            <p class="font-medium text-gray-900 truncate">{{ $schedule->course->name }}</p>
                            <p class="text-gray-500 truncate">{{ $schedule->course->teacher->full_name ?? '-' }}</p>
                            <p class="text-primary-600 font-medium">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</p>
                            @if($schedule->classroom)
                                <p class="text-gray-400">Room: {{ $schedule->classroom }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-4">No classes</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
