@props(['capacities' => []])

<div class="bg-white rounded-xl border border-grey-200 shadow-sm overflow-hidden">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-grey-200">
        <h3 class="text-lg font-semibold text-grey-900">Capacity Records</h3>
        <p class="text-sm text-grey-500 mt-1">View and manage venue capacity for different dates</p>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-grey-50 border-b border-grey-200">
                <tr>
                    <!-- Sortable Date Column -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'venue_date', 'sort_order' => (request('sort_by') === 'venue_date' && request('sort_order') === 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-grey-700 transition-colors">
                            <span>Date</span>
                            @if(request('sort_by') === 'venue_date')
                                @if(request('sort_order') === 'asc')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-grey-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Full Capacity
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Min Capacity
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Available
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Total Paid
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Total Reserved
                    </th>
                    <!-- Sortable Status Column -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_order' => (request('sort_by') === 'status' && request('sort_order') === 'asc') ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-grey-700 transition-colors">
                            <span>Status</span>
                            @if(request('sort_by') === 'status')
                                @if(request('sort_order') === 'asc')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-grey-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-grey-200">
                @forelse($capacities as $capacity)
                    <tr class="hover:bg-grey-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-grey-900">
                                {{ \Carbon\Carbon::parse($capacity->venue_date)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-grey-500">
                                {{ \Carbon\Carbon::parse($capacity->venue_date)->format('l, h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-grey-900">{{ $capacity->full_capacity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-grey-900">{{ $capacity->min_capacity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $capacity->available_capacity > 0 ? 'text-success-600' : 'text-danger-600' }}">
                                {{ $capacity->available_capacity }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-grey-900">{{ $capacity->total_paid }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-grey-900">{{ $capacity->total_reserved }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($capacity->status == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                    Available
                                </span>
                            @elseif($capacity->status == 2)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                    Almost Full
                                </span>
                            @elseif($capacity->status == 3)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-700">
                                    Sold Out
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Edit Button -->
                                <button
                                    onclick="openEditModal({{ $capacity->id }}, '{{ \Carbon\Carbon::parse($capacity->venue_date)->format('Y-m-d\TH:i') }}', {{ $capacity->full_capacity }}, {{ $capacity->min_capacity }}, {{ $capacity->status }})"
                                    class="text-info-600 hover:text-info-900 transition-colors cursor-pointer"
                                    title="Edit Capacity"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button
                                    onclick="confirmDelete({{ $capacity->id }}, '{{ \Carbon\Carbon::parse($capacity->venue_date)->format('d M Y') }}')"
                                    class="text-danger-600 hover:text-danger-900 transition-colors cursor-pointer"
                                    title="Delete Capacity"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-grey-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-grey-500">No capacity records found</p>
                                <p class="text-xs text-grey-400 mt-1">Click "Add Capacity" to create a new record</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer with Pagination -->
    @if($capacities->total() > 0)
        <div class="px-6 py-4 border-t border-grey-200 bg-grey-50">
            <div class="flex items-center justify-between">
                <!-- Pagination Info -->
                <div class="text-sm text-grey-600">
                    Showing <span class="font-medium">{{ $capacities->firstItem() }}</span> to <span class="font-medium">{{ $capacities->lastItem() }}</span> of <span class="font-medium">{{ $capacities->total() }}</span> results
                </div>

                <!-- Pagination Links -->
                <div>
                    {{ $capacities->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
