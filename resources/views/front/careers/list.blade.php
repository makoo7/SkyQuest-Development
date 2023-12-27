@extends('front.layouts.app')
@section('content')
@include("front.layouts.navigation")
<div class="skyquest">
    <div class="container">
    <div class="skyquest-inner">
        <h1>Careers</h1>
        <p>If innovativeness is in your schema and you have just decided to source a technology/innovation or an IP, we at SkyQuest can help you make your decision tangible.</p>
    </div>
    </div>
</div>
<div class="what-do-we cards-padding">
    <div class="container">
    <div class="whatdowe-inner">
        <p>Current Openings</p>        
        @if(count($careersData)>0)
        <div class="card-inner">
            @foreach ($careersData as $key => $careers)
            @php $key++ @endphp
            <div class="card-main">
                <div class="cards">
                    <p>{!!$careers->position!!}</p>
                    <p>{!!$careers->exp_range!!} - {!!$careers->location!!}</p>
                </div>
                <div class="cards">
                    <a href="{!! url('careers/'.$department->slug.'/'.$careers->slug) !!}">APPLY</a>
                </div>
            </div>
            @if($key%2 == 0 && $key < $careersData->count())
            </div>
            <div class="card-inner">
            @endif
            @endforeach
        @else
        <div class="">
            No Openings Available
        </div>
        @endif
        </div>
    </div>
    </div>
</div>
@section('js')
<!-- js link -->
@stop
@endsection