@extends('layouts.app')

@section('title', 'Users')

@section('page-description')
    Manage user accounts and venue managements
@endsection

@section('page-actions')
    <button
        onclick="openAddModal()"
        class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2 cursor-pointer"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add New User
    </button>
@endsection

@section('content')
    <!-- Users Table -->
    <x-users-table :users="$users" />

    <!-- Add User Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-grey-200">
                <h3 class="text-lg font-semibold text-grey-900">Add New User</h3>
            </div>
            <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-4" onsubmit="return prepareAddUserForm()">
                @csrf
                <input type="hidden" name="form_type" value="add_user">
                <!-- Name -->
                <div>
                    <label for="add_name" class="block text-sm font-medium text-grey-700 mb-1">Name <span class="text-danger-500">*</span></label>
                    <input
                        type="text"
                        id="add_name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-3 py-2 border @error('name') border-danger-500 @else border-grey-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="add_email" class="block text-sm font-medium text-grey-700 mb-1">Email <span class="text-danger-500">*</span></label>
                    <input
                        type="text"
                        id="add_email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-3 py-2 border @error('email') border-danger-500 @else border-grey-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="add_password" class="block text-sm font-medium text-grey-700 mb-1">Password <span class="text-danger-500">*</span></label>
                    <input
                        type="password"
                        id="add_password"
                        name="password"
                        class="w-full px-3 py-2 border @error('password') border-danger-500 @else border-grey-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Venues -->
                <div>
                    <label class="block text-sm font-medium text-grey-700 mb-2">Venues <span class="text-danger-500">*</span></label>
                    <div class="space-y-3 max-h-96 overflow-y-auto border border-grey-200 rounded-lg p-3">
                        @foreach($venues as $venue)
                            <div class="border border-grey-200 rounded-lg p-3">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="venue_ids[]"
                                        value="{{ $venue->id }}"
                                        class="venue-checkbox w-4 h-4 text-primary-600 border-grey-300 rounded focus:ring-primary-500"
                                        onchange="toggleVenueFields(this, 'add')"
                                    >
                                    <span class="ml-2 text-sm font-medium text-grey-900">{{ $venue->name }}</span>
                                </label>

                                <!-- Role and Contact fields for this venue (hidden by default) -->
                                <div id="add_venue_fields_{{ $venue->id }}" class="mt-3 space-y-2 hidden">
                                    <div>
                                        <label class="block text-xs font-medium text-grey-600 mb-1">Contact <span class="text-danger-500">*</span></label>
                                        <input
                                            type="text"
                                            name="contacts[{{ $venue->id }}]"
                                            disabled
                                            class="w-full px-2 py-1.5 text-sm border @error('contact_' . $venue->id) border-danger-500 @else border-grey-300 @enderror rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="Contact number"
                                        >
                                        @error('contact_' . $venue->id)
                                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-grey-600 mb-1">Role <span class="text-danger-500">*</span></label>
                                        <select
                                            name="roles[{{ $venue->id }}]"
                                            disabled
                                            class="w-full px-2 py-1.5 text-sm border @error('role_' . $venue->id) border-danger-500 @else border-grey-300 @enderror rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                                        >
                                            <option value="User" selected>User</option>
                                            <option value="Admin" @if(!$isAdmin) disabled @endif>Admin</option>
                                        </select>
                                        @error('role_' . $venue->id)
                                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-grey-500 mt-1">Select at least one venue and provide contact/role for each</p>
                    @error('venue_ids')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeAddModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors cursor-pointer"
                    >
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-grey-200">
                <h3 class="text-lg font-semibold text-grey-900">Edit User</h3>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <!-- Name -->
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-grey-700 mb-1">Name <span class="text-danger-500">*</span></label>
                    <input
                        type="text"
                        id="edit_name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-3 py-2 border @error('name') border-danger-500 @else border-grey-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="edit_email" class="block text-sm font-medium text-grey-700 mb-1">Email <span class="text-danger-500">*</span></label>
                    <input
                        type="text"
                        id="edit_email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-3 py-2 border @error('email') border-danger-500 @else border-grey-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (optional) -->
                <div>
                    <label for="edit_password" class="block text-sm font-medium text-grey-700 mb-1">Password <span class="text-grey-400 text-xs">(leave blank to keep current)</span></label>
                    <input
                        type="password"
                        id="edit_password"
                        name="password"
                        class="w-full px-3 py-2 border @error('password') border-danger-500 @else border-grey-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors cursor-pointer"
                    >
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Venues Modal -->
    <div id="assignVenueModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-grey-200">
                <h3 class="text-lg font-semibold text-grey-900">Edit User Venues</h3>
                <p id="assignUserName" class="text-sm text-grey-500 mt-1"></p>
            </div>
            <form id="assignVenueForm" method="POST" class="p-6 space-y-4" onsubmit="return prepareAssignVenueForm()">
                @csrf
                <input type="hidden" name="form_type" value="edit_venues">
                <input type="hidden" id="edit_user_id" name="edit_user_id" value="">
                <input type="hidden" id="edit_user_name" name="edit_user_name" value="">
                <!-- Venues -->
                <div>
                    <label class="block text-sm font-medium text-grey-700 mb-2">Venues</label>
                    <div class="space-y-3 max-h-96 overflow-y-auto border border-grey-200 rounded-lg p-3">
                        @foreach($venues as $venue)
                            <div class="border border-grey-200 rounded-lg p-3">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="venue_ids[]"
                                        value="{{ $venue->id }}"
                                        class="venue-checkbox w-4 h-4 text-primary-600 border-grey-300 rounded focus:ring-primary-500"
                                        onchange="toggleVenueFields(this, 'assign')"
                                        data-venue-id="{{ $venue->id }}"
                                    >
                                    <span class="ml-2 text-sm font-medium text-grey-900">{{ $venue->name }}</span>
                                </label>

                                <!-- Role and Contact fields for this venue (hidden by default) -->
                                <div id="assign_venue_fields_{{ $venue->id }}" class="mt-3 space-y-2 hidden">
                                    <div>
                                        <label class="block text-xs font-medium text-grey-600 mb-1">Contact <span class="text-danger-500">*</span></label>
                                        <input
                                            type="text"
                                            name="contacts[{{ $venue->id }}]"
                                            data-contact-input="{{ $venue->id }}"
                                            disabled
                                            class="w-full px-2 py-1.5 text-sm border @error('contact_' . $venue->id) border-danger-500 @else border-grey-300 @enderror rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                                            placeholder="Contact number"
                                        >
                                        @error('contact_' . $venue->id)
                                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-grey-600 mb-1">Role <span class="text-danger-500">*</span></label>
                                        <select
                                            name="roles[{{ $venue->id }}]"
                                            data-role-input="{{ $venue->id }}"
                                            disabled
                                            class="w-full px-2 py-1.5 text-sm border @error('role_' . $venue->id) border-danger-500 @else border-grey-300 @enderror rounded focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
                                        >
                                            <option value="User">User</option>
                                            <option value="Admin" @if(!$isAdmin) disabled @endif>Admin</option>
                                        </select>
                                        @error('role_' . $venue->id)
                                            <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-grey-500 mt-1">Check/uncheck venues to add or remove assignments</p>
                    @error('venue_ids')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeAssignVenueModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors cursor-pointer"
                    >
                        Update Venues
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-grey-200">
                <h3 class="text-lg font-semibold text-grey-900">Confirm Delete</h3>
            </div>
            <div class="p-6">
                <p class="text-grey-700">Are you sure you want to delete user <strong id="deleteUserName"></strong>? This action cannot be undone.</p>
                <form id="deleteForm" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            onclick="closeDeleteModal()"
                            class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-danger-600 rounded-lg hover:bg-danger-700 transition-colors cursor-pointer"
                        >
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Reopen modals if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->any())
            @if(old('form_type') === 'add_user')
                // Reopen Add User modal
                openAddModal();

                // Restore checked venues and their values
                @if(old('venue_ids'))
                    @foreach(old('venue_ids') as $venueId)
                        const checkbox_{{ $venueId }} = document.querySelector('#addModal input[name="venue_ids[]"][value="{{ $venueId }}"]');
                        if (checkbox_{{ $venueId }}) {
                            checkbox_{{ $venueId }}.checked = true;
                            const fieldsDiv = document.getElementById('add_venue_fields_{{ $venueId }}');
                            if (fieldsDiv) {
                                fieldsDiv.classList.remove('hidden');
                                const inputs = fieldsDiv.querySelectorAll('input, select');
                                inputs.forEach(input => {
                                    input.disabled = false;
                                });

                                // Restore contact value
                                const contactInput = fieldsDiv.querySelector('input[name="contacts[{{ $venueId }}]"]');
                                if (contactInput) contactInput.value = '{{ old("contacts.$venueId") }}';

                                // Restore role value
                                const roleSelect = fieldsDiv.querySelector('select[name="roles[{{ $venueId }}]"]');
                                if (roleSelect) roleSelect.value = '{{ old("roles.$venueId") }}';
                            }
                        }
                    @endforeach
                @endif
            @elseif(old('form_type') === 'edit_venues')
                // Reopen Edit User Venues modal
                const editUserId = '{{ old("edit_user_id") }}';
                const editUserName = '{{ old("edit_user_name") }}';

                if (editUserId && editUserName) {
                    // Open the modal
                    document.getElementById('assignUserName').textContent = editUserName;
                    document.getElementById('edit_user_id').value = editUserId;
                    document.getElementById('edit_user_name').value = editUserName;
                    document.getElementById('assignVenueForm').action = `/users/${editUserId}/update-venues`;
                    document.getElementById('assignVenueModal').classList.remove('hidden');

                    // Restore checked venues and their values
                    @if(old('venue_ids'))
                        @foreach(old('venue_ids') as $venueId)
                            const assign_checkbox_{{ $venueId }} = document.querySelector('#assignVenueModal input[name="venue_ids[]"][value="{{ $venueId }}"]');
                            if (assign_checkbox_{{ $venueId }}) {
                                assign_checkbox_{{ $venueId }}.checked = true;
                                const assignFieldsDiv = document.getElementById('assign_venue_fields_{{ $venueId }}');
                                if (assignFieldsDiv) {
                                    assignFieldsDiv.classList.remove('hidden');
                                    const inputs = assignFieldsDiv.querySelectorAll('input, select');
                                    inputs.forEach(input => {
                                        input.disabled = false;
                                    });

                                    // Restore contact value
                                    const contactInput = assignFieldsDiv.querySelector('input[data-contact-input="{{ $venueId }}"]');
                                    if (contactInput) contactInput.value = '{{ old("contacts.$venueId") }}';

                                    // Restore role value
                                    const roleSelect = assignFieldsDiv.querySelector('select[data-role-input="{{ $venueId }}"]');
                                    if (roleSelect) roleSelect.value = '{{ old("roles.$venueId") }}';
                                }
                            }
                        @endforeach
                    @endif
                }
            @endif
        @endif
    });

    // Prepare form before submission to ensure all checked venue fields are enabled
    function prepareAddUserForm() {
        const checkedVenues = document.querySelectorAll('#addModal input[name="venue_ids[]"]:checked');
        checkedVenues.forEach(checkbox => {
            const venueId = checkbox.value;
            const fieldsDiv = document.getElementById(`add_venue_fields_${venueId}`);
            if (fieldsDiv) {
                const inputs = fieldsDiv.querySelectorAll('input, select');
                inputs.forEach(input => input.disabled = false);
            }
        });
        return true; // Allow form submission to proceed
    }

    // Prepare assign venue form before submission
    function prepareAssignVenueForm() {
        const checkedVenues = document.querySelectorAll('#assignVenueModal input[name="venue_ids[]"]:checked');
        checkedVenues.forEach(checkbox => {
            const venueId = checkbox.value;
            const fieldsDiv = document.getElementById(`assign_venue_fields_${venueId}`);
            if (fieldsDiv) {
                const inputs = fieldsDiv.querySelectorAll('input, select');
                inputs.forEach(input => input.disabled = false);
            }
        });
        return true; // Allow form submission to proceed
    }

    // Toggle venue fields visibility when checkbox is checked/unchecked
    function toggleVenueFields(checkbox, modalType) {
        const venueId = checkbox.value;
        const fieldsDiv = document.getElementById(`${modalType}_venue_fields_${venueId}`);

        if (checkbox.checked) {
            fieldsDiv.classList.remove('hidden');
            // Enable fields when checkbox is checked
            const inputs = fieldsDiv.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.disabled = false;
            });
        } else {
            fieldsDiv.classList.add('hidden');
            // Disable fields and clear values when unchecked
            const inputs = fieldsDiv.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.disabled = true;
                if (input.tagName === 'INPUT') {
                    input.value = '';
                }
            });
        }
    }

    // Add User Modal
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        // Reset form and hide all venue fields
        const form = document.querySelector('#addModal form');
        form.reset();
        const allVenueFields = document.querySelectorAll('[id^="add_venue_fields_"]');
        allVenueFields.forEach(field => field.classList.add('hidden'));
    }

    // Edit User Modal
    function openEditModal(userId, userName, userEmail) {
        document.getElementById('edit_name').value = userName;
        document.getElementById('edit_email').value = userEmail;
        document.getElementById('editForm').action = `/users/${userId}`;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Add event listeners for Edit User Venues buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-venues-btn')) {
                const button = e.target.closest('.edit-venues-btn');
                const userId = button.dataset.userId;
                const userName = button.dataset.userName;
                const userVenues = JSON.parse(button.dataset.userVenues || '[]');

                openAssignVenueModal(userId, userName, userVenues);
            }
        });
    });

    // Edit User Venues Modal
    function openAssignVenueModal(userId, userName, userVenues = []) {
        console.log('Opening modal for user:', userId, 'with venues:', userVenues);

        // Set user name, user id and form action
        document.getElementById('assignUserName').textContent = userName;
        document.getElementById('edit_user_id').value = userId;
        document.getElementById('edit_user_name').value = userName;
        document.getElementById('assignVenueForm').action = `/users/${userId}/update-venues`;

        // Reset all checkboxes and hide all fields
        const allCheckboxes = document.querySelectorAll('#assignVenueModal input[type="checkbox"]');
        allCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
            const fieldsDiv = document.getElementById(`assign_venue_fields_${checkbox.value}`);
            if (fieldsDiv) {
                fieldsDiv.classList.add('hidden');
            }
        });

        // Pre-check and populate existing venues
        userVenues.forEach(userVenue => {
            console.log('Processing venue:', userVenue);
            const checkbox = document.querySelector(`#assignVenueModal input[name="venue_ids[]"][value="${userVenue.venue_id}"]`);
            console.log('Found checkbox:', checkbox);

            if (checkbox) {
                // Set checked property
                checkbox.checked = true;

                // Also set the attribute for good measure
                checkbox.setAttribute('checked', 'checked');

                console.log('Checked venue:', userVenue.venue_id, 'Current checked state:', checkbox.checked);

                // Show and populate fields
                const fieldsDiv = document.getElementById(`assign_venue_fields_${userVenue.venue_id}`);
                if (fieldsDiv) {
                    fieldsDiv.classList.remove('hidden');

                    // Populate contact and role
                    const contactInput = fieldsDiv.querySelector(`input[data-contact-input="${userVenue.venue_id}"]`);
                    const roleSelect = fieldsDiv.querySelector(`select[data-role-input="${userVenue.venue_id}"]`);

                    if (contactInput) contactInput.value = userVenue.contact || '';
                    if (roleSelect) roleSelect.value = userVenue.role || 'User';

                    // Enable fields
                    const inputs = fieldsDiv.querySelectorAll('input, select');
                    inputs.forEach(input => input.disabled = false);
                }
            } else {
                console.error('Checkbox not found for venue_id:', userVenue.venue_id);
            }
        });

        document.getElementById('assignVenueModal').classList.remove('hidden');
    }

    function closeAssignVenueModal() {
        document.getElementById('assignVenueModal').classList.add('hidden');
        // Reset form and hide all venue fields
        const form = document.querySelector('#assignVenueForm');
        form.reset();
        const allVenueFields = document.querySelectorAll('[id^="assign_venue_fields_"]');
        allVenueFields.forEach(field => field.classList.add('hidden'));
    }

    // Delete Confirmation Modal
    function confirmDelete(userRoleId, userName) {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteForm').action = `/user-roles/${userRoleId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.id === 'addModal') closeAddModal();
        if (event.target.id === 'editModal') closeEditModal();
        if (event.target.id === 'assignVenueModal') closeAssignVenueModal();
        if (event.target.id === 'deleteModal') closeDeleteModal();
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddModal();
            closeEditModal();
            closeAssignVenueModal();
            closeDeleteModal();
        }
    });
</script>
@endpush
