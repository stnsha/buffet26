<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-800 transition"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Welcome Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                    Welcome, {{ Auth::user()->name }}!
                </h2>
                <p class="text-gray-600">
                    You are successfully logged in to your dashboard.
                </p>
            </div>

            <!-- User Info Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>

                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Name:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ Auth::user()->name }}</span>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-700">Email:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ Auth::user()->email }}</span>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-gray-700">Member Since:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ Auth::user()->created_at->format('F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
