<script src="{!! asset('assets/backend/js/all.js') !!}"></script>
<script>
    var _token = $("input[name='_token']").val();
    var baseUrl = "{!! url('admin') !!}/";
    var baseImgUrl = "{!! url('assets/backend/images/') !!}/";
    var emsg = "";
    var ecls = "success";
    @if (session('message') || session('status'))
        emsg = "{!! @session('message').@session('status') !!}";
        @if (session('alert-class') && session('alert-class') == "error")
            ecls = "error";
        @endif
    @endif
</script>

<script src="{!! asset('assets/backend/js/common.js') !!}?v={!! time() !!}"></script>
@yield('js')