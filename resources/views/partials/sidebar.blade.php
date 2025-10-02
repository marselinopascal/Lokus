{{-- resources/views/partials/sidebar.blade.php --}}
<div class="w-64 bg-gray-800 text-white flex flex-col">
    <div class="p-6 border-b border-gray-700">
        <h1 class="text-2xl font-bold tracking-wide">LOKUS</h1>
        <p class="text-gray-400 text-sm mt-1">System Dashboard</p>
    </div>
    
    <nav class="flex-1 py-6">
        <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center px-6 py-3 text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="mr-3">📊</span>
            Dashboard
        </a>
        {{-- Link Logbook (DIPERBARUI) --}}
        <a href="{{ route('logbook.index') }}" class="sidebar-item flex items-center px-6 py-3 text-white {{ request()->routeIs('logbook.*') ? 'active' : '' }}">
            <span class="mr-3">📝</span>
            Logbook
        </a>
        
        {{-- Link Stock Opname (DIPERBARUI) --}}
        <a href="{{ route('stock.index') }}" class="sidebar-item flex items-center px-6 py-3 text-white {{ request()->routeIs('stock.*') ? 'active' : '' }}">
            <span class="mr-3">📦</span>
            Stock Opname
        </a>
        
        {{-- Link Reports (DIPERBARUI) --}}
        <a href="{{ route('reports.index') }}" class="sidebar-item flex items-center px-6 py-3 text-white {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="mr-3">📈</span>
            Reports
        </a>
        
        <a href="{{ route('settings') }}" class="sidebar-item flex items-center px-6 py-3 text-white {{ request()->routeIs('settings') ? 'active' : '' }}">
            <span class="mr-3">⚙️</span>
            Settings
        </a>
    </nav>
    
    <div class="p-6 border-t border-gray-700">
        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="sidebar-item flex items-center px-3 py-2 text-white rounded">
                <span class="mr-3">🚪</span>
                User Logout
            </a>
        </form>
    </div>
</div>