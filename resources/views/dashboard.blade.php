@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-description')
    Welcome back, {{ Auth::user()->name }}! Here's what's happening today.
@endsection

@section('content')
    <!-- Bento Grid Layout -->
    <div class="space-y-6">
        <!-- First Row: Venue Cards (Bento Style) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Venue 1 Card -->
            <x-venue-card
                name="Venue 1"
                :totalCapacity="500"
                :availableCapacity="125"
                :totalSales="18750.00"
            />

            <!-- Venue 2 Card -->
            <x-venue-card
                name="Venue 2"
                :totalCapacity="300"
                :availableCapacity="80"
                :totalSales="11200.00"
            />
        </div>

        <!-- Second Row: Latest Orders Table -->
        <x-orders-table :orders="[
            [
                'id' => 'ORD-2024-001',
                'customer' => 'Ahmad Ibrahim',
                'venue' => 'Venue 1',
                'amount' => 450.00,
                'status' => 'confirmed',
                'date' => '2024-11-20',
            ],
            [
                'id' => 'ORD-2024-002',
                'customer' => 'Siti Nurhaliza',
                'venue' => 'Venue 2',
                'amount' => 320.00,
                'status' => 'pending',
                'date' => '2024-11-20',
            ],
            [
                'id' => 'ORD-2024-003',
                'customer' => 'Lee Wei Ming',
                'venue' => 'Venue 1',
                'amount' => 680.00,
                'status' => 'completed',
                'date' => '2024-11-19',
            ],
            [
                'id' => 'ORD-2024-004',
                'customer' => 'Priya Sharma',
                'venue' => 'Venue 2',
                'amount' => 275.00,
                'status' => 'confirmed',
                'date' => '2024-11-19',
            ],
            [
                'id' => 'ORD-2024-005',
                'customer' => 'Muhammad Ali',
                'venue' => 'Venue 1',
                'amount' => 550.00,
                'status' => 'completed',
                'date' => '2024-11-18',
            ],
            [
                'id' => 'ORD-2024-006',
                'customer' => 'Tan Mei Ling',
                'venue' => 'Venue 2',
                'amount' => 390.00,
                'status' => 'cancelled',
                'date' => '2024-11-18',
            ],
            [
                'id' => 'ORD-2024-007',
                'customer' => 'Kumar Rajesh',
                'venue' => 'Venue 1',
                'amount' => 725.00,
                'status' => 'confirmed',
                'date' => '2024-11-17',
            ],
            [
                'id' => 'ORD-2024-008',
                'customer' => 'Nurul Aina',
                'venue' => 'Venue 2',
                'amount' => 480.00,
                'status' => 'completed',
                'date' => '2024-11-17',
            ],
        ]" />
    </div>
@endsection
