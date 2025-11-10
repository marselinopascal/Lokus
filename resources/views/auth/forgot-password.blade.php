
<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Reset Password</h2>
        <p class="text-gray-600 text-sm">Enter your email address and we'll send you a link to reset your password.</p>
    </div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent" placeholder="Enter your email address">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <button type="submit" class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">Send Reset Link</button>
    </form>
    <div class="text-center mt-6">
        <p class="text-sm text-gray-600">
            Remember your password? 
            <a href="{{ route('login') }}" class="text-gray-800 hover:text-gray-600 font-medium">Back to login</a>
        </p>
    </div>
</x-guest-layout>