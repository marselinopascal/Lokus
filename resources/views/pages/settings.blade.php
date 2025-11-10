
<x-app-layout>
    <div class="p-8">
        {{-- Notifikasi Sukses --}}
        @if (session('status'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>
                    @if (session('status') === 'profile-updated')
                        Informasi profil berhasil diperbarui.
                    @elseif (session('status') === 'password-updated')
                        Password berhasil diperbarui.
                    @else
                        {{ session('status') }}
                    @endif
                </p>
            </div>
        @endif

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Settings</h1>
            <p class="text-gray-600">Configure your LOKUS system preferences and user settings</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6">
                <div class="flex space-x-8 border-b border-gray-200">
                    <button class="settings-tab py-4 px-1 border-b-2 border-gray-600 text-gray-800 font-medium" data-tab="profile">Profile</button>
                    <button class="settings-tab py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-800" data-tab="security">Security</button>
                    <button class="settings-tab py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-800" data-tab="users">Users</button>
                </div>
            </div>
        </div>

        <!-- Profile Tab -->
        <div id="profileTab" class="settings-content">
            {{-- BERIKAN VARIABEL '$user' KE PARTIAL --}}
            @include('partials.settings.update-profile-form', ['user' => $user])
        </div>

        <!-- Security Tab -->
        <div id="securityTab" class="settings-content hidden">
            {{-- Form update password tidak butuh variabel eksternal --}}
            @include('partials.settings.update-password-form')
        </div>
        
        <!-- Users Tab -->
        <div id="usersTab" class="settings-content hidden">
            {{-- BERIKAN VARIABEL '$users' KE PARTIAL --}}
            @include('partials.settings.user-management-table', ['users' => $users])
        </div>
    </div>
</x-app-layout>