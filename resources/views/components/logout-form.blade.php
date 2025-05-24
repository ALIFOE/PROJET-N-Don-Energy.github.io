@props(['class' => ''])
<form method="POST" action="{{ route('logout') }}" class="{{ $class }}">
    @csrf
    <button type="submit" {{ $attributes->merge(['class' => 'block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100']) }}>
        Déconnexion
    </button>
</form>
