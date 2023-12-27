$(document).ready(function () {
    'use strict';
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            name: { required: true },
            description: { required: true },
            image: { required: function (element) {
                return $("#image_data").val()=='';
            } },
            location: { required: true },
            slug: { required: true },
            
        },
        messages: {
            name: { required: "Please provide a name."},
            description: { required: "Please provide a description."},
            image: { required: "Please upload an image."},
            location: { required: "Please provide a location."},
            slug: { required: "Please provide a slug."},
        },
        errorPlacement: function(label, element) {
            // position error label after generated textarea
            if (element.is("textarea.tinymce-editor")) {
                label.insertAfter(element.next());
            } else {
                label.insertAfter(element)
            }
        }
    });
});
    
$(document).on('click', '.sortColumn', function () {
    var column = $(this).data('column');
    var order =$('#sort_order').val();
    order = (order == 'asc')?'desc':'asc';
    $('#sort_by').val(column);
    $('#sort_order').val(order);
    fetch_data('casestudy',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('casestudy',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('casestudy',1);
});

$(document).on('click', '#casestudy_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('casestudy',page);
});

function deleteCaseStudyImage(id) {
    let eventclose = true;
    $.prompt(' ', {
        title: "Do you really want to delete this Image?",
        buttons: { "Yes": true, "No": false },
        submit: function (e, v, m, f) {
            if (v) {
                eventclose = false;
                $(".loader").show();
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': _token
                    },
                    url: baseUrl + 'casestudy/deleteImage',
                    dataType: 'json',
                    data: { 'id': id },
                    success: function (data) {
                        $(".loader").hide();
                        if (data.success == "1") {                            
                            toastr.success(data.message);
                            $('#image_preview').attr('src', window.location.origin + '/assets/backend/images/no-image.png');
                            $("#image_data").val("");
                            $(".deleteMetaImg").hide();
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function (data, e) {
                        $(".loader").hide();
                        toastr.error("Something went wrong. Please try again later.");
                    }
                });
            }
        },
        close: function(event, ui) {
            if(!eventclose) {
                event.preventDefault();
                return false;
            }
        }
    });
}