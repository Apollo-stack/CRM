@props(['title', 'value', 'icon', 'color' => 'indigo', 'trend' => null])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-{{ $color }}-500">
    <div class="flex items-center">
        <div class="p-3 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-900 text-{{ $color }}-500 dark:text-{{ $color }}-300 mr-4">
            {!! $icon !!}
        </div>
        <div>
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $title }}
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ $value }}
            </div>
            @if($trend)
                <div class="text-xs {{ $trend > 0 ? 'text-green-500' : 'text-red-500' }} font-bold mt-1">
                    {{ $trend > 0 ? '+' : '' }}{{ $trend }}%
                </div>
            @endif
        </div>
    </div>
</div>
