// File: resources/views/partials/settings/update-profile-form.blade.php

<div class="bg-white rounded-lg shadow-sm">
    <form method="post" action="{{ route('profile.update') }}" class="p-6">
        @csrf
        @method('patch')

        <header>
            <h2 class="text-xl font-semibold text-gray-800">Profile Information</h2>
            <p class="mt-1 text-sm text-gray-600">Update your account's profile information.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="space-y-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required autofocus class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>
                 <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                     <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" value="{{ $user->department }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" value="{{ $user->username }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">Update Profile</button>
        </div>
    </form>
</div>