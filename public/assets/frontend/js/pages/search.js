$(document).ready(function () {
    var page = 1;

    var isLoading = false;
    load_more_data(page,'reportsTab'); //initial content load
    localStorage.setItem("SearchTab", "reportsTab");

    $("#tab_1").on('click', function () {
        localStorage.setItem("SearchTab", "reportsTab");
        page = 1;
        load_more_data(page,'reportsTab');
    });

    $("#tab_2").on('click', function () {
        localStorage.setItem("SearchTab", "insightsTab");
        page = 1;
        load_more_data(page,'insightsTab');
    });

    $("#tab_3").on('click', function () {
        localStorage.setItem("SearchTab", "caseStudiesTab");
        page = 1;
        load_more_data(page,'caseStudiesTab');
    });

    $(window).scroll(function () { //detect page scroll
        if (Math.ceil($(window).scrollTop() + $(window).height()) >= $(document).height()-150) { //if user scrolled from top to bottom of the page
            if (isLoading) return
            page++;
            console.log(localStorage.getItem("SearchTab"));
            load_more_data(page,localStorage.getItem("SearchTab"));
        }
    });

    function load_more_data(page,type) {
        isLoading = true;
        var keyword = $("#hdnkeyword").val();
        var targetdiv = ''; 

        callURL = baseUrl + 'searchPageList?searchtxt=' + keyword + "&type=" + type;
        targetdiv = type;
                
        $.ajax({
            url: callURL + "&page=" + page,
            type: "get",
            datatype: "html",
            beforeSend: function () {
            }
        })
            .done(function (data) {
                if (data.length == 0) {
                    if (page == '1') $('#'+targetdiv).html("<p class='no-data-text'>No Data Found</p>");
                    return;
                }
                else {
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
                //alert('No response from server '+thrownError);
                isLoading = false;
            });
    }

});