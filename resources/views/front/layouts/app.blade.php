<!DOCTYPE html>
@if(request()->root() == config('app.domain_name_english'))
<html lang="{!! str_replace('_', '-', app()->getLocale()) !!}">
@else
<html lang="ja">
@endif
{{-- @dump(request()->root()) --}}
<head>
@if((request()->root()=='https://skyquestt.com') || (request()->root()=='https://www.skyquestt.com'))
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5K2H2RJ');</script>
<!-- End Google Tag Manager -->
@endif
@if(request()->root() == config('app.domain_name_english'))
<meta charset="utf-8">
@else
<meta charset="utf-16">
@endif
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{!! csrf_token() !!}">
@if($page_title != '')
<title>{!! $page_title !!}</title>
@else
<title>{!! $title !!}</title>
@endif
<link rel="icon" href="{!! asset('assets/backend/images/favicon.ico') !!}">
@if(isset($meta_title) && $meta_title!='')
<meta name="title" content="{!! $meta_title !!}">
<meta property="og:title" content="{!! $meta_title !!}" />
<meta property="twitter:title" content="{!! $meta_title !!}" />
@else
<meta name="title" content="{!! config('app.name') !!}@if(isset($title) && $title!='') | {!! $title !!}@endif">
<meta property="og:title" content="{!! config('app.name') !!}@if(isset($title) && $title!='') | {!! $title !!}@endif" />
<meta property="twitter:title" content="{!! config('app.name') !!}@if(isset($title) && $title!='') | {!! $title !!}@endif" />
@endif
@if(isset($meta_description) && $meta_description!='')
<meta name="description" content="{!! $meta_description !!}">
<meta property="og:description" content="{!! $meta_description !!}" />
<meta property="twitter:description" content="{!! $meta_description !!}" />
@else
<meta name="description" content="Technology driven best market research companies international market research and global market research companies where we simplify data and unravel insights that can keep you one step ahead of competition."/>
<meta property="og:description" content="Technology driven best market research companies international market research and global market research companies where we simplify data and unravel insights that can keep you one step ahead of competition." />
<meta property="twitter:description" content="Technology driven best market research companies international market research and global market research companies where we simplify data and unravel insights that can keep you one step ahead of competition." />
@endif
<meta name="keyword" content="{!! $meta_keyword !!}">
<meta property="og:locale" content="en_GB"/>
<meta name="twitter:card" content="summary"></meta>
@if(isset($imageURL) && $imageURL!='')
<meta property="og:image" content="{!! $imageURL !!}" />
<meta property="twitter:image" content="{!! $imageURL !!}" />
@else
<meta property="og:image" content="{!! asset('assets/frontend/images/apple-icon-57x57.png') !!}" />
<meta property="twitter:image" content="{!! asset('assets/frontend/images/apple-icon-57x57.png') !!}" />
@endif
<meta property="og:type" content="website" />
<meta property="og:url" content="{!! Request::url() !!}" />
<link rel="canonical" href="{!! Request::url() !!}" />
@if(isset($schema) && $schema!='')
{!! $schema !!}
@endif
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@if(request()->segment(1) == 'sample-request' || request()->segment(1) == 'speak-with-analyst' || request()->segment(1) == 'buy-now' || request()->segment(1) == 'subscribe-now')
<meta name="robots" content="noindex">
@else
<meta name="robots" content="all,follow">
@endif
<link rel="stylesheet" href="{!! asset('assets/frontend/css/all.css') !!}">
@yield('css')
@if((request()->root()=='https://skyquestt.com') || (request()->root()=='https://www.skyquestt.com'))
<meta name="yandex-verification" content="8c44b07f3698f788" />
@endif
<style>
    div.skiptranslate { display: none;}
    body{ top: 0 !important;}
</style>
</head>

<body @if(request()->segment(1) == 'buy-now') class="buy-now-body" @endif>
{{-- Google Translate Div --}}
    <div id="google_translate_element" style="display: none;"></div>
{{-- Google Translate Div --}}
@if((request()->root()=='https://skyquestt.com') || (request()->root()=='https://www.skyquestt.com'))
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5K2H2RJ" height="0" width="0" style="display:none;visibility:hidden" title="iframe"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
    <!-- PAGE LOADER -->
    <div class="page-loader" style="display: none;"><span class="loader-image"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span></div>
    <!-- PAGE LOADER -->
    <!-- Google Translator -->
    <!-- <div id="google_translate_element"></div> 
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                {pageLanguage: 'en', includedLanguages : 'ar,bn,gu', layout: google.translate.TranslateElement.InlineLayout.SIMPLE},
                'google_translate_element'
            );
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> -->
    <!-- Google Translator -->

    @yield('content')

    @if(request()->segment(1) != 'buy-now')
    <!-- footer-section start -->
    <footer class="footer-section">
        <div class="container">
            <div class="footer-section-inner">
                <h5 class="title1">Lets work together</h5>
                <h5 class="title2">Say hi.</h5>
                <div class="footer-menus row">
                    <div class="footerinner-menus col-lg-2 col-sm-3 col-6">
                        <p>SkyQuest</p>
                        <a href="{!! route('home') !!}" title="Home">Home</a>
                        <a href="{!! route('about-us') !!}" title="About">About</a>
                        <a href="{!! route('reports') !!}" title="Reports">Reports</a>
                        <a href="{!! route('insights') !!}" title="Insights">Insights</a>
                        <a href="{!! route('contact-us') !!}" title="Contact Us">Contact Us</a>
                    </div>
                    <div class="footerinner-menus col-lg-2 col-sm-3 col-6">
                        <p>Services</p>
                        @foreach ($services as $service)
                        <a href="{!! url('services/'.$service->slug) !!}" title="{!!$service->name!!}">{!!$service->name!!}</a>
                        @endforeach
                    </div>
                    <!-- <div class="footerinner-menus col-lg-2 col-sm-3 col-6">
                        <p>Products</p>
                        <a href="javascript:void(0);" title="Waterquest">Waterquest</a>
                        <a href="javascript:void(0);" title="Intelliquest">Intelliquest</a>
                    </div> -->
                    <div class="footerinner-menus col-lg-2 col-sm-3 col-6">
                        <p>Privacy</p>
                        <a href="{!! route('privacy') !!}" title="Privacy Policy">Privacy Policy</a>
                        <a href="{!! route('cookies') !!}" title="Cookies">Cookies</a>
                    </div>
                    <div class="footerinner-menus col-lg-2">
                        <p>Social</p>
                        <a href="https://twitter.com/skyquestt?lang=en" title="twitter">
                            <span>
                                <img src="{!! asset('assets/frontend/images/twitter.webp') !!}" alt="twitter"
                                    class="social-media" width="20" height="20">
                                twitter
                            </span>
                        </a>
                        <a href="https://www.facebook.com/STI4SDG/" title="facebook">
                            <span>
                                <img src="{!! asset('assets/frontend/images/facebook.webp') !!}" alt="facebook"
                                    class="social-media" width="20" height="20">
                                facebook
                            </span>
                        </a>
                        <a href="https://www.linkedin.com/company/skyquest-technology-consulting-private-limited/" title="linkedin">
                            <span>
                                <img src="{!! asset('assets/frontend/images/linkedin.webp') !!}" alt="linkedin"
                                    class="social-media" width="20" height="20">
                                linkedin
                            </span>
                        </a>
                    </div>
                    <div class="footerinner-menus col-lg-2">
                        <p>(+1) 617-230-0741</p>
                        <img src="{!! asset('assets/frontend/images/email-text.svg') !!}" width="160" height="18"
                                alt="emai-text-blue" style="height:auto;margin-bottom: 16px;">
                        <p>© 2022 SkyQuest Technology Consulting Pvt. Ltd.</p> 
                        <p>All rights reserved</p> 
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer-section end -->

    @include("front.layouts.auth")

    @endif

    <div id="cookieNotice" class="cookieView" style="display: none;">
        <div class="content-wrap">
            <p><span id="closeIcon" style="display: none;"></span> This site uses cookies. See Our Privacy And Policy For More info.</a></p>
            <div class="btn-wrap">
                <button class="btn btn-blue" onclick="acceptCookieConsent();">Accept Cookies!</button>
                <button class="btn btn-light" onclick="declineCookieConsent();">Decline</button>
            </div>
        </div>
    </div>
    <script src="{!! asset('assets/frontend/js/all.js') !!}"></script>
    <script>
    var _token = $("input[name='_token']").val();
    var baseUrl = "{!! url('') !!}/";
    // to show toastr messages
    var emsg = '';
    var ecls = "success";
    @if(session('message') || session('status'))
    emsg = "{!! @session('message').@session('status') !!}";
    @if(session('alert-class') && session('alert-class') == "error")
    ecls = "error";
    @endif
    @endif
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        var route = "{{ request()->root() }}";
        var currLocale = "{{ (request()->root() == config('app.domain_name_english')) ? 'en' : 'ja' }}";
    </script>
    <script src="{!! asset('assets/frontend/js/utils-gl.js') !!}"></script>
    <script src="{!! asset('assets/frontend/js/common.js') !!}?v={!! time() !!}" async></script>

    @if(isset($resetForm) && $resetForm=='1')
    <script>
    $(document).ready(function() {
        $('#resetpwdModal').modal('show');
    });
    </script>
    @endif

    @if((!auth('web')->check()) && ($errors->has('email') || $errors->has('password') ||
    $errors->has('emailForgotpwd')))
    <script>
    $(function() {
        var modalFrm = '';

        @if(old('hdnauthbtn')=='1')
        modalFrm = 'login';
        @endif

        @if(session('modal') && session('modal') == 'register')
        modalFrm = 'register';
        @endif

        @if(session('modal') && session('modal') == 'forgotpwd')
        modalFrm = 'forgotpwd';
        @endif

        if (modalFrm == 'login')
            $('#loginModal').modal('show');
        else if (modalFrm == 'register')
            $('#registerModal').modal('show');
        else if (modalFrm == 'forgotpwd')
            $('#forgotpwdModal').modal('show');
    });
    </script>
    @endif

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6507ee2b0f2b18434fd914b4/1hajfkn8l';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->

    @yield('js')    
</body>

</html>
