@props(['lead'])

<a href="{{ route('leads.show', $lead->id) }}" class="block bg-white dark:bg-gray-700 p-4 rounded shadow hover:shadow-md transition duration-200 border-l-4 
    {{ $lead->status == 'won' ? 'border-green-500' : ($lead->status == 'negotiation' ? 'border-blue-500' : 'border-gray-500') }}">
    
    <div class="flex justify-between items-start mb-2">
        <h4 class="font-bold text-gray-800 dark:text-white text-sm">{{ $lead->title }}</h4>
        <span class="text-xs text-gray-400">#{{ $lead->id }}</span>
    </div>

    <p class="text-xs text-gray-500 dark:text-gray-300 mb-2">{{ $lead->client->name }}</p>

    <div class="flex justify-between items-center mt-3">
        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">R$ {{ number_format($lead->value, 2, ',', '.') }}</span>
        
        <span class="text-[10px] text-gray-400">
            {{ $lead->created_at->format('d/m') }}
        </span>
    </div>
</a>