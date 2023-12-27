<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use App\Models\Homepage;
use App\Models\HomepageModule;
use App\Models\Settings;
use App\Models\ClientFeedback;
use App\Models\CaseStudy;
use App\Models\Insight;
use App\Models\Award;
use App\Models\Service;
use App\Models\Sectors;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $title = '';
        $services = Service::where('is_active',1)->get();
        $sectorsData = Sectors::where('is_active',1)->get();
        $settings = Settings::first();
        $homepage = Homepage::first();

        $home_clientfeedbacks = HomepageModule::where('item_type', 'Feedback')->pluck('item_id')->toArray();
        $sel_clientfeedbacks = ClientFeedback::whereIn('id', $home_clientfeedbacks)->get();

        $home_casestudies = HomepageModule::where('item_type', 'Case Studies')->pluck('item_id')->toArray();
        $sel_casestudies = CaseStudy::whereIn('id', $home_casestudies)->get();
        
        $home_insights = HomepageModule::where('item_type', 'Insignts')->pluck('item_id')->toArray();
        $sel_insights = Insight::whereIn('id', $home_insights)->get();

        $home_awards = HomepageModule::where('item_type', 'Awads')->pluck('item_id')->toArray();
        $sel_awards = Award::whereIn('id', $home_awards)->get();

        $resetForm = 1;

        return view('front.home.index',compact('title','services','sectorsData','settings','homepage','sel_clientfeedbacks',
        'sel_casestudies','sel_insights','sel_awards','request','resetForm'));
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {        
        /*$request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);*/

        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'email' => ['required', 'email:filter'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);

        if ($validator->fails()) {
            //dd($validator->errors());
            $msg = $validator->errors()->first();
            $notification = ['message' => $msg,'alert-class' => 'error'];
            
            throw ValidationException::withMessages([
                'email' => $msg,
            ]);
        }
        else {

            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );
            
            // If the password was successfully reset, we will redirect the user back to
            // the application's home authenticated view. If there is an error we can
            // redirect them back to where they came from with their error message.
            return $status == Password::PASSWORD_RESET
                        ? redirect()->route('home')->with(['alert-class' => 'success', 'message' => "", 'status' => __($status)])
                        : back()->withInput($request->only('email'))
                                ->withErrors(['email' => __($status)]);
        }
    }
}
