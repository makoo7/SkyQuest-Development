@extends('admin.layouts.app')

@section('content')
<!-- Dashboard Counts Section-->
<section class="dashboard-counts no-padding-bottom">
    <div class="container-fluid">
        <div class="row bg-white has-shadow">
            @if(auth('admin')->user()->can('admin-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.admin.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-user-group"></i></div>
                    <div class="title">
                        <span>Admins</span>
                        <div class="number"><strong>{!! $admin !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('role-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.roles.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-tasks"></i></div>
                    <div class="title">
                        <span>Roles</span>
                        <div class="number"><strong>{!! $role !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('report-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.report.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-bars"></i></div>
                    <div class="title">
                        <span>Reports</span>
                        <div class="number"><strong>{!! $report !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('free-sample-request-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.free-sample-request.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-file-lines"></i></div>
                    <div class="title">
                        <span>Free Sample Request</span>
                        <div class="number"><strong>{!! $report_sample_request !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('report-inquiry-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.report-inquiry.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-list-check"></i></div>
                    <div class="title">
                        <span>Report Inquiry</span>
                        <div class="number"><strong>{!! $report_inquiry !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('report-subscription-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.report-subscription.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-certificate"></i></div>
                    <div class="title">
                        <span>Report Subscription</span>
                        <div class="number"><strong>{!! $report_subscribe_now !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('report-order-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.report-order.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-certificate"></i></div>
                    <div class="title">
                        <span>Report Order</span>
                        <div class="number"><strong>{!! $report_orders !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('service-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.service.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-gears"></i></div>
                    <div class="title">
                        <span>Services</span>
                        <div class="number"><strong>{!! $service !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('career-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.career.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-book"></i></div>
                    <div class="title">
                        <span>Careers</span>
                        <div class="number"><strong>{!! $career !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('user-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.user.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-users"></i></div>
                    <div class="title">
                        <span>Users</span>
                        <div class="number"><strong>{!! $user !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('sectors-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.sectors.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-sliders"></i></div>
                    <div class="title">
                        <span>Sectors</span>
                        <div class="number"><strong>{!! $sectors !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('casestudy-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.casestudy.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-file-lines"></i></div>
                    <div class="title">
                        <span>Case Study</span>
                        <div class="number"><strong>{!! $casestudy !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('award-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.award.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-award"></i></div>
                    <div class="title">
                        <span>Awards</span>
                        <div class="number"><strong>{!! $award !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('insight-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.insight.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-lightbulb"></i></div>
                    <div class="title">
                        <span>Insights</span>
                        <div class="number"><strong>{!! $insight !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('client-feedback-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.client-feedback.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-pen-to-square"></i></div>
                    <div class="title">
                        <span>Client Feedback</span>
                        <div class="number"><strong>{!! $clientfeedback !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('sector-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.sector.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-sliders"></i></div>
                    <div class="title">
                        <span>Sector</span>
                        <div class="number"><strong>{!! $sector !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('industry-group-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.industry-group.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-city"></i></div>
                    <div class="title">
                        <span>Industry Group</span>
                        <div class="number"><strong>{!! $industrygroup !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('industry-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.industry.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-building"></i></div>
                    <div class="title">
                        <span>Industry</span>
                        <div class="number"><strong>{!! $industry !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('sub-industry-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.sub-industry.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-building"></i></div>
                    <div class="title">
                        <span>Sub Industry</span>
                        <div class="number"><strong>{!! $subindustry !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('appointment-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.appointment.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-calendar-check"></i></div>
                    <div class="title">
                        <span>Appointments</span>
                        <div class="number"><strong>{!! $appointment !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('contactus-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.contactus.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-calendar-check"></i></div>
                    <div class="title">
                        <span>Contact Us</span>
                        <div class="number"><strong>{!! $contactus !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('our-team-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.our-team.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-regular fa-calendar-check"></i></div>
                    <div class="title">
                        <span>Our Team</span>
                        <div class="number"><strong>{!! $ourteam !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('job-application-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.job-application.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-briefcase"></i></div>
                    <div class="title">
                        <span>Job Applications</span>
                        <div class="number"><strong>{!! $jobapplication !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('404-inquiry-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.404-inquiry.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-briefcase"></i></div>
                    <div class="title">
                        <span>404 Inquiry</span>
                        <div class="number"><strong>{!! $pagenotfound !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('gallery-list'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.gallery.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-layer-group"></i></div>
                    <div class="title">
                        <span>Gallery</span>
                        <div class="number"><strong>{!! $gallery !!}</strong></div>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('homepage'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.homepage.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-layer-group"></i></div>
                    <div class="title">
                        <span>Home Page</span>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @if(auth('admin')->user()->can('report-export'))
            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <a href="{!! route('admin.report-export.index') !!}" class="dashboard-counts-item d-block alert-secondary rounded px-3">
                <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa-solid fa-file-export"></i></div>
                    <div class="title">
                        <span>Report Export</span>
                    </div>
                </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection