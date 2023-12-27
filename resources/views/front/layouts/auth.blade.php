<!-- lets-talk-nav start -->
<div class="lets-bg" style="display:none;"></div>
<div class="lets-nav" style="display: none;">
    <div class="letsnav-inner">
        <div class="lets-cancle">
            <div class="lets-cancle-btn">
                <button class="close" id="lets-close" type="button" aria-label="Close"></button>
            </div>
        </div>
        <div class="lets-talk-data">
            <p>Let's Talk</p>
            <div class="lets-talk-body">
                Our experts understand & Guide your projects with tailor made solutions just for you
                <br>
                <a href="{!! route('contact-us') !!}">
                    <button class="lets-nav-btn">
                        <span>Let's Talk</span>
                        <img src="{!! asset('assets/frontend/images/right-arrow.svg') !!}" alt="right-arrow" width="32" height="21">
                    </button>
                </a>
                <div class="Number">
                    <a href="tel:6172 300 741">
                        <img src="{!! asset('assets/frontend/images/call.svg') !!}" alt="call">USA (+1) 617-230-0741
                    </a>
                </div>
                <div class="email">
                    <a href="mailto: info@skyquestt.com">
                        <img src="{!! asset('assets/frontend/images/email.svg') !!}" alt="email">
                        <img src="{!! asset('assets/frontend/images/email-text-blue.svg') !!}" width="160" height="18"
                            alt="emai-text-blue" style="width:auto;height:auto;margin-right:auto;">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- lets-talk-nav end -->

<!-- login-modal -->
<div class="modal fade login-modal resize-modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Login to SkyQuest</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmlogin" name="frmlogin" method="post" action="{!! url('login') !!}">
                    @csrf
                    <input type="email" class="modal-input" placeholder="Email" id="login_email" name="email"
                        value="{{ old('email') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="pass-input-grp mt-20">
                        <input type="password" class="modal-input" placeholder="Password" id="login_password" name="password"
                            value="">
                        <span toggle="#login_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input type="hidden" name="hdnauthbtn" id="hdnauthbtnlogin" value="1">
                    <button class="btn modal-login-btn" type="submit" name="authbtn">
                        <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status"
                            aria-hidden="true"></span>
                        Login
                    </button>
                    <div class="dont-have-ac mt-2">
                        <div class="new-ac">
                            <small class="ac-txt">Don't have an account ? <a data-bs-dismiss="modal"
                                    data-bs-toggle="modal" href="#registerModal">Create an Account</a></small>
                        </div>
                        <div class="forgot-ps">
                            <small class="ac-txt">
                                <a data-bs-dismiss="modal" data-bs-toggle="modal" href="#forgotpwdModal">Forgot password
                                    ?</a>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- login-modal -->

<!-- create register modal -->
<div class="modal fade resize-modal cret-ac" id="registerModal" tabindex="-1" role="dialog"
    aria-labelledby="registerModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create an account</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmregister" name="frmregister" method="post" action="{!! url('register') !!}">
                    @csrf
                    <input type="email" class="modal-input" placeholder="Email" id="register_email" name="email"
                        value="{{ old('email') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="pass-input-grp mt-20">
                        <input type="password" class="modal-input" placeholder="Password" id="register_password" name="password"
                            value="">
                        <span toggle="#frmregister #register_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="pass-input-grp mt-20">
                        <input type="password" class="modal-input" placeholder="Confirm Password"
                            id="register_password_confirmation" name="password_confirmation" value="">
                        <span toggle="#register_password_confirmation" class="fa fa-eye-slash field-icon toggle-password"></span>
                    </div>
                    <input type="hidden" name="hdnauthbtn" id="hdnauthbtnregister" value="1">
                    <button class="btn modal-login-btn" type="submit" name="authbtn">
                        <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status"
                            aria-hidden="true"></span>
                        Create Account
                    </button>
                    <div class="dont-have-ac mt-3">
                        <div class="new-ac" style="flex:1;text-align:center;">
                            <small class="ac-txt">Already have an account ? <a data-bs-dismiss="modal"
                                    data-bs-toggle="modal" href="#loginModal">Login</a>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- create register modal -->

<!-- forgot forgot pwd modal -->
<div class="modal fade resize-modal cret-ac" id="forgotpwdModal" tabindex="-1" role="dialog"
    aria-labelledby="forgotpwdModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Forgot Password</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="heading">Enter your email address you’re using for your account below</p>
                <form id="frmForgotPassword" name="frmForgotPassword" method="POST"
                    action="{!! route('password.email') !!}">
                    @csrf
                    <input type="email" class="modal-input" id="forgot_email" name="email" placeholder="Email"
                        value="{{ old('email') }}">
                    @error('emailForgotpwd')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <input type="hidden" name="hdnauthbtn" id="hdnauthbtnforgotpwd" value="1">
                    <a href="#"><button class="modal-login-btn" type="submit" name="authbtn">
                            <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status"
                                aria-hidden="true"></span>
                            Send Password Reset Link
                        </button></a>
                    <div class="dont-have-ac">
                        <div class="new-ac">
                            <small class="ac-txt"><a data-bs-dismiss="modal" data-bs-toggle="modal"
                                    href="#loginModal">Back to Login</a>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- forgot forgot pwd modal -->

<!-- create reset pwd modal -->
<div class="modal fade resize-modal cret-ac" id="resetpwdModal" tabindex="-1" role="dialog"
    aria-labelledby="resetpwdModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Reset password</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="frmresetpwd" name="frmresetpwd" method="post" action="{!! route('password.update') !!}">
                    @csrf
                    <!-- Password Reset Token -->
                    @php
                    if(Route::is('password.reset'))
                    $token = $request->route('token');
                    @endphp
                    <input type="hidden" name="token" id="token" value="{!! isset($token) ? $token : '' !!}">
                    <input type="email" class="modal-input" placeholder="Email" id="resetpwd_email" name="email"
                        value="{!! $request->email ?? '' !!}">
                    @error('email')
                    <span class="invalid-feedback" role="alert" id="emailError">
                        <strong>{!! $message !!}</strong>
                    </span>
                    @enderror
                    <div class="pass-input-grp mt-20">
                        <input type="password" class="modal-input" placeholder="Password" id="resetpwd_password" name="password"
                            value="">
                        <span toggle="#frmresetpwd #resetpwd_password" class="fa fa-eye-slash field-icon toggle-password"></span>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert" id="passwordError">
                        <strong>{!! $message !!}</strong>
                    </span>
                    @enderror
                    <div class="pass-input-grp mt-20">
                        <input type="password" class="modal-input" placeholder="Confirm Password"
                            id="resetpwd_password_confirmation" name="password_confirmation" value="">
                        <span toggle="#frmresetpwd #resetpwd_password_confirmation"
                            class="fa fa-eye-slash field-icon toggle-password"></span>
                    </div>
                    <input type="hidden" name="hdnauthbtn" id="hdnauthbtnresetpwd" value="1">
                    <a href="#"><button class="modal-login-btn" type="submit" name="authbtn">
                            <span class="spinner-border spinner-border-sm me-2" style="display:none;" role="status"
                                aria-hidden="true"></span>
                            Reset password
                        </button></a>
                    <div class="dont-have-ac">
                        <div class="new-ac">
                            <small class="ac-txt"><a data-bs-dismiss="modal" data-bs-toggle="modal"
                                    href="#loginModal">Back to Login</a>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- create reset pwd modal -->