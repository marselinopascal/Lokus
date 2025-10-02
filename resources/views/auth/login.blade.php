{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">Log In</h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" id="email" name="email" :value="old('email')" required autofocus
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        
        <div class="text-right">
             @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-gray-800">Forgot password?</a>
             @endif
        </div>
        
        <button type="submit" 
                class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
            Log In
        </button>
    </form>
    
    <div class="text-center mt-6">
        <p class="text-sm text-gray-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-gray-800 hover:text-gray-600 font-medium">Sign up</a>
        </p>
    </div>
</x-guest-layout>