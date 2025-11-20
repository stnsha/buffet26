@props(['orders' => []])

<div class="bg-white rounded-xl border border-grey-200 shadow-sm overflow-hidden">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-grey-200">
        <h3 class="text-lg font-semibold text-grey-900">Latest Orders</h3>
        <p class="text-sm text-grey-500 mt-1">Recent bookings and reservations</p>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-grey-50 border-b border-grey-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Order ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Customer
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Venue
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Amount
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-grey-500 uppercase tracking-wider">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-grey-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-grey-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-grey-900">#{{ $order['id'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-medium mr-3">
                                    {{ strtoupper(substr($order['customer'], 0, 1)) }}
                                </div>
                                <div class="text-sm text-grey-900">{{ $order['customer'] }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-grey-900">{{ $order['venue'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-grey-900">RM {{ number_format($order['amount'], 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-warning-100 text-warning-700',
                                    'confirmed' => 'bg-info-100 text-info-700',
                                    'completed' => 'bg-success-100 text-success-700',
                                    'cancelled' => 'bg-danger-100 text-danger-700',
                                ];
                                $statusColor = $statusColors[$order['status']] ?? 'bg-grey-100 text-grey-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst($order['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-grey-500">{{ $order['date'] }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-grey-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-sm text-grey-500">No orders found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer -->
    @if(count($orders) > 0)
        <div class="px-6 py-4 border-t border-grey-200 bg-grey-50">
            <div class="flex items-center justify-between">
                <p class="text-sm text-grey-600">
                    Showing <span class="font-medium">{{ count($orders) }}</span> recent orders
                </p>
                <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    View all orders →
                </a>
            </div>
        </div>
    @endif
</div>
