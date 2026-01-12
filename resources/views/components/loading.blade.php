{{-- Loading Spinner Reutilizável --}}
<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    <div class="animate-spin rounded-full border-t-2 border-b-2 border-blue-500" 
         style="width: {{ $size ?? '24px' }}; height: {{ $size ?? '24px' }};"></div>
    @if(isset($text))
        <span class="ml-2 text-gray-400">{{ $text }}</span>
    @endif
</div>