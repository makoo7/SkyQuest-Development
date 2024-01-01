<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8" />
<meta name="csrf-token" content="{!! csrf_token() !!}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset("css/slide.css") }}">
<link rel="stylesheet" href="{{ asset("css/index.css") }}">
<style class="shared-css" type="text/css" >
    .s1_1{font-family:DejaVuSans_mv;color:#000;}
    @font-face {
        font-family: DejaVuSans-Bold_n4;
        src: url({{ asset("assets/frontend/slide/fonts/DejaVuSans-Bold_n4.woff") }}) format("woff");
    }

    @font-face {
        font-family: DejaVuSans_mv;
        src: url({{ asset("assets/frontend/slide/fonts/DejaVuSans_mv.woff") }}) format("woff");
    }
</style>
</head>

<body style="margin: 0;">

{{-- Header Start --}}
    @include('front.sample-report-page.header.index')
{{-- Header Start --}}

{{-- Content --}}
    @if($response['frame'] == "intro")
        @include('front.sample-report-page.pages.intro')
    @elseif($response['frame'] == "dummy-content")
        @if(app('request')->input('page') == 2)
            @include('front.sample-report-page.pages.dummy-content-page-2')
        @elseif(app('request')->input('page') == 3)
            @include('front.sample-report-page.pages.dummy-content-page-3')
        @endif
    @elseif($response['frame'] == "frame")
        @include('front.sample-report-page.pages.frame')
    @elseif($response['frame'] == "content")
        @include('front.sample-report-page.pages.content')
    @elseif($response['frame'] == "flowchart-10")
        @include('front.sample-report-page.pages.flowchart-10')
    @elseif($response['frame'] == "page-13")
        @include('front.sample-report-page.pages.page-13')
    @elseif($response['frame'] == "page-15")
        @include('front.sample-report-page.pages.page-15')
    @elseif($response['frame'] == "page-16")
        @include('front.sample-report-page.pages.page-16')
    @elseif($response['frame'] == "page-17")
        @include('front.sample-report-page.pages.page-17')
    @elseif($response['frame'] == "page-20")
        @include('front.sample-report-page.pages.page-20')
    @elseif($response['frame'] == "page-21")
        @include('front.sample-report-page.pages.page-21')
    @elseif($response['frame'] == "page-22")
        @include('front.sample-report-page.pages.page-22')
    @endif
    {{-- @include('front.sample-report-page.pages.content') --}}
    {{-- @include('front.sample-report-page.pages.thankyou') --}}
{{-- Content --}}

{{-- Footer --}}
    {{-- @include('front.sample-report-page.footer.index') --}}
{{-- Footer --}}

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function AddMinutesToDate(date, minutes) {
    return new Date(date.getTime() + minutes * 60000);
}
function DateFormat(date){
  var days = date.getDate();
  var year = date.getFullYear();
  var month = (date.getMonth()+1);
  var hours = date.getHours();
  var minutes = date.getMinutes();
  minutes = minutes < 10 ? '0' + minutes : minutes;
  var strTime = days + '/' + month + '/' + year + '/ '+hours + ':' + minutes;
  return strTime;
}
$(document).on('click', '#btn-next', function(){
    let obj = $(this).attr('data-href');
    let user = "{{ base64_encode($user) }}";
    let report = "{{ base64_encode($report) }}";
    let sampleId = "{{ base64_encode($sampleId) }}";
    let page = "{{ $page }}";
    let startTime = new Date();
    let endTime = AddMinutesToDate(startTime,5);
    let data = JSON.stringify({user: user, report: report, sampleId: sampleId, page:page, startTime:startTime, endTime:endTime});
    $.ajax({
        url: "{{ route('sample-report-logs-store') }}",
        contentType: "application/json",
        dataType: "json",
        type: "POST",
        data: data,
        processData: false,
        success: function (data){
            alert('logs added successfully');
            window.location.href = obj;
        },
        error: function(error){}
    });
});    
</script>
</body>
</html>
