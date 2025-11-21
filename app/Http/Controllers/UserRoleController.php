<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserRoleController extends Controller
{
    /**
     * Display a listing of users with their venue roles.
     */
    public function index()
    {
        // Only show non-deleted users (soft deletes handled automatically)
        $users = User::with(['userRoles.venue'])->get();
        $venues = Venue::all();

        // Check if current user has admin role
        $isAdmin = auth()->user()->userRoles()
            ->where('role', 'Admin')
            ->exists();

        return view('users.index', compact('users', 'venues', 'isAdmin'));
    }

    /**
     * Store a newly created user and assign to venue(s).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'venue_ids' => ['required', 'array', 'min:1'],
            'venue_ids.*' => ['exists:venues,id'],
            'contacts' => ['nullable', 'array'],
            'contacts.*' => ['nullable', 'string', 'max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['nullable', 'string', 'max:255'],
        ]);

        // Validate that each selected venue has contact and role
        $errors = [];
        foreach ($validated['venue_ids'] as $venueId) {
            if (empty($request->input("contacts.{$venueId}"))) {
                $errors["contact_{$venueId}"] = "Contact is required.";
            }
            if (empty($request->input("roles.{$venueId}"))) {
                $errors["role_{$venueId}"] = "Role is required.";
            }
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create user role assignment for each selected venue
        foreach ($validated['venue_ids'] as $venueId) {
            UserRole::create([
                'user_id' => $user->id,
                'venue_id' => $venueId,
                'role' => $request->input("roles.{$venueId}"),
                'contact' => $request->input("contacts.{$venueId}"),
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created and assigned to ' . count($validated['venue_ids']) . ' venue(s) successfully.');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Soft delete the user and force delete all their venue assignments.
     */
    public function destroy(UserRole $userRole)
    {
        $userId = $userRole->user_id;
        $user = User::find($userId);

        // Force delete all user roles for this user
        $allUserRoles = UserRole::where('user_id', $userId)->get();
        foreach ($allUserRoles as $role) {
            $role->forceDelete();
        }

        // Soft delete the user
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User and all venue assignments deleted successfully.');
    }

    /**
     * Assign user to a new venue.
     */
    public function assignVenue(Request $request, User $user)
    {
        $validated = $request->validate([
            'venue_id' => [
                'required',
                'exists:venues,id',
                Rule::unique('user_roles')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'contact' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'venue_id' => $validated['venue_id'],
            'role' => $validated['role'],
            'contact' => $validated['contact'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User assigned to venue successfully.');
    }

    /**
     * Remove a specific venue assignment from user.
     */
    public function removeVenue(Request $request, User $user)
    {
        $validated = $request->validate([
            'user_role_id' => ['required', 'exists:user_roles,id'],
        ]);

        $userRole = UserRole::findOrFail($validated['user_role_id']);

        // Ensure the user role belongs to this user
        if ($userRole->user_id !== $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Invalid venue assignment.');
        }

        // Force delete to permanently remove the record
        $userRole->forceDelete();

        return redirect()->route('users.index')
            ->with('success', 'Venue assignment removed successfully.');
    }

    /**
     * Update user's venue assignments (add, remove, or update).
     */
    public function updateUserVenues(Request $request, User $user)
    {
        $validated = $request->validate([
            'venue_ids' => ['nullable', 'array'],
            'venue_ids.*' => ['exists:venues,id'],
            'contacts' => ['nullable', 'array'],
            'contacts.*' => ['nullable', 'string', 'max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['nullable', 'string', 'max:255'],
        ]);

        // Validate that each selected venue has contact and role
        $errors = [];
        if (!empty($validated['venue_ids'])) {
            foreach ($validated['venue_ids'] as $venueId) {
                if (empty($request->input("contacts.{$venueId}"))) {
                    $errors["contact_{$venueId}"] = "Contact is required.";
                }
                if (empty($request->input("roles.{$venueId}"))) {
                    $errors["role_{$venueId}"] = "Role is required.";
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        \DB::transaction(function () use ($validated, $user, $request) {
            $newVenueIds = $validated['venue_ids'] ?? [];

            // Get current venue IDs for this user
            $currentVenueIds = $user->userRoles()->pluck('venue_id')->toArray();

            // Determine which venues to remove
            $venuesToRemove = array_diff($currentVenueIds, $newVenueIds);

            // Determine which venues to add
            $venuesToAdd = array_diff($newVenueIds, $currentVenueIds);

            // Determine which venues to update (existing venues that are still selected)
            $venuesToUpdate = array_intersect($currentVenueIds, $newVenueIds);

            // Remove unchecked venues (force delete to avoid foreign key issues)
            if (!empty($venuesToRemove)) {
                $rolesToDelete = UserRole::where('user_id', $user->id)
                    ->whereIn('venue_id', $venuesToRemove)
                    ->get();

                foreach ($rolesToDelete as $role) {
                    $role->forceDelete();
                }
            }

            // Add new venues
            foreach ($venuesToAdd as $venueId) {
                UserRole::create([
                    'user_id' => $user->id,
                    'venue_id' => $venueId,
                    'role' => $request->input("roles.{$venueId}"),
                    'contact' => $request->input("contacts.{$venueId}"),
                ]);
            }

            // Update existing venues (role and contact might have changed)
            foreach ($venuesToUpdate as $venueId) {
                UserRole::where('user_id', $user->id)
                    ->where('venue_id', $venueId)
                    ->update([
                        'role' => $request->input("roles.{$venueId}"),
                        'contact' => $request->input("contacts.{$venueId}"),
                    ]);
            }
        });

        // Refresh the user's relationships to get the latest data
        $user->refresh();

        // Check if user has any remaining venues
        if ($user->userRoles()->count() === 0) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User deleted as all venue assignments were removed.');
        }

        return redirect()->route('users.index', ['refresh' => time()])
            ->with('success', 'User venues updated successfully.');
    }
}
