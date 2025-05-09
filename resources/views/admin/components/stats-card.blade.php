@props([
    'title',
    'count',
    'icon',
    'color',
    'subtext',
    'subcount'
])

<div class="bg-gradient-to-br from-{{ $color }}-500 to-{{ $color }}-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-3xl font-bold mb-1">{{ $count }}</p>
            <h3 class="text-lg font-medium opacity-90">{{ $title }}</h3>
        </div>
        <div class="bg-{{ $color }}-400 bg-opacity-30 p-3 rounded-lg">
            <i class="fas {{ $icon }} text-2xl"></i>
        </div>
    </div>
    <div class="mt-4 flex items-center text-{{ $color }}-100">
        @if(isset($subcount))
            <i class="fas fa-arrow-up mr-1"></i>
        @endif
        <span>{{ $subtext }}</span>
    </div>
</div>
