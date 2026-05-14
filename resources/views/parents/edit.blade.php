@extends('layouts.app')

@section('title', 'Edit ' . $parent->full_name)
@section('page-title', 'Edit Parent')
@section('page-subtitle', $parent->full_name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.parents.update', $parent) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="label">First Name <span class="text-danger-500">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $parent->first_name) }}" required class="input @error('first_name') input-error @enderror">
                        @error('first_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="label">Last Name <span class="text-danger-500">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $parent->last_name) }}" required class="input @error('last_name') input-error @enderror">
                        @error('last_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="label">Email <span class="text-danger-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email', $parent->email) }}" required class="input @error('email') input-error @enderror">
                        @error('email') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="label">Phone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $parent->phone) }}" class="input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="secondary_phone" class="label">Secondary Phone</label>
                        <input id="secondary_phone" type="text" name="secondary_phone" value="{{ old('secondary_phone', $parent->secondary_phone) }}" class="input">
                    </div>
                    <div>
                        <label for="arabic_name" class="label">Arabic Name</label>
                        <input id="arabic_name" type="text" name="arabic_name" value="{{ old('arabic_name', $parent->arabic_name) }}" class="input" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="profession" class="label">Profession</label>
                        <input id="profession" type="text" name="profession" value="{{ old('profession', $parent->profession) }}" class="input">
                    </div>
                    <div>
                        <label for="relationship" class="label">Relationship</label>
                        <select id="relationship" name="relationship" class="input">
                            <option value="">Select</option>
                            <option value="father" {{ old('relationship', $parent->relationship) === 'father' ? 'selected' : '' }}>Father</option>
                            <option value="mother" {{ old('relationship', $parent->relationship) === 'mother' ? 'selected' : '' }}>Mother</option>
                            <option value="guardian" {{ old('relationship', $parent->relationship) === 'guardian' ? 'selected' : '' }}>Guardian</option>
                            <option value="other" {{ old('relationship', $parent->relationship) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.parents.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Update Parent</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
