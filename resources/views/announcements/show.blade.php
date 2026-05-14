@extends('layouts.app')

@section('title', $announcement->title)
@section('page-title', $announcement->title)
@section('page-subtitle', 'Announcement')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body space-y-4">
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span>Posted {{ $announcement->created_at->format('M d, Y H:i') }}</span>
                <span>&middot;</span>
                <span>
                    @if($announcement->is_global)
                        <span class="badge-primary">Global</span>
                    @else
                        Class: {{ $announcement->class->name ?? '-' }}
                    @endif
                </span>
                <span>&middot;</span>
                <span class="badge-{{ $announcement->is_published ? 'success' : 'warning' }}">
                    {{ $announcement->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>
            <div class="prose prose-sm max-w-none text-gray-700">
                {!! nl2br(e($announcement->content)) !!}
            </div>
            @if($announcement->author)
                <div class="border-t pt-4 text-sm text-gray-500">
                    Posted by {{ $announcement->author->full_name }}
                </div>
            @endif
        </div>
    </div>
    <div class="flex gap-3 mt-6">
        <a href="{{ route('teacher.announcements.edit', $announcement) }}" class="btn-primary">Edit</a>
        <a href="{{ route('teacher.announcements.index') }}" class="btn-outline">Back to Announcements</a>
    </div>
</div>
@endsection
