$(document).ready(function () {
    var hdnslug = $("#hdnslug").val();
    if (hdnslug != '') {
        $('#sectorlist .dropdown-item').each(function () {
            var sel = $(this).data("selection");
            if (sel != '' && sel.trim() == 'selected') {
                var selText = $(this).text();
                $("#dropdownMenuButton1").html(selText);
            }
        });
    }
    
    var page = 1; //track user scroll as page number, right now page number is 1
    localStorage.setItem("Tab", "1");
    $("#hdnupcoming").val('0'); 
    load_more(page); //initial content load
    var isLoading = false;

    $("#tab_1").on('click', function () {
        localStorage.setItem("Tab", "1");
        $("#orderby").show();
        page = 1; //track user scroll as page number, right now page number is 1   
        $("#hdnupcoming").val('0'); 
        load_more(page); //initial content load
    });

    $("#tab_2").on('click', function () {
        localStorage.setItem("Tab", "2");
        $("#orderby").hide();
        page = 1; //track user scroll as page number, right now page number is 1 
        $("#hdnupcoming").val('1');
        load_more(page,'1');
    });
        
    if(localStorage.getItem("Tab")=='1'){
        $('#reportTabs a[href="#tab_default_1"]').tab('show');
        localStorage.setItem("Tab", "1");
        $("#orderby").show();
        page = 1; //track user scroll as page number, right now page number is 1   
        $("#hdnupcoming").val('0'); 
        load_more(page); //initial content load
    } 
    if(localStorage.getItem("Tab")=='2'){
        $('#reportTabs a[href="#tab_default_2"]').tab('show');
        localStorage.setItem("Tab", "2");
        $("#orderby").hide();
        page = 1; //track user scroll as page number, right now page number is 1 
        $("#hdnupcoming").val('1');
        load_more(page,'1');
    }    
    
    $(window).scroll(function () { //detect page scroll
        if (Math.ceil($(window).scrollTop() + $(window).height()) >= $(document).height()-150) { //if user scrolled from top to bottom of the page
            if (isLoading) return

            page++; //page number increment            
            if($("#hdnupcoming").val()=='1'){
                load_more(page,'1'); //load content
            }else{                
                load_more(page); //load content
            }
        }
    });

    function load_more(page,isupcoming='') {
        isLoading = true;
        var hdnslug = $("#hdnslug").val();
        var keyword = $("#keyword").val();
        var orderby = $("#orderby").val();
        var isReport = (hdnslug != '') ? '' : '1';

        if (isReport) {
            callURL = baseUrl + 'reports?keyword=' + keyword + '&orderby=' + orderby;
        }
        else {
            callURL = baseUrl + 'industries/' + hdnslug + '?keyword=' + keyword + '&orderby=' + orderby;
        }
        
        var targetdiv = 'tab_default_1';
        if(isupcoming=='1'){
            callURL = callURL + '&upcoming=1';
            targetdiv = 'tab_default_2';
        }

        $.ajax({
            url: callURL + "&page=" + page,
            type: "get",
            datatype: "html",
            beforeSend: function () {
            }
        })
            .done(function (data) {
                if (data.length == 0) {
                    if (page == '1') $('#'+targetdiv).html("<p class='no-data-text'>No Reports Found</p>");
                    return;
                }
                else {
                    //$('#load_more_button').remove();
                    if (page == '1'){
                        $('#'+targetdiv).html(data);
                    }
                    else{
                        $('#'+targetdiv).append(data);
                    }
                }
                isLoading = false;
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                //alert('No response from server');
                isLoading = false;
            });
    }

    function getReportData(slug = '', keyword = '', orderby = '', isReport = '', upcoming = '') {
        isLoading = true;
        var callURL = '';
        if (isReport) {
            callURL = baseUrl + 'reports?page=1&keyword=' + keyword + '&orderby=' + orderby;
        }
        else {
            callURL = baseUrl + 'industries/' + slug + '?page=1&keyword=' + keyword + '&orderby=' + orderby;
        }

        if(localStorage.getItem("Tab")=='1')    var targetdiv = 'tab_default_1';
        if(localStorage.getItem("Tab")=='2')    var targetdiv = 'tab_default_2';

        if(upcoming=='1'){
            callURL = callURL + '&upcoming=1';
        }

        $.ajax({
            url: callURL,
            type: 'get',
            datatype: 'html',
            headers: {
                'X-CSRF-TOKEN': _token
            }
        })
            .done(function (data) {
                if (data.length == 0) {
                    $('#'+targetdiv).html('<p class="no-data-text">No Reports Found</p>');
                    return;
                } else {
                    $('#'+targetdiv).html(data);
                }
                isLoading = false;
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                //alert('Something went wrong.');
                isLoading = false;
            });
    }

    $("#orderby").on('change', function () {
        var hdnslug = $("#hdnslug").val();
        var keyword = $("#keyword").val();
        var orderby = $("#orderby").val();
        var upcoming = $("#hdnupcoming").val();
        var isReport = (hdnslug != '') ? '' : '1';
        page = 1;
        getReportData(hdnslug, keyword, orderby, isReport, upcoming);
    })

    $('#frmreportsearch').on('submit', function (e) {
        e.preventDefault();
        var hdnslug = $("#hdnslug").val();
        var keyword = $("#keyword").val();
        var orderby = $("#orderby").val();
        var upcoming = $("#hdnupcoming").val();
        var isReport = (hdnslug != '') ? '' : '1';
        page = 1;
        getReportData(hdnslug, keyword, orderby, isReport, upcoming);
    });

});