<?php

namespace App\Http\Controllers;

use App\Models\Capacity;
use App\Models\Venue;
use Illuminate\Http\Request;

class CapacityController extends Controller
{
    /**
     * Display capacities for a specific venue.
     */
    public function index(Request $request, Venue $venue)
    {
        $query = $venue->capacities();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        // If only one date is selected, show exact match for that date
        // If both dates are selected, show date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            // Both dates provided - show range
            $query->whereDate('venue_date', '>=', $request->date_from)
                  ->whereDate('venue_date', '<=', $request->date_to);
        } elseif ($request->filled('date_from')) {
            // Only date_from - show exact match
            $query->whereDate('venue_date', $request->date_from);
        } elseif ($request->filled('date_to')) {
            // Only date_to - show exact match
            $query->whereDate('venue_date', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'venue_date');
        $sortOrder = $request->get('sort_order', 'asc');

        // Only allow sorting by specific columns
        $allowedSortColumns = ['venue_date', 'status'];
        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('venue_date', 'desc');
        }

        // Pagination
        $capacities = $query->paginate(10)->appends($request->except('page'));

        return view('capacities.index', compact('venue', 'capacities'));
    }

    /**
     * Store a newly created capacity for a venue.
     */
    public function store(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'venue_date' => ['required', 'date'],
            'hour' => ['required', 'integer', 'min:1', 'max:12'],
            'minute' => ['required', 'integer', 'min:0', 'max:59'],
            'period' => ['required', 'in:AM,PM'],
            'full_capacity' => ['required', 'integer', 'min:1'],
            'min_capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'integer'],
        ]);

        // Validate that min_capacity doesn't exceed full_capacity
        if ($validated['min_capacity'] > $validated['full_capacity']) {
            return redirect()->back()
                ->withErrors(['min_capacity' => 'Minimum capacity cannot exceed full capacity.'])
                ->withInput();
        }

        // Convert 12-hour format to 24-hour format
        $hour = $validated['hour'];
        if ($validated['period'] === 'PM' && $hour != 12) {
            $hour += 12;
        } elseif ($validated['period'] === 'AM' && $hour == 12) {
            $hour = 0;
        }
        $time = sprintf('%02d:%02d:00', $hour, $validated['minute']);
        $venueDateTime = $validated['venue_date'] . ' ' . $time;

        Capacity::create([
            'venue_id' => $venue->id,
            'venue_date' => $venueDateTime,
            'full_capacity' => $validated['full_capacity'],
            'min_capacity' => $validated['min_capacity'],
            'available_capacity' => $validated['full_capacity'], // Initially equals full capacity
            'total_paid' => 0,
            'total_reserved' => 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('capacities.index', $venue)
            ->with('success', 'Capacity created successfully.');
    }

    /**
     * Store bulk capacities for a date range.
     */
    public function bulkStore(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'hour' => ['required', 'integer', 'min:1', 'max:12'],
            'minute' => ['required', 'integer', 'min:0', 'max:59'],
            'period' => ['required', 'in:AM,PM'],
            'full_capacity' => ['required', 'integer', 'min:1'],
            'min_capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'integer'],
        ]);

        // Validate that min_capacity doesn't exceed full_capacity
        if ($validated['min_capacity'] > $validated['full_capacity']) {
            return redirect()->back()
                ->withErrors(['min_capacity' => 'Minimum capacity cannot exceed full capacity.'])
                ->withInput();
        }

        // Convert 12-hour format to 24-hour format
        $hour = $validated['hour'];
        if ($validated['period'] === 'PM' && $hour != 12) {
            $hour += 12;
        } elseif ($validated['period'] === 'AM' && $hour == 12) {
            $hour = 0;
        }
        $time = sprintf('%02d:%02d:00', $hour, $validated['minute']);

        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $createdCount = 0;
        $skippedCount = 0;

        // Loop through each date in the range
        while ($startDate <= $endDate) {
            $currentDate = $startDate->format('Y-m-d');
            $venueDateTime = $currentDate . ' ' . $time;

            // Check if capacity already exists for this date
            $exists = Capacity::where('venue_id', $venue->id)
                ->whereDate('venue_date', $currentDate)
                ->exists();

            if (!$exists) {
                Capacity::create([
                    'venue_id' => $venue->id,
                    'venue_date' => $venueDateTime,
                    'full_capacity' => $validated['full_capacity'],
                    'min_capacity' => $validated['min_capacity'],
                    'available_capacity' => $validated['full_capacity'],
                    'total_paid' => 0,
                    'total_reserved' => 0,
                    'status' => $validated['status'],
                ]);
                $createdCount++;
            } else {
                $skippedCount++;
            }

            // Move to next day
            $startDate->modify('+1 day');
        }

        $message = $createdCount > 0
            ? "{$createdCount} capacity record(s) created successfully."
            : "No new records created.";

        if ($skippedCount > 0) {
            $message .= " {$skippedCount} date(s) skipped (already exists).";
        }

        return redirect()->route('capacities.index', $venue)
            ->with('success', $message);
    }

    /**
     * Update the specified capacity.
     */
    public function update(Request $request, Capacity $capacity)
    {
        $validated = $request->validate([
            'venue_date' => ['required', 'date'],
            'hour' => ['required', 'integer', 'min:1', 'max:12'],
            'minute' => ['required', 'integer', 'min:0', 'max:59'],
            'period' => ['required', 'in:AM,PM'],
            'full_capacity' => ['required', 'integer', 'min:1'],
            'min_capacity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'integer'],
        ]);

        // Validate that min_capacity doesn't exceed full_capacity
        if ($validated['min_capacity'] > $validated['full_capacity']) {
            return redirect()->back()
                ->withErrors(['min_capacity' => 'Minimum capacity cannot exceed full capacity.'])
                ->withInput();
        }

        // Convert 12-hour format to 24-hour format
        $hour = $validated['hour'];
        if ($validated['period'] === 'PM' && $hour != 12) {
            $hour += 12;
        } elseif ($validated['period'] === 'AM' && $hour == 12) {
            $hour = 0;
        }
        $time = sprintf('%02d:%02d:00', $hour, $validated['minute']);
        $venueDateTime = $validated['venue_date'] . ' ' . $time;

        // Recalculate available capacity based on new full_capacity
        $available = $validated['full_capacity'] - ($capacity->total_paid + $capacity->total_reserved);

        $capacity->update([
            'venue_date' => $venueDateTime,
            'full_capacity' => $validated['full_capacity'],
            'min_capacity' => $validated['min_capacity'],
            'available_capacity' => $available,
            'status' => $validated['status'],
        ]);

        return redirect()->route('capacities.index', $capacity->venue_id)
            ->with('success', 'Capacity updated successfully.');
    }

    /**
     * Soft delete the specified capacity.
     */
    public function destroy(Capacity $capacity)
    {
        $venueId = $capacity->venue_id;
        $capacity->delete();

        return redirect()->route('capacities.index', $venueId)
            ->with('success', 'Capacity deleted successfully.');
    }
}
