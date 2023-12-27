<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminLoginNotify;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $title = "Admin Login";
        return view('admin.auth.login',compact('title'));
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\AdminLoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminLoginRequest $request)
    {
        $request->authenticate();
        // check admin user is active or not
        $user = auth('admin')->user();
        if(!$user->is_active)
        {
            $this->guard()->logout();
            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Your account is inactive. Please contact your administrator to get access!',
                ]);
        }
        $request->session()->regenerate();

        // send mail to super admin for the admin login
        $user = auth('admin')->user();        
        $user->ip_address = \Request::getClientIp(true);        
        Mail::to(getSuperAdminEmail())->send(new AdminLoginNotify($user));
        
        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
