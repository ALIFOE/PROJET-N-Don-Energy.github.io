<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\EmailValidationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $emailValidator = new EmailValidationService();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email:rfc,dns',
                'max:255', 
                'unique:'.User::class,
                function ($attribute, $value, $fail) use ($emailValidator) {
                    if (!$emailValidator->isAllowedDomain($value)) {
                        $fail('Seules les adresses email de Google (Gmail), Microsoft (Outlook, Hotmail, Live) ou Yahoo sont acceptées.');
                        return;
                    }

                    if (!$emailValidator->verifyEmailExists($value)) {
                        $fail("Cette adresse email n'existe pas. Veuillez fournir une adresse email valide et active.");
                    }
                },
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client', // Attribution explicite du rôle client
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
