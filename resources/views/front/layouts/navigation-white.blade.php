<!-- navigationbar start -->
<header class="navigation white-header">
    <nav class="navigation-bar">
        <div class="container">
        <div class="menu">
            <div class="navigation-icn">
                <svg id="icon-fill" width="30" height="11" viewBox="0 0 30 11" xmlns="http://www.w3.org/2000/svg" class="hamburger-icon-svg">
                    <path d="M0 0h30v1H0zm0 10h30v1H0z" fill-rule="evenodd" fill="#000"></path>
                </svg>
            </div>
            <div class="sq-logo">
                <a href="{!! route('home') !!}" aria-label="sq-logo">
                    <img src="{!! asset('assets/frontend/images/black-logo.webp') !!}" alt="sq-logo" width="230" height="33">
                </a>
            </div>
            <div class="menu-list">
                <ul class="menulist-ul">
                <li>
                    <span class="menu-text">
                    <a href="{!! route('services') !!}">What We Do</a>
                    </span>
                    <ul class="menutxt-sub">
                        <!-- <li>
                            <span>
                            <a href="javascript:void(0);">Products</a>
                            </span>
                            <ul class="product-list">
                            <li>
                                <a href="javascript:void(0);">WaterQuest</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Intelliquest</a>
                            </li>
                            </ul>
                        </li> -->
                        <li>
                            <span>
                            <a href="{!! route('services') !!}">Services</a>
                            </span>
                            <ul class="services-list">
                            @foreach ($services as $service)
                                <li><a href="{!! url('services/'.$service->slug) !!}">{!!$service->name!!}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <span class="menu-text">
                    <a href="{!! route('reports') !!}">Reports</a>
                    </span>
                    {!!getMegaMenuReports()!!}
                </li>
                <li>
                    <span class="menu-text">
                    <a href="{!! route('insights') !!}">Insights</a>
                    </span>
                </li>
                </ul>
            </div>
        </div>
        <div class="account-login">
            @if(auth('web')->check())
                <a href="{!! route('my-bookmarks') !!}"><img class="book-mark-tag" src="{!! asset('assets/frontend/images/black-bookmark.svg') !!}" width="16" height="22" alt="Top market research company in India"></a>
            @else
            <img class="book-mark-tag" id="viewBookmark" src="{!! asset('assets/frontend/images/black-bookmark.svg') !!}" width="16" height="22" alt="Top market research company in India">            
            @endif
            <button class="lets-talk-btn" id="lets-talk">
                <i class="fas fa-comments"></i>
            </button>
            @if(auth('web')->check())
            <form method="POST" action="{!! route('logout') !!}" id="frmLogout">
                @csrf
            </form>            
            <div class="avtar-dropdown dropdown">
                <a href="{!! route('settings') !!}" class="btn name-btn" id="dropdowntopMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="user-name">{{ Auth::user()->user_name }}</span> 
                    <img src="{{ Auth::user()->image_url }}" class="user-img" alt="user-img"/>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdowntopMenuButton1">
                    <li><a class="dropdown-item" href="{!! route('my-reports') !!}">My Reports</a></li>
                    <li><a class="dropdown-item" href="{!! route('my-bookmarks') !!}">My Bookmarks</a></li>
                    <li><a class="dropdown-item" href="{!! route('settings') !!}">Settings</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="javascript:$('#frmLogout').submit();">Logout</a></li>
                </ul>
            </div>
            @else                
            <div class="login" data-bs-toggle="modal" data-bs-target="#loginModal">login</div>
            @endif
	    <form class="header-search-form desk" name="frmsearchWhite" id="frmsearchWhite" method="post" action="{!! route('search-detail') !!}">
                @csrf
                <div class="input-group">
                    <input type="text" class="form-control search-click" id="searchtxtWhite" name="searchtxtWhite" placeholder="Search..." value="{!! old('searchtxtWhite') ?? app('request')->input('searchtxtWhite') !!}">
                    <button class="btn" type="submit" id="searchbtnWhite" name="searchbtnWhite" aria-label="search btn"><i class="fas fa-search"></i></button>
                </div>
                <div class="suggetions-container" id="suggestionsWhite" style="display:none;"></div>
            </form>
        </div>
        </div>
        <div class="mega-navigation" style="display: none;">
            <div class="mega-menu">
                <span class="cancel-btn">
                <img src="{!! asset('assets/frontend/images/humburg-menu.svg') !!}" alt="cancel-btn">
                </span>
                <div class="megamenu-inner">
                    <ul class="mega-nav">
                    <li>
                        <a href="{!! route('home') !!}">Home</a>
                    </li>
                    <li>
                        <a href="{!! route('about-us') !!}">About Us</a>
                    </li>
                    <li>
                        <a href="{!! route('insights') !!}">Insights</a>
                    </li>
                    <span class="side-nav">
                        <li>
                            <a href="{!! route('reports') !!}">Reports</a>
                            <ul class="report-nav">
                                @foreach ($sectors as $sector)
                                <li>
                                <a href="{!! url('industries/'.$sector->slug) !!}">{!! $sector->title !!}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <!-- <li>
                            <a href="javascript:void(0);">Products</a>
                            <ul class="report-nav">
                                <li>
                                <a href="javascript:void(0);">WaterQuest</a>
                                </li>
                                <li>
                                <a href="javascript:void(0);">Intelliquest</a>
                                </li>
                            </ul>
                        </li> -->
                        <li>
                            <a href="{!! route('services') !!}">Services</a>
                            <ul class="report-nav">
                            @foreach ($services as $service)
                                <li><a href="{!! url('services/'.$service->slug) !!}">{!!$service->name!!}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    </span>
                    <li>
                        <a href="{!! route('case-studies') !!}">Case Studies</a>
                    </li>
                    <li>
                        <a href="{!! route('careers') !!}">Careers</a>
                    </li>
                    </ul>
                    <ul class="megamenu-list">
                    <li>
                        <span>
                        <a href="{!! route('reports') !!}">Reports</a>
                        </span>
                        <ul>
                           @foreach ($sectors as $sector)
                           <li>
                              <a href="{!! url('industries/'.$sector->slug) !!}">{!! $sector->title !!}</a>
                           </li>
                           @endforeach
                        </ul>
                    </li>
                    </ul>
                    <ul class="megamenu-list">
                    <!-- <li>
                        <span>
                        <a href="javascript:void(0);">Products</a>
                        </span>
                        <ul>
                            <li>
                                <a href="javascript:void(0);">WaterQuest</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">Intelliquest</a>
                            </li>
                        </ul>
                    </li> -->
                    <li>
                        <span>
                        <a href="{!! route('services') !!}">Services</a>
                        </span>
                        <ul>
                        @foreach ($services as $service)
                            <li><a href="{!! url('services/'.$service->slug) !!}">{!!$service->name!!}</a></li>
                        @endforeach
                        </ul>
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<!-- START SEARCH FOR MOBILE VIEW -->
<form class="header-search-form mob white" name="frmsearchMobileWhite" id="frmsearchMobileWhite" method="post" action="{!! route('search-detail') !!}">
    @csrf
    <div class="input-group">
        <input type="text" class="form-control" id="searchtxtMobileWhite" name="searchtxtMobileWhite" placeholder="Search..." value="{!! old('searchtxtMobileWhite') ?? app('request')->input('searchtxtMobileWhite') !!}">
        <button class="btn" type="submit" id="searchbtnMobileWhite" name="searchbtnMobileWhite" aria-label="search btn"><i class="fas fa-search"></i></button>
    </div>
    <div class="suggetions-container" id="suggestionsMobileWhite" style="display:none;"></div>
</form>
<!-- START SEARCH FOR MOBILE VIEW -->
<!-- navigationbar end -->