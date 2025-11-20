@props(['users' => []])

<div class="bg-white rounded-xl border border-grey-200 shadow-sm overflow-hidden">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-grey-200">
        <h3 class="text-lg font-semibold text-grey-900">Users</h3>
        <p class="text-sm text-grey-500 mt-1">Manage user accounts and venue managements</p>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-grey-50 border-b border-grey-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Venues & Roles
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-grey-200">
                @forelse($users as $user)
                    <tr class="hover:bg-grey-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-sm font-medium mr-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-grey-900">{{ $user->name }}</div>
                                    <div class="text-xs text-grey-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @forelse($user->userRoles as $userRole)
                                    @php
                                        // Define color palette for venues
                                        $venueColors = [
                                            'bg-primary-100 text-primary-700',
                                            'bg-success-100 text-success-700',
                                            'bg-warning-100 text-warning-700',
                                            'bg-info-100 text-info-700',
                                            'bg-danger-100 text-danger-700',
                                        ];
                                        // Assign color based on venue ID to ensure consistency
                                        $colorIndex = ($userRole->venue_id - 1) % count($venueColors);
                                        $venueColor = $venueColors[$colorIndex];
                                    @endphp
                                    <div class="inline-flex flex-col gap-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $venueColor }}">
                                            {{ $userRole->venue?->name ?? 'Unknown' }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-grey-100 text-grey-700">
                                            {{ $userRole->role }}
                                        </span>
                                    </div>
                                @empty
                                    <span class="text-xs text-grey-400">No venues assigned</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-grey-500">
                                @if($user->userRoles->count() > 0)
                                    {{ $user->userRoles->first()->contact }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Edit Button -->
                                <button
                                    onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                    class="text-info-600 hover:text-info-900 transition-colors"
                                    title="Edit User"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>

                                <!-- Assign to Venue Button -->
                                <button
                                    onclick="openAssignVenueModal({{ $user->id }}, '{{ $user->name }}')"
                                    class="text-success-600 hover:text-success-900 transition-colors"
                                    title="Assign to Venue"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                @if($user->userRoles->count() > 0)
                                    <button
                                        onclick="confirmDelete({{ $user->userRoles->first()->id }}, '{{ $user->name }}')"
                                        class="text-danger-600 hover:text-danger-900 transition-colors"
                                        title="Delete User"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-grey-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-sm text-grey-500">No users found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer -->
    @if(count($users) > 0)
        <div class="px-6 py-4 border-t border-grey-200 bg-grey-50">
            <div class="flex items-center justify-between">
                <p class="text-sm text-grey-600">
                    Total <span class="font-medium">{{ count($users) }}</span> user{{ count($users) === 1 ? '' : 's' }}
                </p>
            </div>
        </div>
    @endif
</div>
