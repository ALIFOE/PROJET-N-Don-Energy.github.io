@if(isset($devisCount) && $devisCount > 0)
    <span class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
        {{ $devisCount }}
    </span>
@endif

@if(isset($formationsCount) && $formationsCount > 0)
    <span class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
        {{ $formationsCount }}
    </span>
@endif

@if(isset($boutiqueCount) && $boutiqueCount > 0)
    <span class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
        {{ $boutiqueCount }}
    </span>
@endif

@if(isset($servicesCount) && $servicesCount > 0)
    <span class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
        {{ $servicesCount }}
    </span>
@endif

@if(isset($usersCount) && $usersCount > 0)
    <span class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
        {{ $usersCount }}
    </span>
@endif
