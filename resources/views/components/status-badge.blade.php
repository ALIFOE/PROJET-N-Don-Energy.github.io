@props(['status'])

@php
$classes = match($status) {
    'planifiee' => 'bg-blue-100 text-blue-800',
    'en_cours' => 'bg-yellow-100 text-yellow-800',
    'terminee' => 'bg-green-100 text-green-800',
    'annulee' => 'bg-red-100 text-red-800',
    default => 'bg-gray-100 text-gray-800'
};

$labels = [
    'planifiee' => 'Planifiée',
    'en_cours' => 'En cours',
    'terminee' => 'Terminée',
    'annulee' => 'Annulée'
];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $labels[$status] ?? ucfirst($status) }}
</span>