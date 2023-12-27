@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! route('admin.report.store') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" name="name" id="name" value="{!! old('name') !!}" class="form-control">
                                        @error('name')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Report Slug</label>
                                        <input type="text" name="slug" id="slug" value="{!! old('slug') !!}" class="form-control">
                                        @error('slug')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-6 mb-3">
                                        <label for="fileInput" class="form-control-label">Image</label>
                                        <div class="d-md-flex">
                                            <!-- <div class="form-inline form-fileUpload">
                                                <div class="fileUpload btn btn-primary">
                                                    <span>Browse</span>
                                                    <input id="image" name="image" type="file" class="upload" accept="image/*">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <span class="uploadFile form-control"></span>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <input type="file" class="form-control-file upload" id="image" name="image" accept="image/*">
                                            </div>
                                            <div class="show-image imageRounded ml-4">
                                                <img src="" class="" id='image_preview'>
                                             </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Image Alt</label>
                                        <input type="text" name="image_alt" id="image_alt" value="{!! old('image_alt') !!}" class="form-control">
                                        @error('image_alt')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Country</label>
                                        <select name="country" id="country" class="form-control">
                                            <option value="Global">Global</option>
                                            <option value="Regional">Regional</option>
                                            <option value="Country">Country</option>
                                        </select>
                                        @error('country')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">S/C</label>
                                        <select name="s_c" id="s_c" class="form-control">
                                            <option value="">Select S/C</option>
                                            <option value="Syndicate" >Syndicate</option>
                                            <option value="Market Intelligence" >Market Intelligence</option>
                                            <option value="Competitor Intelligence">Competitor Intelligence</option>
                                            <option value="Supplier Intelligence">Supplier Intelligence</option>
                                            <option value="Open Innovation">Open Innovation</option>
                                        </select>
                                        @error('s_c')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Product ID</label>
                                        <input type="text" name="product_id" id="product_id" readonly value="{!! old('product_id') !!}" class="form-control">
                                        @error('product_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Report Type</label>
                                        <input type="text" name="report_type" id="report_type" readonly value="{!! old('report_type')  !!}" class="form-control">
                                        @error('report_type')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>  
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Download</label>
                                        <input type="text" name="download" id="download" value="{!! old('download') !!}" class="form-control">
                                        @error('download')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>                                       
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Pages</label>
                                        <input type="text" name="pages" id="pages" value="{!! old('pages') !!}" class="form-control">
                                        @error('pages')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Meta Title</label>
                                        <input type="text" name="meta_title" id="meta_title" value="{!! old('meta_title') !!}" class="form-control">
                                        @error('meta_title')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Meta Description</label>
                                        <input type="text" name="meta_description" id="meta_description" value="{!! old('meta_description') !!}" class="form-control">
                                        @error('meta_description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>       
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Sectors</label>
                                        <select name="sector_id" id="sector_id" class="form-control">
                                            <option value="">Select Sector</option>
                                            @foreach ($sectors as $sector)
                                                <option value="{{$sector->id}}">{{$sector->title}}</option>
                                            @endforeach
                                        </select>
                                        @error('sector_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Industry Group</label>
                                        <select name="industry_group_id" id="industry_group_id" class="form-control">
                                            <option value="">Select Industry Group</option>
                                        </select>
                                        @error('industry_group_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Industry</label>
                                        <select name="industry_id" id="industry_id" class="form-control">
                                            <option value="">Select Industry</option>
                                        </select>
                                        @error('industry_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Sub Industry</label>
                                        <select name="sub_industry_id" id="sub_industry_id" class="form-control">
                                            <option value="">Sub Sub Industry</option>
                                        </select>
                                        @error('sub_industry_id')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-control-label">Free sample report link</label>
                                        <input type="text" name="free_sample_report_link" id="free_sample_report_link" value="{!! old('free_sample_report_link') !!}" class="form-control">
                                        @error('free_sample_report_link')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-12 mb-4">
                                        <label class="form-control-label font-weight-bold">
                                            Segments
                                            <a href="javascript:void(0);" class="action-btn addSegment ms-2" title="Add" role="button" aria-pressed="true">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-12 mb-3" id="segment-section">
                                        <div class="row">
                                            <?php $segcounter = count($reportSegments ?? []);?>
                                            @foreach ($reportSegments ?? [] as $segment)
                                            <div class="col-sm-12" id="segment_all_{{$segment->id}}">
                                                <div class="col-sm-12 mb-3">
                                                    <label class="form-control-label">Segment</label>
                                                    <div class="d-flex align-items-center segment-item mb-4">
                                                        <input type="text"  name="segment_name[]" id="segment_name_{{$segment->id}}" value="{!! old('segment_name_') ?? $segment->name !!}" class="form-control">
                                                        <input type="hidden" name="report_segment_id[]" value="{{$segment->id}}"/>
                                                        <input type="hidden" name="report_segment_counter[]" value="0">
                                                        <a href="javascript:void(0);" data-id="{{$segment->id}}" class="btn action-btn btn-outline-danger deleteSegment" title="Delete" role="button" aria-pressed="true" style="width: 40px;">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-12 mb-3 pr-0 mt-2" id="sub_segment_{{$segment->id}}">
                                                        <label class="form-control-label">
                                                            Sub-segment
                                                            <a href="javascript:void(0);" data-segment="{{$segment->id}}" class="action-btn addSubSegment" title="Add" role="button" aria-pressed="true">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </a>
                                                        </label>
                                                        <?php $subsegments = explode(',',$segment->value);
                                                        $counter = 1;?>
                                                            <div id="sub_segment_box_{{$segment->id}}">
                                                                @foreach ($subsegments as $subsegment)
                                                                <div class="d-flex align-items-center mb-1 sub-segment-item">
                                                                    <input type="text" name="sub_segment_name[{{$segment->id}}][]" id="sub_segment_name_{{$segment->id}}_{{$counter}}" value="{!! old('segment_name_') ?? $subsegment !!}" class="form-control">
                                                                    <a href="javascript:void(0);" data-segment="{{$segment->id}}" id="sub_segment_delete_{{$segment->id}}_{{$counter}}" data-id="{{$counter++}}" class="btn action-btn btn-outline-danger deleteSubSegment" title="Delete" role="button" aria-pressed="true" style="width: 40px;">
                                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                            <input type="hidden" id="segment_counter_{{$segment->id}}" value="{{$counter}}"/>
                                                        </div>
                                                    </div>     
                                                </div> 
                                            </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" id="main_segment_counter" value="{{++$segcounter}}"/>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label class="form-control-label font-weight-bold">Report Type</label>
                                    </div> 
                                    
                                    @foreach ($reportPricing ?? [] as $pricing)
                                    <div class="col-sm-12 mb-3 d-flex">
                                    <div class="col-sm-4 mb-3 ">
                                        <label class="form-control-label">License Type</label>
                                        <input type="text"  name="license_type[]" id="license_type_{{$pricing->id}}" value="{!! old('license_type_') ?? $pricing->license_type !!}" class="form-control">
                                        @error('license_type')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">File Type</label>
                                        <input type="text"  name="file_type[]" id="file_type_{{$pricing->id}}" value="{!! old('file_type_') ?? $pricing->file_type !!}" class="form-control">
                                        @error('file_type')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div> 
                                    <div class="col-sm-4 mb-3">
                                        <label class="form-control-label">Price</label>
                                        <input type="text"  name="price[]" id="price_{{$pricing->id}}" value="{!! old('price_') ?? $pricing->price !!}" class="form-control">
                                        @error('price')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                        <input type="hidden" name="report_pricing_id[]" value="{{$pricing->id}}"/>
                                    </div> 
                                    </div>    
                                    @endforeach
                                   
                                </div>
                                {{-- @if($report->report_type=='Dynamic') --}}
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Description</label>
                                        <textarea name="description" id="description" class="form-control tinymce-editor">{!! old('description') !!}</textarea>
                                        @error('description')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>                                
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Table of Content</label>
                                        <textarea name="toc" id="toc" class="form-control tinymce-editor"></textarea>
                                        @error('toc')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- @endif --}}
                                {{-- @if(($report->report_type=='SD') || ($report->report_type=='Dynamic')) --}}
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">What's included</label>
                                        <textarea name="whats_included" id="whats_included" class="form-control tinymce-editor">{!! old('whats_included') !!}</textarea>
                                        @error('whats_included')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Methodologies</label>
                                        <textarea name="methodologies" id="methodologies" class="form-control tinymce-editor">{!! old('methodologies') !!}</textarea>
                                        @error('methodologies')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Analyst Support</label>
                                        <textarea name="analyst_support" id="analyst_support" class="form-control tinymce-editor">{!! old('analyst_support') !!}</textarea>
                                        @error('analyst_support')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- @endif --}}
                                {{-- @if(($report->report_type=='SD') || ($report->report_type=='Upcoming')) --}}
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label class="form-control-label font-weight-bold">Market Insights</label>
                                            <textarea name="market_insights" id="market_insights" class="form-control tinymce-editor">{!! old('market_insights') !!}</textarea>
                                            @error('market_insights')
                                            <span class="error" role="alert">{!! $message !!}</span>
                                            @enderror
                                        </div>
                                    </div>
                                {{-- @endif --}}
                                {{-- @if($report->report_type=='SD') --}}
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Segmental Analysis</label>
                                        <textarea name="segmental_analysis" id="segmental_analysis" class="form-control tinymce-editor">{!! old('segmental_analysis') !!}</textarea>
                                        @error('segmental_analysis')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Regional Insights</label>
                                        <textarea name="regional_insights" id="regional_insights" class="form-control tinymce-editor">{!! old('regional_insights') !!}</textarea>
                                        @error('regional_insights')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Market Dynamics</label>
                                        <textarea name="market_dynamics" id="market_dynamics" class="form-control tinymce-editor">{!! old('market_dynamics') !!}</textarea>
                                        @error('market_dynamics')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Competitive Landscape</label>
                                        <textarea name="competitive_landscape" id="competitive_landscape" class="form-control tinymce-editor">{!! old('competitive_landscape') !!}</textarea>
                                        @error('competitive_landscape')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Key Market Trends</label>
                                        <textarea name="key_market_trends" id="key_market_trends" class="form-control tinymce-editor">{!! old('key_market_trends') !!}</textarea>
                                        @error('key_market_trends')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">SkyQuest Analysis</label>
                                        <textarea name="skyQuest_analysis" id="skyQuest_analysis" class="form-control tinymce-editor">{!! old('skyQuest_analysis') !!}</textarea>
                                        @error('skyQuest_analysis')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- @endif --}}
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="form-control-label font-weight-bold">Schema</label>
                                        <textarea name="schema" id="schema" class="form-control">{!! old('schema') !!}</textarea>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 mb-3 ">
                                        <label class="form-control-label font-weight-bold blue">
                                            Faq Questions
                                            <a href="javascript:void(0);" class="action-btn addFaq" title="Add" role="button" aria-pressed="true">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </a>
                                        </label>
                                        
                                        <div class="accordion" id="accordion">
                                        <?php $faqcounter = 1000000000;
                                        $faqsr = 0;
                                        ?>
                                            @foreach ($reportFaqs ?? [] as $faq)
                                            <div id="heading{{$faq->id}}">
                                                <a href="#answer{{$faq->id}}" class="btn que-btn" data-toggle="collapse" aria-expanded="false" aria-controls="#answer{{$faq->id}}">
                                                    {{$faq->faq_question}}
                                                    <div class="ml-auto">
                                                        @if($faqsr >= 5)
                                                        <span data-id="{{$faq->id}}" class="btn action-btn text-danger deleteFaq" title="Delete" role="button" aria-pressed="true">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </span>
                                                        @endif
                                                        <span class="toggle-plus-minus"></span>
                                                    </div>
                                                </a>
                                            </div>   
                                            <div id="answer{{$faq->id}}" aria-labelledby="heading{{$faq->id}}" data-parent="#accordion" class="collapse">
                                                <div class="col-sm-12 mb-3">
                                                    <label class="form-control-label">Question</label>
                                                        <input type="text"  name="faq_question[]" id="faq_question_{{$faq->id}}" data-id="{{$faq->id}}" value="{!! old('faq_question_') ?? $faq->faq_question !!}" class="form-control">
                                                        @error('faq_question')
                                                        <span class="error" role="alert">{!! $message !!}</span>
                                                        @enderror
                                                </div> 
                                                <div class="col-sm-12 mb-3" >
                                                    <label class="form-control-label">Answer</label>
                                                    <textarea name="faq_answer[]" id="faq_answer_{{$faq->id}}" class="form-control tinymce-editor faq_answer" data-id="{{$faq->id}}">{!! old('faq_answer_') ?? $faq->faq_answer !!}</textarea>
                                                    @error('price')
                                                    <span class="error" role="alert">{!! $message !!}</span>
                                                    @enderror
                                                    <input type="hidden" name="report_faq_id[]" value="{{$faq->id}}"/>
                                                </div>
                                            </div>
                                            <?php $faqsr++;?>
                                            @endforeach
                                        </div>
                                        <div id="additionalFaq">
                                        </div>
                                        <input type="hidden" id="faq_counter" value="{{$faqcounter}}"/>
                                    </div> 
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label font-weight-bold">Status</label>
                                        <div class="radio-wrap">
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_yes" class="form-control checkboxinput" name="is_active" value="1" !!} checked>Active
                                                <h1></h1>
                                            </div>
                                            <div class="radiobtn">
                                                <input type="radio" id="is_active_no" class="form-control checkboxinput" name="is_active" value="0" >Inactive
                                                <h1></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.report.index') !!}';">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script id="section-template" type="text/template">
    <div class="row">
        <div class="col-sm-12" id="segment_all_{segcounter}">
            <div class="col-sm-12 mb-3">
                <label class="form-control-label">Segment</label>
                <div class="d-flex align-items-center">
                    <input type="text"  name="segment_name[]" id="segment_name_{segcounter}" value="" class="form-control">
                    <input type="hidden" name="report_segment_id[]" value="0">
                    <input type="hidden" name="report_segment_counter[]" value="{segcounter}">
                    <a href="javascript:void(0);" data-id="{segcounter}" class="btn action-btn btn-outline-danger deleteSegmentStatic" title="Delete" role="button" aria-pressed="true" style="width:40px;">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="col-sm-12 mb-3 pr-0 mt-2" id="sub_segment_{segcounter}">
                    <div class="border-bottom-pb-2">
                        <label class="form-control-label">Sub-segment</label>
                        <a href="javascript:void(0);" data-segment="{segcounter}" data-id="{subsegcounter}" class="btn action-btn addSubSegment" title="Add" role="button" aria-pressed="true" style="width:40px;">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div d id="sub_segment_box_{segcounter}">
                        <div class="d-flex align-items-center mb-1">
                            <input type="text" name="sub_segment_name[{segcounter}][]" id="sub_segment_name_{segcounter}_{subsegcounter}" value="" class="form-control">
                            {hiddenCounter}
                            <a href="javascript:void(0);" data-segment="{segcounter}" id="sub_segment_delete_{segcounter}_{subsegcounter}" data-id="{subsegcounter}" class="btn action-btn btn-outline-danger deleteSubSegment" title="Delete" role="button" aria-pressed="true" style="width:40px;"> 
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>     
            </div> 
        </div>
    </div>
</script>
<script id="sub-section-template" type="text/template">
    <div class="d-flex align-items-center mb-1">
        <input type="text"  name="sub_segment_name[{segment_id}][]" id="sub_segment_name_{segment_id}_{subsegcounter}" value="" class="form-control">    
        <a href="javascript:void(0);" data-segment="{segment_id}" id="sub_segment_delete_{segment_id}_{subsegcounter}" data-id="{subsegcounter}" class="btn action-btn btn-outline-danger deleteSubSegment" title="Delete" role="button" aria-pressed="true" style="width:40px;">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </a>
    </div>
</script>
<script id="faq-template" type="text/template">
    <div class="row mt-2" id="faq_block_{faq_counter}">
        <div class="col-sm-12">
            <div class="col border p-2 bg-light rounded">
                <div class="mb-3">
                    <label class="form-control-label d-flex align-items-center justify-content-between">
                        Question 
                        <a href="javascript:void(0);" data-id="{faq_counter}" class="btn btn-sm btn-danger action-btn deleteStaticFaq" title="Delete" role="button" aria-pressed="true" style="width:40px;">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </label>
                    <input type="text"  name="faq_question[]" id="faq_question_{faq_counter}" value="" class="form-control faq_question requirednew" data-id="{faq_counter}">
                </div>
                <div class="mb-3">
                    <label class="form-control-label">Answer</label>
                    <textarea name="faq_answer[]" id="faq_answer_{faq_counter}" class="form-control tinymce-editor faq_answer requirednew" data-id="{faq_counter}"></textarea>
                    <input type="hidden" name="report_faq_id[]" value="0"/>
                </div>
            </div>
        </div>
    </div>
</script>
@section('js')
<script src="{!! asset('assets/backend/js/pages/report.js') !!}"></script>
<script>
    $(document).ready(function () {
        var sectorId = null;
        var industryGroupId = null;
        var industryId = null;        
        var subIndustryId = null; 

        // set industry_group
        $.ajax({
            type: "POST",
            data: { entity_id: sectorId, type: 'industry_group', selected: industryGroupId },
            headers: {
                'X-CSRF-TOKEN': _token
            },
            url: baseUrl + "report/getIndustryData",
            success: function (data) {
                //console.log(data);
                $("#industry_group_id").html(data);
            },
            error: function (data, e) {
                toastr.error("Something went wrong. Please try again later.");
            }
        });

        // set industry
        $.ajax({
            type: "POST",
            data: { entity_id: industryGroupId, type: 'industry', selected: industryId },
            headers: {
                'X-CSRF-TOKEN': _token
            },
            url: baseUrl + "report/getIndustryData",
            success: function (data) {
                //console.log(data);
                $("#industry_id").html(data);
            },
            error: function (data, e) {
                toastr.error("Something went wrong. Please try again later.");
            }
        });

        // set sub_industry
        $.ajax({
            type: "POST",
            data: { entity_id: industryId, type: 'sub_industry', selected: subIndustryId },
            headers: {
                'X-CSRF-TOKEN': _token
            },
            url: baseUrl + "report/getIndustryData",
            success: function (data) {
                //console.log(data);
                $("#sub_industry_id").html(data);
            },
            error: function (data, e) {
                toastr.error("Something went wrong. Please try again later.");
            }
        });
    });
</script>
@stop
@endsection
