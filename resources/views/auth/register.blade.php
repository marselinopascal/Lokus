<x-guest-layout>
    <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Create Account</h2>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required autofocus class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>
        <div>
            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
            <select id="department" name="department" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                <option value="">Select Department</option>
                <option value="warehouse" @if(old('department') == 'warehouse') selected @endif>Warehouse Operations</option>
                <option value="it" @if(old('department') == 'it') selected @endif>IT Department</option>
                <option value="admin" @if(old('department') == 'admin') selected @endif>Administration</option>
                <option value="management" @if(old('department') == 'management') selected @endif>Management</option>
            </select>
            <x-input-error :messages="$errors->get('department')" class="mt-2" />
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <button type="submit" class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">Create Account</button>
    </form>
    <div class="text-center mt-6">
        <p class="text-sm text-gray-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-gray-800 hover:text-gray-600 font-medium">Log in</a>
        </p>
    </div>
</x-guest-layout>