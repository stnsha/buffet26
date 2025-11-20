@extends('layouts.app')

@section('title', 'Users')

@section('page-description')
    Manage user accounts and venue managements
@endsection

@section('page-actions')
    <button
        onclick="openAddModal()"
        class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2"
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
            <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-4">
                @csrf
                <!-- Name -->
                <div>
                    <label for="add_name" class="block text-sm font-medium text-grey-700 mb-1">Name</label>
                    <input
                        type="text"
                        id="add_name"
                        name="name"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="add_email" class="block text-sm font-medium text-grey-700 mb-1">Email</label>
                    <input
                        type="email"
                        id="add_email"
                        name="email"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="add_password" class="block text-sm font-medium text-grey-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="add_password"
                        name="password"
                        required
                        minlength="8"
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Venue -->
                <div>
                    <label for="add_venue_id" class="block text-sm font-medium text-grey-700 mb-1">Venue</label>
                    <select
                        id="add_venue_id"
                        name="venue_id"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="">Select venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Contact -->
                <div>
                    <label for="add_contact" class="block text-sm font-medium text-grey-700 mb-1">Contact</label>
                    <input
                        type="text"
                        id="add_contact"
                        name="contact"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Role -->
                <div>
                    <label for="add_role" class="block text-sm font-medium text-grey-700 mb-1">Role</label>
                    <select
                        id="add_role"
                        name="role"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="User" selected>User</option>
                        @if($isAdmin)
                            <option value="Admin">Admin</option>
                        @endif
                    </select>
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
                    <label for="edit_name" class="block text-sm font-medium text-grey-700 mb-1">Name</label>
                    <input
                        type="text"
                        id="edit_name"
                        name="name"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="edit_email" class="block text-sm font-medium text-grey-700 mb-1">Email</label>
                    <input
                        type="email"
                        id="edit_email"
                        name="email"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Password (optional) -->
                <div>
                    <label for="edit_password" class="block text-sm font-medium text-grey-700 mb-1">Password <span class="text-grey-400 text-xs">(leave blank to keep current)</span></label>
                    <input
                        type="password"
                        id="edit_password"
                        name="password"
                        minlength="8"
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
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
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assign to Venue Modal -->
    <div id="assignVenueModal" class="hidden fixed inset-0 bg-grey-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-grey-200">
                <h3 class="text-lg font-semibold text-grey-900">Assign to Venue</h3>
                <p id="assignUserName" class="text-sm text-grey-500 mt-1"></p>
            </div>
            <form id="assignVenueForm" method="POST" class="p-6 space-y-4">
                @csrf
                <!-- Venue -->
                <div>
                    <label for="assign_venue_id" class="block text-sm font-medium text-grey-700 mb-1">Venue</label>
                    <select
                        id="assign_venue_id"
                        name="venue_id"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="">Select venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Contact -->
                <div>
                    <label for="assign_contact" class="block text-sm font-medium text-grey-700 mb-1">Contact</label>
                    <input
                        type="text"
                        id="assign_contact"
                        name="contact"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </div>

                <!-- Role -->
                <div>
                    <label for="assign_role" class="block text-sm font-medium text-grey-700 mb-1">Role</label>
                    <select
                        id="assign_role"
                        name="role"
                        required
                        class="w-full px-3 py-2 border border-grey-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="User" selected>User</option>
                        @if($isAdmin)
                            <option value="Admin">Admin</option>
                        @endif
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeAssignVenueModal()"
                        class="px-4 py-2 text-sm font-medium text-grey-700 bg-grey-100 rounded-lg hover:bg-grey-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Assign
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
    </div>
@endsection

@push('scripts')
<script>
    // Add User Modal
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
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

    // Assign to Venue Modal
    function openAssignVenueModal(userId, userName) {
        document.getElementById('assignUserName').textContent = userName;
        document.getElementById('assignVenueForm').action = `/users/${userId}/assign-venue`;
        document.getElementById('assignVenueModal').classList.remove('hidden');
    }

    function closeAssignVenueModal() {
        document.getElementById('assignVenueModal').classList.add('hidden');
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
