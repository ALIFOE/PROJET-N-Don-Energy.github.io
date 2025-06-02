<x-guest-layout>    <div class="mb-4 text-sm text-gray-600">
        {{ __('Nous avons envoyé un code de vérification à votre adresse email. Veuillez entrer ce code pour confirmer votre compte.') }}
    </div>

    @if (session('resent'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ __('Un nouveau code de vérification a été envoyé à votre adresse email.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf

        <!-- Code de vérification -->
        <div>
            <x-label for="code" :value="__('Code de vérification')" />
            <x-input id="code" 
                    type="text" 
                    name="code" 
                    :value="old('code')" 
                    required 
                    autofocus 
                    maxlength="6"
                    class="block w-full mt-1" />
        </div>

        @if ($errors->any())
            <div class="mt-4">
                <div class="text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <div class="flex items-center justify-end mt-4">
            <x-button>
                {{ __('Vérifier') }}
            </x-button>
        </div>
    </form>
</x-guest-layout>
