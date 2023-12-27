<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email:filter'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // return $status == Password::RESET_LINK_SENT
        //             ? back()->with(['alert-class' => 'success', 'message' => "", 'status' => __($status)])
        //             : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);

        if($status == Password::RESET_LINK_SENT){
            return back()->with(['alert-class' => 'success', 'message' => "", 'status' => __($status)]);
        }else{
            $notification = ['message' => __($status),'alert-class' => 'error','modal'=>'forgotpwd'];

            return back()->withInput($request->only('email'))
            ->withErrors(['emailForgotpwd' => __($status)])->with($notification);
        }

        // if($status == Password::RESET_LINK_SENT){
        //     $request->session()->put('status', __($status));
            
        //     //return back()->with(['alert-class' => 'success', 'message' => "", 'status' => __($status)]);
        //     return response()->json(['success' => 1, 'alert-class' => 'success', 'status' => __($status)]);
        // }else {
        //     throw ValidationException::withMessages([
        //         'email' => __($status),
        //     ]);
        // }                            
    }
}
