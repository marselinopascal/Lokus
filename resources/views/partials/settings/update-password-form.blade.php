// File: resources/views/partials/settings/update-password-form.blade.php

<div class="bg-white rounded-lg shadow-sm">
    <form method="post" action="{{ route('password.update') }}" class="p-6">
        @csrf
        @method('put')

        <header>
            <h2 class="text-xl font-semibold text-gray-800">Update Password</h2>
            <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
        </header>

        <div class="mt-6 space-y-4 max-w-xl">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" id="current_password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">Save Password</button>
        </div>
    </form>
</div>