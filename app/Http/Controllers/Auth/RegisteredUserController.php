<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    protected function guard()
    {
        return Auth::guard('guest');
    }
       
    public function store(Request $request)
    {                
        /*$request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);*/
        
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email:filter', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);

        if ($validator->fails()) {
            //dd($validator->errors());
            $msg = $validator->errors()->first();
            $notification = ['message' => $msg,'alert-class' => 'error','modal'=>'register'];
            
            // throw ValidationException::withMessages([
            //     'email' => $msg,
            // ]);

            return redirect()->back()->withErrors($validator)->withInput($request->all())->with($notification);
        }
        else {
            $user = User::create([
                'user_name' => 'Default',
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->sendNewUserNotification($request->password);

            event(new Registered($user));
    
            Auth::login($user);
            
            //$request->session()->put('message', 'Registered Successfully!');

            return redirect(RouteServiceProvider::HOME)->with(['alert-class' => 'success', 'message' => "Registered Successfully!"]);
            //return response()->json(['success' => 1, 'alert-class' => 'success', 'message' => "Registered Successfully!"]);
        }        
    }
}
