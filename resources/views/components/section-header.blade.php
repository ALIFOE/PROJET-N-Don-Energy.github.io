@props(['actions' => null])

<div class="bg-white shadow sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            {{ $title }}
        </div>
        
        @if ($actions)
            <div class="flex items-center space-x-4">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>