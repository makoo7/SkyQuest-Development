
// insights-hero
$(document).ready(function() {
   fetch_data('insights',1);   
});

// for pagination
function fetch_data(module,page) {
   var container = "#insights_list";
   $.ajax({
       type: "POST",
       data: $('#searchFrm').serialize(),
       headers: {
           'X-CSRF-TOKEN': _token
       },
       url: baseUrl + module + "/ajax?page=" + page,
       success: function (data) {
           $(container).html(data);
       },
       error: function (data, e) {
           toastr.error("Something went wrong. Please try again later.");
       }
   });
}

// for pagination
$(document).on('click', '#insights_nav a', function (event) {
   event.preventDefault();
   var page = $(this).attr('href').split('page=')[1];
   $("#page").val(page);
   fetch_data('insights',page);
   window.scrollTo(0, 0);
});
