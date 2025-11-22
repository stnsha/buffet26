@php
    $venues = \App\Models\Venue::all();
@endphp

<aside
    id="sidebar"
    class="fixed left-0 top-[60px] bottom-0 w-[230px] bg-white border-r border-grey-200 z-[1040] transition-transform duration-300 ease-in-out overflow-hidden flex flex-col -translate-x-full"
>
    <!-- User Info (hidden on mobile, visible on desktop) -->
    <div class="hidden lg:flex justify-between items-center px-4 py-4 border-b border-grey-200">
        <div>
            <h6 class="text-[14px] font-medium text-grey-900 mb-0.5">{{ auth()->user()->name }}</h6>
            <small class="text-[13px] text-grey-500 opacity-70">User</small>
        </div>
        <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-medium ml-2">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>

    <!-- Navigation Menu (Scrollable) -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-thumb-grey-300 scrollbar-track-grey-50">
        <nav class="pb-4">
            <ul class="list-none p-0 m-0">
                <!-- Dashboard -->
                <li>
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 text-grey-900 hover:bg-grey-100 transition-all text-[13px] font-medium border-l-[3px] cursor-pointer {{ request()->routeIs('dashboard') ? 'bg-grey-100 border-l-primary-600' : 'border-l-transparent' }}"
                    >
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Venues -->
                @if($venues->count() > 0)
                    <li>
                        <button
                            type="button"
                            onclick="toggleVenuesDropdown()"
                            class="flex items-center justify-between w-full px-4 py-3 text-grey-900 hover:bg-grey-100 transition-all text-[13px] font-medium border-l-[3px] {{ request()->routeIs('capacities.*') ? 'bg-grey-100 border-l-primary-600' : 'border-l-transparent' }} cursor-pointer"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>Venues</span>
                            </div>
                            <svg
                                id="venuesChevron"
                                class="w-3 h-3 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Venues Dropdown -->
                        <ul id="venuesDropdown" class="hidden bg-grey-50">
                            @foreach($venues as $venue)
                                <li>
                                    <a
                                        href="{{ route('capacities.index', $venue) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 pl-11 text-grey-700 hover:bg-grey-100 transition-all text-[12px] border-l-[3px] {{ request()->routeIs('capacities.index') && request()->route('venue')->id === $venue->id ? 'bg-grey-100 border-l-primary-600' : 'border-l-transparent' }}"
                                    >
                                        <span>{{ $venue->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                <!-- Orders -->
                <li>
                    <a
                        href="#"
                        class="flex items-center gap-3 px-4 py-3 text-grey-900 hover:bg-grey-100 transition-all text-[13px] font-medium border-l-[3px] border-l-transparent cursor-pointer"
                    >
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span>Orders</span>
                    </a>
                </li>

                <!-- Customers -->
                <li>
                    <a
                        href="#"
                        class="flex items-center gap-3 px-4 py-3 text-grey-900 hover:bg-grey-100 transition-all text-[13px] font-medium border-l-[3px] border-l-transparent cursor-pointer"
                    >
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Customers</span>
                    </a>
                </li>

                <!-- Users -->
                <li>
                    <a
                        href="{{ route('users.index') }}"
                        class="flex items-center gap-3 px-4 py-3 text-grey-900 hover:bg-grey-100 transition-all text-[13px] font-medium border-l-[3px] cursor-pointer {{ request()->routeIs('users.*') ? 'bg-grey-100 border-l-primary-600' : 'border-l-transparent' }}"
                    >
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Users</span>
                    </a>
                </li>

                <!-- Settings -->
                <li>
                    <a
                        href="#"
                        class="flex items-center gap-3 px-4 py-3 text-grey-900 hover:bg-grey-100 transition-all text-[13px] font-medium border-l-[3px] border-l-transparent cursor-pointer"
                    >
                        <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- User Profile (visible on mobile/tablet only) -->
    <div class="lg:hidden px-4 py-4 border-t border-grey-200">
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-medium mr-2">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h6 class="text-[14px] font-medium text-grey-900 mb-0.5">{{ auth()->user()->name }}</h6>
                <small class="text-[13px] text-grey-500 opacity-70">User</small>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-3 py-2 text-[13px] font-medium text-primary-600 border border-primary-600 rounded-lg hover:bg-primary-600 hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<script>
    function toggleVenuesDropdown() {
        const dropdown = document.getElementById('venuesDropdown');
        const chevron = document.getElementById('venuesChevron');

        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            dropdown.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    }

    // Auto-expand venues dropdown if on a capacity page
    document.addEventListener('DOMContentLoaded', function() {
        @if(request()->routeIs('capacities.*'))
            const dropdown = document.getElementById('venuesDropdown');
            const chevron = document.getElementById('venuesChevron');
            if (dropdown && chevron) {
                dropdown.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            }
        @endif
    });
</script>
