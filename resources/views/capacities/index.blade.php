@extends('layouts.app')

@section('title', $venue->name . ' - Capacities')

@section('page-description')
    Manage capacity records for {{ $venue->name }}
@endsection

@section('page-actions')
    <div class="flex gap-2">
        <button
            onclick="openAddModal()"
            class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors"
        >
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Capacity
        </button>
        <button
            onclick="openBulkModal()"
            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
        >
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Add Bulk Capacity
        </button>
    </div>
@endsection

@section('content')
    <!-- Filter Section -->
    <div class="bg-white rounded-xl border border-grey-200 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('capacities.index', $venue) }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-grey-700 mb-1">Date From</label>
                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full px-3 py-2 text-sm border border-grey-300 rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-grey-700 mb-1">Date To</label>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full px-3 py-2 text-sm border border-grey-300 rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-grey-700 mb-1">Status</label>
                    <select
                        name="status"
                        class="w-full px-3 py-2 text-sm border border-grey-300 rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="">All</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Available</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Almost Full</option>
                        <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Apply Filters
                    </button>
                    <a
                        href="{{ route('capacities.index', $venue) }}"
                        class="px-4 py-2 bg-grey-100 text-grey-700 text-sm font-medium rounded-lg hover:bg-grey-200 transition-colors"
                    >
                        Clear
                    </a>
                </div>
            </div>

            <!-- Hidden inputs to preserve sorting -->
            @if(request('sort_by'))
                <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
            @endif
            @if(request('sort_order'))
                <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
            @endif
        </form>
    </div>

    <x-capacities-table :capacities="$capacities" />

    <!-- Add Capacity Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-grey-900 mb-4">Add Capacity for {{ $venue->name }}</h3>

            <form method="POST" action="{{ route('capacities.store', $venue) }}">
                @csrf
                <input type="hidden" name="form_type" value="add_capacity">

                <!-- Venue Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Venue Date <span class="text-danger-500">*</span></label>
                    <input
                        type="date"
                        name="venue_date"
                        value="{{ old('venue_date') }}"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'add_capacity') @error('venue_date') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'add_capacity')
                        @error('venue_date')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Time -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Time <span class="text-danger-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Hour -->
                        <select
                            name="hour"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'add_capacity') @error('hour') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('hour', '12') == $i ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>

                        <!-- Minute -->
                        <select
                            name="minute"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'add_capacity') @error('minute') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            @for($i = 0; $i < 60; $i += 15)
                                <option value="{{ $i }}" {{ old('minute', '0') == $i ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>

                        <!-- AM/PM -->
                        <select
                            name="period"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'add_capacity') @error('period') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="AM" {{ old('period', 'PM') == 'AM' ? 'selected' : '' }}>AM</option>
                            <option value="PM" {{ old('period', 'PM') == 'PM' ? 'selected' : '' }}>PM</option>
                        </select>
                    </div>
                    @if(old('form_type') === 'add_capacity')
                        @error('hour')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('minute')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('period')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Full Capacity -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Full Capacity <span class="text-danger-500">*</span></label>
                    <input
                        type="number"
                        name="full_capacity"
                        value="{{ old('full_capacity') }}"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'add_capacity') @error('full_capacity') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'add_capacity')
                        @error('full_capacity')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Min Capacity -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Min Capacity <span class="text-danger-500">*</span></label>
                    <input
                        type="number"
                        name="min_capacity"
                        value="{{ old('min_capacity') }}"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'add_capacity') @error('min_capacity') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'add_capacity')
                        @error('min_capacity')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Status <span class="text-danger-500">*</span></label>
                    <select
                        name="status"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'add_capacity') @error('status') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Available</option>
                        <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Almost Full</option>
                        <option value="3" {{ old('status') == '3' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                    @if(old('form_type') === 'add_capacity')
                        @error('status')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Add Capacity
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Capacity Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-grey-900 mb-4">Edit Capacity</h3>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="edit_capacity">

                <!-- Venue Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Venue Date <span class="text-danger-500">*</span></label>
                    <input
                        type="date"
                        id="edit_venue_date"
                        name="venue_date"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'edit_capacity') @error('venue_date') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'edit_capacity')
                        @error('venue_date')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Time -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Time <span class="text-danger-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Hour -->
                        <select
                            id="edit_hour"
                            name="hour"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'edit_capacity') @error('hour') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>

                        <!-- Minute -->
                        <select
                            id="edit_minute"
                            name="minute"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'edit_capacity') @error('minute') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            @for($i = 0; $i < 60; $i += 15)
                                <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>

                        <!-- AM/PM -->
                        <select
                            id="edit_period"
                            name="period"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'edit_capacity') @error('period') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    @if(old('form_type') === 'edit_capacity')
                        @error('hour')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('minute')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('period')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Full Capacity -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Full Capacity <span class="text-danger-500">*</span></label>
                    <input
                        type="number"
                        id="edit_full_capacity"
                        name="full_capacity"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'edit_capacity') @error('full_capacity') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'edit_capacity')
                        @error('full_capacity')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Min Capacity -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Min Capacity <span class="text-danger-500">*</span></label>
                    <input
                        type="number"
                        id="edit_min_capacity"
                        name="min_capacity"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'edit_capacity') @error('min_capacity') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'edit_capacity')
                        @error('min_capacity')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Status <span class="text-danger-500">*</span></label>
                    <select
                        id="edit_status"
                        name="status"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'edit_capacity') @error('status') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="1">Available</option>
                        <option value="2">Almost Full</option>
                        <option value="3">Sold Out</option>
                    </select>
                    @if(old('form_type') === 'edit_capacity')
                        @error('status')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Update Capacity
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-grey-900 mb-2">Delete Capacity</h3>
            <p class="text-sm text-grey-600 mb-4">Are you sure you want to delete this capacity record for <span id="delete_date" class="font-medium"></span>?</p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-danger-600 rounded-lg hover:bg-danger-700 transition-colors"
                    >
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Capacity Modal -->
    <div id="bulkModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-grey-900 mb-4">Add Bulk Capacity for {{ $venue->name }}</h3>

            <form method="POST" action="{{ route('capacities.bulk-store', $venue) }}">
                @csrf
                <input type="hidden" name="form_type" value="bulk_capacity">

                <!-- Start Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Start Date <span class="text-danger-500">*</span></label>
                    <input
                        type="date"
                        name="start_date"
                        value="{{ old('start_date') }}"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('start_date') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'bulk_capacity')
                        @error('start_date')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- End Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">End Date <span class="text-danger-500">*</span></label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('end_date') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'bulk_capacity')
                        @error('end_date')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Time for Bulk -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Time <span class="text-danger-500">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Hour -->
                        <select
                            name="hour"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('hour') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('hour', '12') == $i ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>

                        <!-- Minute -->
                        <select
                            name="minute"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('minute') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            @for($i = 0; $i < 60; $i += 15)
                                <option value="{{ $i }}" {{ old('minute', '0') == $i ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>

                        <!-- AM/PM -->
                        <select
                            name="period"
                            class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('period') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                        >
                            <option value="AM" {{ old('period', 'PM') == 'AM' ? 'selected' : '' }}>AM</option>
                            <option value="PM" {{ old('period', 'PM') == 'PM' ? 'selected' : '' }}>PM</option>
                        </select>
                    </div>
                    @if(old('form_type') === 'bulk_capacity')
                        @error('hour')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('minute')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                        @error('period')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Full Capacity -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Full Capacity <span class="text-danger-500">*</span></label>
                    <input
                        type="number"
                        name="full_capacity"
                        value="{{ old('full_capacity') }}"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('full_capacity') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'bulk_capacity')
                        @error('full_capacity')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Min Capacity -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Min Capacity <span class="text-danger-500">*</span></label>
                    <input
                        type="number"
                        name="min_capacity"
                        value="{{ old('min_capacity') }}"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('min_capacity') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @if(old('form_type') === 'bulk_capacity')
                        @error('min_capacity')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-grey-700 mb-1">Status <span class="text-danger-500">*</span></label>
                    <select
                        name="status"
                        class="w-full px-3 py-2 text-sm border @if($errors->any() && old('form_type') === 'bulk_capacity') @error('status') border-danger-500 @else border-grey-300 @enderror @else border-grey-300 @endif rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Available</option>
                        <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Almost Full</option>
                        <option value="3" {{ old('status') == '3' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                    @if(old('form_type') === 'bulk_capacity')
                        @error('status')
                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeBulkModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Add Bulk Capacity
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Add Modal Functions
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    // Edit Modal Functions
    function openEditModal(capacityId, venueDate, fullCapacity, minCapacity, status) {
        // Split datetime into date and time parts (format: Y-m-d\TH:i)
        const [datePart, timePart] = venueDate.split('T');
        const [hour24, minute] = timePart.split(':');

        // Convert 24-hour to 12-hour format
        let hour12 = parseInt(hour24);
        let period = 'AM';

        if (hour12 >= 12) {
            period = 'PM';
            if (hour12 > 12) {
                hour12 = hour12 - 12;
            }
        }

        if (hour12 === 0) {
            hour12 = 12;
        }

        // Populate form fields
        document.getElementById('edit_venue_date').value = datePart;
        document.getElementById('edit_hour').value = hour12;
        document.getElementById('edit_minute').value = minute;
        document.getElementById('edit_period').value = period;
        document.getElementById('edit_full_capacity').value = fullCapacity;
        document.getElementById('edit_min_capacity').value = minCapacity;
        document.getElementById('edit_status').value = status;
        document.getElementById('editForm').action = `/capacities/${capacityId}`;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Delete Modal Functions
    function confirmDelete(capacityId, venueDate) {
        document.getElementById('delete_date').textContent = venueDate;
        document.getElementById('deleteForm').action = `/capacities/${capacityId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Bulk Modal Functions
    function openBulkModal() {
        document.getElementById('bulkModal').classList.remove('hidden');
    }

    function closeBulkModal() {
        document.getElementById('bulkModal').classList.add('hidden');
    }

    // Auto-open modals if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->any())
            @if(old('form_type') === 'add_capacity')
                openAddModal();
            @elseif(old('form_type') === 'edit_capacity')
                // Reopen edit modal with old values
                document.getElementById('edit_venue_date').value = '{{ old('venue_date') }}';
                document.getElementById('edit_hour').value = '{{ old('hour') }}';
                document.getElementById('edit_minute').value = '{{ old('minute') }}';
                document.getElementById('edit_period').value = '{{ old('period') }}';
                document.getElementById('edit_full_capacity').value = '{{ old('full_capacity') }}';
                document.getElementById('edit_min_capacity').value = '{{ old('min_capacity') }}';
                document.getElementById('edit_status').value = '{{ old('status') }}';
                document.getElementById('editModal').classList.remove('hidden');
            @elseif(old('form_type') === 'bulk_capacity')
                openBulkModal();
            @endif
        @endif
    });
</script>
@endpush
