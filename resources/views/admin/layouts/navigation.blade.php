<!-- Side Navbar -->
<nav class="side-navbar">
    <!-- Sidebar Header-->
    <div class="sidebar-header text-center">
        <div class="avatar mb-3">
            @if(isset(auth('admin')->user()->image_url))
            <img src="{!! auth('admin')->user()->image_url !!}" class="img-fluid rounded-circle" id="profile_pic">
            @else
            <img src="{!! asset('/assets/backend/images/default-avatar.png') !!}" class="img-fluid rounded-circle" id="profile_pic">
            @endif
        </div>
        <div class="title">
            <h1 class="h4">{!! ucwords(auth('admin')->user()->user_name) !!}</h1>
        </div>
    </div>

    <!-- Sidebar Navidation Menus-->
    <ul class="list-unstyled mainmenu" id="accordion">
        <li @if(request()->segment(2) == 'dashboard') class="active" @endif>
            <a href="{!! route('admin.dashboard') !!}">
                <i class="fa fa-home"></i> Dashboard
            </a>
        </li>
        @if(auth('admin')->user()->can('admin-list'))
        <li @if(request()->segment(2) == 'admin') class="active" @endif>
            <a href="{!! route('admin.admin.index') !!}">
                <i class="fa-solid fa-user-group"></i> Manage Admins
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('role-list'))
        <li @if(request()->segment(2) == 'roles') class="active" @endif>
            <a href="{!! route('admin.roles.index') !!}">
                <i class="fa-solid fa-tasks"></i> Manage Roles
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('report-list'))
        <li @if(request()->segment(2) == 'report') class="active" @endif>
            <a href="{!! route('admin.report.index') !!}">
                <i class="fa fa-bars" aria-hidden="true"></i> Manage Reports
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('free-sample-request-list'))
        <li @if(request()->segment(2) == 'free-sample-request') class="active" @endif>
            <a href="{!! route('admin.free-sample-request.index') !!}">
                <i class="fa-regular fa-file-lines"></i> Manage Free Sample Request
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('report-inquiry-list'))
        <li @if(request()->segment(2) == 'report-inquiry') class="active" @endif>
            <a href="{!! route('admin.report-inquiry.index') !!}">
            <i class="fa-solid fa-list-check"></i> Manage Report Inquiry
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('report-subscription-list'))
        <li @if(request()->segment(2) == 'report-subscription') class="active" @endif>
            <a href="{!! route('admin.report-subscription.index') !!}">
            <i class="fa fa-certificate" aria-hidden="true"></i> Manage Report Subscription
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('report-order-list'))
        <li @if(request()->segment(2) == 'report-order') class="active" @endif>
            <a href="{!! route('admin.report-order.index') !!}">
            <i class="fa fa-certificate" aria-hidden="true"></i> Manage Report Order
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('service-list'))
        <li @if(request()->segment(2) == 'service') class="active" @endif>
            <a href="{!! route('admin.service.index') !!}">
                <i class="fa fa-gears"></i> Manage Services
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('career-list'))
        <li @if(request()->segment(2) == 'career') class="active" @endif>
            <a href="{!! route('admin.career.index') !!}">
                <i class="fa-solid fa-book"></i> Manage Careers
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('user-list'))
        <li @if(request()->segment(2) == 'user') class="active" @endif>
            <a href="{!! route('admin.user.index') !!}">
                <i class="fa fa-users"></i> Manage Users
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('sectors-list'))
        <li @if(request()->segment(2) == 'sectors') class="active" @endif>
            <a href="{!! route('admin.sectors.index') !!}">
                <i class="fa-solid fa-sliders"></i> Manage Sectors
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('casestudy-list'))
        <li @if(request()->segment(2) == 'casestudy') class="active" @endif>
            <a href="{!! route('admin.casestudy.index') !!}">
            <i class="fa-regular fa-file-lines"></i> Manage Case Study
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('award-list'))
        <li @if(request()->segment(2) == 'award') class="active" @endif>
            <a href="{!! route('admin.award.index') !!}">
            <i class="fa-solid fa-award"></i> Manage Awards
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('insight-list'))
        <li @if(request()->segment(2) == 'insight') class="active" @endif>
            <a href="{!! route('admin.insight.index') !!}">
                <i class="fa-regular fa-lightbulb"></i> Manage Insights
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('client-feedback-list'))
        <li @if(request()->segment(2) == 'client-feedback') class="active" @endif>
            <a href="{!! route('admin.client-feedback.index') !!}">
            <i class="fa-regular fa-pen-to-square"></i> Manage Client Feedback
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('sector-list'))
        <li @if(request()->segment(2) == 'sector') class="active" @endif>
            <a href="{!! route('admin.sector.index') !!}">
                <i class="fa-solid fa-sliders"></i> Manage Sector
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('industry-group-list'))
        <li @if(request()->segment(2) == 'industry-group') class="active" @endif>
            <a href="{!! route('admin.industry-group.index') !!}">
                <i class="fa-solid fa-city"></i> Manage Industry Group
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('industry-list'))
        <li @if(request()->segment(2) == 'industry') class="active" @endif>
            <a href="{!! route('admin.industry.index') !!}">
                <i class="fa-regular fa-building"></i> Manage Industry
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('sub-industry-list'))
        <li @if(request()->segment(2) == 'sub-industry') class="active" @endif>
            <a href="{!! route('admin.sub-industry.index') !!}">
                <i class="fa-regular fa-building"></i> Manage Sub Industry
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('appointment-list'))
        <li @if(request()->segment(2) == 'appointment') class="active" @endif>
            <a href="{!! route('admin.appointment.index') !!}">
                <i class="fa-regular fa-calendar-check"></i> Manage Appointments
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('contactus-list'))
        <li @if(request()->segment(2) == 'contactus') class="active" @endif>
            <a href="{!! route('admin.contactus.index') !!}">
                <i class="fa-regular fa-calendar-check"></i> Manage Contact Us
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('our-team-list'))
        <li @if(request()->segment(2) == 'our-team') class="active" @endif>
            <a href="{!! route('admin.our-team.index') !!}">
                <i class="fa-regular fa-calendar-check"></i> Manage Our Team
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('job-application-list'))
        <li @if(request()->segment(2) == 'job-application') class="active" @endif>
            <a href="{!! route('admin.job-application.index') !!}">
                <i class="fa-solid fa-briefcase"></i> Manage Job Applications
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('404-inquiry-list'))
        <li @if(request()->segment(2) == '404-inquiry') class="active" @endif>
            <a href="{!! route('admin.404-inquiry.index') !!}">
                <i class="fa-solid fa-briefcase"></i> Manage 404 Inquiry
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('gallery-list'))
        <li @if(request()->segment(2) == 'gallery') class="active" @endif>
            <a href="{!! route('admin.gallery.index') !!}">
                <i class="fa-solid fa-layer-group"></i> Manage Gallery
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('homepage'))
        <li @if(request()->segment(2) == 'homepage') class="active" @endif>
            <a href="{!! route('admin.homepage.index') !!}">
                <i class="fa-solid fa-layer-group"></i> Manage Home Page
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('pages-list'))
        <li @if(request()->segment(2) == 'pages') class="active" @endif>
            <a href="{!! route('admin.pages.index') !!}">
                <i class="fa-solid fa-layer-group"></i> Manage Pages
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('email-restriction-list'))
        <li @if(request()->segment(2) == 'email_restriction') class="active" @endif>
            <a href="{!! route('admin.email-restriction.index') !!}">
                <i class="fa-solid fa-layer-group"></i> Manage Email Restriction
            </a>
        </li>
        @endif
        @if(auth('admin')->user()->can('report-export'))
        <li @if(request()->segment(2) == 'report_export') class="active" @endif>
            <a href="{!! route('admin.report-export.index') !!}">
                <i class="fa-solid fa-file-export"></i> Manage Report Export
            </a>
        </li>
        @endif
    </ul>
</nav>
