@extends('admin.layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <form id="frmAddUpdate" name="frmAddUpdate" action="{!! route('admin.systemsettings') !!}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{!! isset($settings->id) ? $settings->id : '' !!}">
                    <div class="col-lg-06">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Satisfied Customers</label>
                                        <input type="text" name="satisfied_customers" id="satisfied_customers" value="{!! old('satisfied_customers') ?? isset($settings->satisfied_customers) ? $settings->satisfied_customers : '' !!}" class="form-control">
                                        @error('satisfied_customers')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Customer Retention Rate</label>
                                        <input type="text" name="customer_retention_rate" id="customer_retention_rate" value="{!! old('customer_retention_rate') ?? isset($settings->customer_retention_rate) ? $settings->customer_retention_rate : '' !!}" class="form-control">
                                        @error('customer_retention_rate')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">                                        
                                        <label class="form-control-label">Years In Business</label>
                                        <input type="text" name="years_in_business" id="years_in_business" value="{!! old('years_in_business') ?? isset($settings->years_in_business) ? $settings->years_in_business : '' !!}" class="form-control">
                                        @error('years_in_business')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">                                
                                        <label class="form-control-label">Country Network</label>
                                        <input type="text" name="country_network" id="country_network" value="{!! old('country_network') ?? isset($settings->country_network) ? $settings->country_network : '' !!}" class="form-control">
                                        @error('country_network')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Team Members</label>
                                        <input type="text" name="team_members" id="team_members" value="{!! old('team_members') ?? isset($settings->team_members) ? $settings->team_members : '' !!}" class="form-control">
                                        @error('team_members')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Years Of Team Experience</label>                                        
                                        <input type="text" name="years_of_team_experience" id="years_of_team_experience" value="{!! old('years_of_team_experience') ?? isset($settings->years_of_team_experience) ? $settings->years_of_team_experience : '' !!}" class="form-control">
                                        @error('years_of_team_experience')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-control-label">Forecast Year</label>                                        
                                        <input type="text" name="forecast_year" id="forecast_year" value="{!! old('forecast_year') ?? isset($settings->forecast_year) ? $settings->forecast_year : '' !!}" class="form-control">
                                        @error('forecast_year')
                                        <span class="error" role="alert">{!! $message !!}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="line"> </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="location.href='{!! route('admin.dashboard') !!}';">Cancel</button>
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
@section('js')
<script src="{!! asset('assets/backend/js/pages/setting.js') !!}"></script>
@stop
@endsection
