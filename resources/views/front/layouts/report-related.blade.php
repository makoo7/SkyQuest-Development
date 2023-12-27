<div class="related-report-sec" style="background-color:#fff;">
    <div class="container">
        <h3 class="text-center mb-5">Related Reports</h3>
        <div class="row">
            @foreach($related_reports as $related_report)
            <div class="card col-lg-3 col-md-6 mt-4 border-0">
                    <a href="{!! url('report/'.$related_report->slug) !!}" class="card-img-top re-img">
                        <img src="{!! $related_report->image_url !!}" alt="{!! $report->image_alt !!}" width="200" height="240" loading="lazy">
                    </a>
                    <div class="card-body content">
                        <p class="grey-content">
                            @if(isset($report->product_id))
                            <b>Report ID: </b>
                            {!! (isset($related_report->product_id)) ? $related_report->product_id .' | ' : '' !!}
                            @endif

                            @if(isset($report->country))
                            <b>Region:</b>
                            {!! (isset($related_report->country)) ? $related_report->country .' | ' : '' !!}
                            @endif

                            @if($report->report_type=='Upcoming')
                                <b>Published Date:</b> Upcoming | 
                            @elseif(($report->report_type!='Upcoming') && isset($report->publish_date))
                                <b>Published Date:</b> {!! convertUtcToIst($report->publish_date, config('constants.DISPLAY_REPORT_DATE')) .' | ' !!}
                            @endif

                            @if(isset($report->pages))
                            <b>Pages:</b>
                            {!! (isset($related_report->pages)) ? $related_report->pages : '' !!}
                            @endif

                            @if($report->report_type=='Upcoming')
                                | <b>Tables:</b> 55 | <b>Figures:</b> 60
                            @else
                                @if(!$report->report_tableofcontent->isEmpty())
                                    @if(($report->report_tableofcontent[0]->tables!='') || ($report->report_tableofcontent[0]->figures!=''))
                                    | 
                                    @endif
                                    
                                    @if($report->report_tableofcontent[0]->tables!='')
                                    <b>Tables:</b>
                                    {!! (isset($related_report->report_tableofcontent[0]->tables)) ? substr_count($related_report->report_tableofcontent[0]->tables,"<p>") .' | ' : '' !!}
                                    @endif

                                    @if($report->report_tableofcontent[0]->figures!='')
                                    <b>Figures:</b>
                                    {!! (isset($related_report->report_tableofcontent[0]->figures)) ? substr_count($related_report->report_tableofcontent[0]->figures,"<p>")  : '' !!}
                                    @endif
                                @endif
                            @endif
                        </p>
                        <a href="{!! url('report/'.$related_report->slug) !!}" class="title">{!! $related_report->name !!}</a>
                </div>
                <div class="card-footer bg-transparent btn-row border-top-0">
                        <a href="{!! url('buy-now/'.$related_report->slug) !!}" class="btn btn-buy">Buy Now</a>
                        <a href="{!! url('sample-request/'.$related_report->slug) !!}" class="btn btn-outline">Get Sample</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>