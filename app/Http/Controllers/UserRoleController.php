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
        $users = User::with(['userRoles.venue'])->get();
        $venues = Venue::all();

        // Check if current user has admin role
        $isAdmin = auth()->user()->userRoles()
            ->where('role', 'Admin')
            ->exists();

        return view('users.index', compact('users', 'venues', 'isAdmin'));
    }

    /**
     * Store a newly created user and assign to venue.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'venue_id' => ['required', 'exists:venues,id'],
            'contact' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create user role assignment
        UserRole::create([
            'user_id' => $user->id,
            'venue_id' => $validated['venue_id'],
            'role' => $validated['role'],
            'contact' => $validated['contact'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
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
     * Remove the specified user role.
     */
    public function destroy(UserRole $userRole)
    {
        $userId = $userRole->user_id;
        $userRole->delete();

        // Check if user has any other venue managements
        $remainingRoles = UserRole::where('user_id', $userId)->count();

        // If no more roles, optionally delete the user
        if ($remainingRoles === 0) {
            User::find($userId)->delete();
            return redirect()->route('users.index')
                ->with('success', 'User and all managements deleted successfully.');
        }

        return redirect()->route('users.index')
            ->with('success', 'Venue assignment removed successfully.');
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

        $userRole->delete();

        return redirect()->route('users.index')
            ->with('success', 'Venue assignment removed successfully.');
    }
}
