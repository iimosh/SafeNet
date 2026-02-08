<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-2">Digital Risk Report</h1>
        <div class="mb-4">
            <div><strong>Total points:</strong> {{ $assessment->total_points }}</div>
            <div><strong>Risk level:</strong>
                <span class="px-2 py-1 rounded
                    @if($assessment->risk_level==='low') bg-green-100 text-green-700
                    @elseif($assessment->risk_level==='medium') bg-yellow-100 text-yellow-700
                    @else bg-red-100 text-red-700 @endif
                ">
                    {{ strtoupper($assessment->risk_level) }}
                </span>
            </div>
        </div>

        <h2 class="text-xl font-semibold mt-6 mb-2">Category breakdown</h2>
        <ul class="list-disc pl-5">
            @foreach($breakdown as $cat => $pts)
                <li>{{ $cat }}: {{ $pts }}</li>
            @endforeach
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-2">Recommendations (basic)</h2>
        <div class="p-4 border rounded bg-gray-50">
            @if($assessment->risk_level === 'low')
                Keep good habits: use strong passwords, keep profiles private, and be cautious with unknown contacts.
            @elseif($assessment->risk_level === 'medium')
                Improve safety: review privacy settings, reduce risky chats, and enable 2FA where possible.
            @else
                High risk: talk with a parent/teacher, restrict unknown contacts, review accounts, and consider digital safety guidance.
            @endif
        </div>
    </div>
</x-app-layout>
