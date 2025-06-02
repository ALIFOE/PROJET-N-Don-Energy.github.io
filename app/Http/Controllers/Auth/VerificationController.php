<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class VerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();

        if ($request->code === $user->verification_code) {
            $user->is_verified = true;
            $user->verification_code = null;
            $user->save();

            return redirect(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'code' => 'Le code de vérification est incorrect.',
        ]);
    }
}
