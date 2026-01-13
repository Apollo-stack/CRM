@props(['name'])

@php
    $initials = collect(explode(' ', $name))
        ->map(function ($segment) {
            return strtoupper(substr($segment, 0, 1));
        })
        ->take(2)
        ->join('');

    // Array de cores do Tailwind (safelist)
    $colors = [
        'bg-red-500', 'bg-orange-500', 'bg-amber-500', 
        'bg-yellow-500', 'bg-lime-500', 'bg-green-500', 
        'bg-emerald-500', 'bg-teal-500', 'bg-cyan-500', 
        'bg-sky-500', 'bg-blue-500', 'bg-indigo-500', 
        'bg-violet-500', 'bg-purple-500', 'bg-fuchsia-500', 
        'bg-pink-500', 'bg-rose-500'
    ];

    // Hash consistente para sempre dar a mesma cor para o mesmo nome
    $hash = crc32($name);
    $colorClass = $colors[$hash % count($colors)];
@endphp

<div {{ $attributes->merge(['class' => "rounded-full flex items-center justify-center text-white font-bold text-sm tracking-wider shadow-sm " . $colorClass]) }}>
    {{ $initials }}
</div>
