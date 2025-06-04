@props(['url'])
<div class="mail-wrapper">
    <div class="mail-header">
        {{ config('app.name') }}
    </div>

    <div class="mail-body">
        {{ $slot }}
    </div>

    <div class="mail-footer">
        © {{ date('Y') }} {{ config('app.name') }}. @lang('Tous droits réservés.')
    </div>
</div>
