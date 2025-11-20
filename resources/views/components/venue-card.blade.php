@props(['name', 'totalCapacity', 'availableCapacity', 'totalSales'])

<div class="bg-white rounded-xl border border-grey-200 shadow-sm hover:shadow-md transition-shadow">
    <!-- Card Header -->
    <div class="p-6 border-b border-grey-100">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-grey-900">{{ $name }}</h3>
            <span class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </span>
        </div>
    </div>

    <!-- Card Body -->
    <div class="p-6 space-y-4">
        <!-- Total Capacity -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-info-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-grey-500">Total Capacity</p>
                    <p class="text-2xl font-bold text-grey-900">{{ number_format($totalCapacity) }}</p>
                </div>
            </div>
        </div>

        <!-- Available Capacity -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-success-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-grey-500">Available Capacity</p>
                    <p class="text-2xl font-bold text-success-600">{{ number_format($availableCapacity) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="flex items-center justify-between pt-4 border-t border-grey-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-warning-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-grey-500">Total Sales</p>
                    <p class="text-2xl font-bold text-grey-900">RM {{ number_format($totalSales, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Utilization Bar -->
        @php
            $utilizationPercent = $totalCapacity > 0 ? (($totalCapacity - $availableCapacity) / $totalCapacity) * 100 : 0;
        @endphp
        <div class="pt-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-grey-600">Utilization</span>
                <span class="text-xs font-semibold text-grey-900">{{ number_format($utilizationPercent, 1) }}%</span>
            </div>
            <div class="w-full bg-grey-200 rounded-full h-2">
                <div
                    class="h-2 rounded-full transition-all {{ $utilizationPercent > 80 ? 'bg-danger-500' : ($utilizationPercent > 50 ? 'bg-warning-500' : 'bg-success-500') }}"
                    style="width: {{ $utilizationPercent }}%"
                ></div>
            </div>
        </div>
    </div>
</div>
