<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {        
        $request->authenticate();
        
        // check front user is active or not
        $user = auth('web')->user();
        if(!$user->is_active)
        {
            $this->guard()->logout();
            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Your account is inactive. Please contact your administrator to get access!',
                ]);
            // throw ValidationException::withMessages([
            //     'email' => 'Your account is inactive. Please contact your administrator to get access!',
            // ]);
        }

        $request->session()->regenerate();
        
        //$request->session()->put('message', 'Sign In Successfully!');
        
        return redirect()->intended(RouteServiceProvider::HOME)->with(['alert-class' => 'success', 'message' => "Sign In Successfully!"]);
        //return response()->json(['success' => 1, 'alert-class' => 'success', 'message' => "Sign In Successfully!"]);
    }
        
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        //return redirect('/');
        return redirect()->back()->with(['alert-class' => 'success', 'message' => "Sign Out Successfully!"]);
    }
}
