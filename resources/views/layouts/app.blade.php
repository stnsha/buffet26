<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Meta Tags -->
    <meta name="description" content="@yield('meta-description', config('app.name'))">
    <meta name="keywords" content="@yield('meta-keywords', '')">
    @stack('meta')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Page-Specific Styles -->
    @stack('styles')
</head>
<body class="bg-grey-50 font-sans antialiased text-[13px] @yield('body-class', '')">
    <!-- Navbar -->
    @if(!isset($hideNavbar) || !$hideNavbar)
        <x-navbar />
    @endif

    <!-- Sidebar Backdrop (for mobile) -->
    <div
        id="sidebar-backdrop"
        class="fixed inset-0 bg-grey-900/50 z-[1039] hidden opacity-0 transition-opacity duration-300"
    ></div>

    <!-- Sidebar -->
    @if(!isset($hideSidebar) || !$hideSidebar)
        <x-sidebar />
    @endif

    <!-- Main Content -->
    <main class="pt-[60px] @if(!isset($hideSidebar) || !$hideSidebar) transition-[margin-left] duration-300 ease-in-out @endif min-h-screen flex flex-col" id="mainContent">
        <div class="flex-1">
            <div class="px-4 py-4 @yield('content-wrapper-class', '')">
                <!-- Flash Messages -->
                @if(session('success') || session('error') || session('warning') || session('info'))
                    <div class="mb-4 space-y-3">
                        @if(session('success'))
                            <div class="bg-success-50 border border-success-200 text-success-800 px-4 py-3 rounded-lg flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="bg-danger-50 border border-danger-200 text-danger-800 px-4 py-3 rounded-lg flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif
                        @if(session('warning'))
                            <div class="bg-warning-50 border border-warning-200 text-warning-800 px-4 py-3 rounded-lg flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>{{ session('warning') }}</span>
                            </div>
                        @endif
                        @if(session('info'))
                            <div class="bg-info-50 border border-info-200 text-info-800 px-4 py-3 rounded-lg flex items-start gap-3">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('info') }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Custom Alerts Section -->
                @hasSection('alerts')
                    <div class="mb-4">
                        @yield('alerts')
                    </div>
                @endif

                <!-- Page Header -->
                @hasSection('page-header')
                    {{-- Custom page header --}}
                    @yield('page-header')
                @else
                    {{-- Auto-generated page header if title or description provided --}}
                    @if(trim($__env->yieldContent('page-title')) !== '' || trim($__env->yieldContent('title')) !== '' || trim($__env->yieldContent('page-description')) !== '' || trim($__env->yieldContent('page-actions')) !== '' || trim($__env->yieldContent('breadcrumbs')) !== '')
                        <div class="mb-6">
                            {{-- Breadcrumbs --}}
                            @hasSection('breadcrumbs')
                                <nav class="mb-3 text-sm">
                                    @yield('breadcrumbs')
                                </nav>
                            @endif

                            {{-- Title and Actions Row --}}
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    {{-- Page Title --}}
                                    @if(trim($__env->yieldContent('page-title')) !== '')
                                        <h1 class="text-3xl font-bold text-grey-900">
                                            @yield('page-title')
                                        </h1>
                                    @elseif(trim($__env->yieldContent('title')) !== '')
                                        <h1 class="text-3xl font-bold text-grey-900">
                                            @yield('title')
                                        </h1>
                                    @endif

                                    {{-- Page Description --}}
                                    @hasSection('page-description')
                                        <p class="text-grey-600 mt-2">
                                            @yield('page-description')
                                        </p>
                                    @endif
                                </div>

                                {{-- Page Actions --}}
                                @hasSection('page-actions')
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @yield('page-actions')
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Page-Specific Scripts -->
    @stack('scripts')
</body>
</html>
