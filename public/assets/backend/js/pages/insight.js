$(document).ready(function () {
    'use strict';   
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            name: { required: true },
            description: { required: true },
            slug: { required: true },
            image: { 
                required: function (element) {
                    return $("#image_data").val()=='';
                }
             },      
        },
        messages: {
            name: { required: "Please provide a name."},
            description: { required: "Please provide a description."},
            image: { required: "Please upload an image."},
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

$('#publish_date').datepicker({
    //dateFormat: 'dd/mm/yy'
    dateFormat: 'yy-mm-dd'
});

//$('#publish_date').mask("00/00/0000", {placeholder: 'dd/mm/yyyy' });
    
$(document).on('click', '.sortColumn', function () {
    var column = $(this).data('column');
    var order =$('#sort_order').val();
    order = (order == 'asc')?'desc':'asc';
    $('#sort_by').val(column);
    $('#sort_order').val(order);
    fetch_data('insight',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('insight',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('insight',1);
});

$(document).on('click', '#insight_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('insight',page);
});

function deleteInsightImage(id) {
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
                    url: baseUrl + 'insight/deleteImage',
                    dataType: 'json',
                    data: { 'id': id },
                    success: function (data) {
                        $(".loader").hide();
                        if (data.success == "1") {                            
                            toastr.success(data.message);
                            $('#image_preview').attr('src', window.location.origin + '/assets/backend/images/no-image.png');
                            $("#image_data").val("");
                            $("#deleteImg").hide();
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

function deleteWriterImage(id) {
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
                    url: baseUrl + 'insight/deleteWriterImage',
                    dataType: 'json',
                    data: { 'id': id },
                    success: function (data) {
                        $(".loader").hide();
                        if (data.success == "1") {                            
                            toastr.success(data.message);
                            $('#writer_image_preview').attr('src', window.location.origin + '/assets/backend/images/default-avatar.png');                            
                            $("#deleteWriterImg").hide();
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