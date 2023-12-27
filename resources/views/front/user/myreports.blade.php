@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="content-body">
    <div class="container">
        <div class="account-header">
            <button class="btn btn-blue nav-collapse-btn"></button>
            <h3>Hello! {{ $user->user_name }}</h3>
        </div>
        <div class="account-tabs d-flex align-items-start">
            @include("front.layouts.account")
            <div class="content-view">
                <div class="reports p-0">                
                    @if(!$order_reports->isEmpty())            
                    @foreach ($order_reports as $order_report)
                        @php
                        // prepare report name 
                        $report_name = $order_report->report->name;

                        if(isset($order_report->report->report_segments)){
                            $report_name .= " Size, Share, Growth Analysis";

                            foreach($order_report->report->report_segments as $report_segment){
                                $sub_segmentsArr = array();
                                $report_name .= ", By ".$report_segment->name;
                                $sub_segmentsArr = explode(",",$report_segment->value);
                                if(count($sub_segmentsArr)>0){
                                    if(count($sub_segmentsArr)==1)
                                    $report_name .= "(".$sub_segmentsArr[0].")";
                                    if(count($sub_segmentsArr)>=2)
                                    $report_name .= "(".$sub_segmentsArr[0].",".$sub_segmentsArr[1].")";
                                }
                            }
                            $report_name .= " - Industry Forecast ".$settings->forecast_year;
                        }
                        @endphp
                    <div class="report-items-inner mt-0 mb-4">
                        <div class="report-img">
                            <a href="{!! url('report/'.$order_report->report->slug) !!}">
                                <img src="{!! $order_report->report->image_url !!}" alt="{!! $order_report->report->image_alt ?? '' !!}"></a>
                        </div>
                        <div class="containt">
                            <h1><a href="{!! url('report/'.$order_report->report->slug) !!}">{!! $report_name !!}</a></h1>
                            <div class="report-segment-data">
                                <hr/>
                                <p class="grey-content">
                                    @if(isset($order_report->report->product_id))
                                    <b>Report ID:</b>
                                    {!! $order_report->report->product_id. ' | ' !!}
                                    @endif

                                    @if(isset($order_report->report->country))
                                    <b>Region:</b>
                                    {!! ucfirst($order_report->report->country). ' | ' !!}
                                    @endif

                                    @if($order_report->report->report_type=='Upcoming')
                                        <b>Published Date:</b> Upcoming | 
                                    @elseif($order_report->report->publish_date!='')
                                        <b>Published Date:</b> {!! convertUtcToIst($order_report->report->publish_date, config('constants.DISPLAY_REPORT_DATE')) .' | ' !!}
                                    @endif

                                    @if(isset($order_report->report->pages))
                                    <b>Pages:</b>
                                    {!! $order_report->report->pages !!}
                                    @endif
                                </p>
                                <p class="grey-content">
                                    <b>Purchase Price:</b>
                                    ${!! number_format($order_report->report->report_pricing[0]['price'],0) !!} |
                                    <b>Purchase Date:</b>
                                    {!! (isset($order_report->created_at)) ? convertUtcToIst($order_report->created_at, config('constants.DISPLAY_SEARCH_REPORT_DATE')) .' | ' : '' !!}
                                    <b>License Type:</b>
                                    {!! $order_report->license_type !!} | <br>
                                    <b>File Type:</b>
                                    {!! $order_report->file_type !!}
                                </p>
                                <p class="green-content">{!! $order_report->report->download !!}+ Downloads</p>
                                {!! \Illuminate\Support\Str::limit($order_report->report->market_insights, 150, $end=' ...') !!}                                
                            </div>
                        </div>
                    </div>
                    <hr class="hr-tag">
                    @endforeach
                    @else
                    <h3 class="no-data-text text-center">No Reports Found!</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
<!-- js link -->
<script src="{!! asset('assets/frontend/js/pages/myreports.js') !!}"></script>
@stop
@endsection