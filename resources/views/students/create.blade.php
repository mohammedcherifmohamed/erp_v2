@extends('layouts.app')

@section('title', 'Create Student')
@section('page-title', 'Create Student')
@section('page-subtitle', 'Register a new student')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="label">First Name <span class="text-danger-500">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required class="input @error('first_name') input-error @enderror">
                        @error('first_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="label">Last Name <span class="text-danger-500">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="input @error('last_name') input-error @enderror">
                        @error('last_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="label">Email <span class="text-danger-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="input @error('email') input-error @enderror">
                        @error('email') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="label">Password <span class="text-danger-500">*</span></label>
                        <input id="password" type="password" name="password" required class="input @error('password') input-error @enderror">
                        @error('password') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="label">Phone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="input">
                    </div>
                    <div>
                        <label for="arabic_name" class="label">Arabic Name</label>
                        <input id="arabic_name" type="text" name="arabic_name" value="{{ old('arabic_name') }}" class="input" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date_of_birth" class="label">Date of Birth</label>
                        <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="input">
                    </div>
                    <div>
                        <label for="gender" class="label">Gender</label>
                        <select id="gender" name="gender" class="input">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="address" class="label">Address</label>
                    <textarea id="address" name="address" rows="2" class="input">{{ old('address') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="emergency_contact" class="label">Emergency Contact</label>
                        <input id="emergency_contact" type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" class="input">
                    </div>
                    <div>
                        <label for="blood_type" class="label">Blood Type</label>
                        <select id="blood_type" name="blood_type" class="input">
                            <option value="">Select</option>
                            <option value="A+" {{ old('blood_type') === 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type') === 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type') === 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type') === 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type') === 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type') === 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type') === 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type') === 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="allergies" class="label">Allergies / Medical Notes</label>
                    <textarea id="allergies" name="allergies" rows="2" class="input">{{ old('allergies') }}</textarea>
                </div>
                <div>
                    <label for="parent_id" class="label">Parent / Guardian</label>
                    <select id="parent_id" name="parent_id" class="input @error('parent_id') input-error @enderror">
                        <option value="">Select Parent</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->full_name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                    @error('parent_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.students.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Create Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
