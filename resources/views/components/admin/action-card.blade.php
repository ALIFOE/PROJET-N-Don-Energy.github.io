<a href="{{ $link }}" 
   class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow group">
    <div class="flex items-center mb-4">
        <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center group-hover:bg-{{ $color }}-500 transition-colors">
            <i class="fas {{ $icon }} text-{{ $color }}-500 group-hover:text-white text-xl"></i>
        </div>
        <h3 class="ml-4 text-lg font-semibold text-gray-800">{{ $title }}</h3>
    </div>
    <p class="text-gray-600 text-sm">{{ $description }}</p>
</a>
