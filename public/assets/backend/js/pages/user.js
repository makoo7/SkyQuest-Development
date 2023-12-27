$(document).ready(function () {
    'use strict';
    $("#frmAddUpdate").validate({
        errorElement: 'span',
        rules: {
            role_id: { required: true },
            user_name: { required: true },
            email: { required: true, email : true },
            password: { required: true }
        },
        messages: {
            role_id: { required: "Please select role."},
            user_name: { required: "Please provide a user name."},
            email: { required: "Please provide an email."},
            password: { required: "Please provide a password."},
        }
    });
});

$(document).on('click', '.sortColumn', function () {
    var column = $(this).data('column');
    var order =$('#sort_order').val();
    order = (order == 'asc')?'desc':'asc';
    $('#sort_by').val(column);
    $('#sort_order').val(order);
    fetch_data('user',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('user',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('user',1);
});

$(document).on('click', '#user_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('user',page);
});

function deleteUserAvatar(id) {
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
                    url: baseUrl + 'user/deleteImage',
                    dataType: 'json',
                    data: { 'id': id },
                    success: function (data) {
                        $(".loader").hide();
                        if (data.success == "1") {
                            $('#image_preview').attr('src', window.location.origin + '/assets/backend/images/default-avatar.png');
                            toastr.success(data.message);
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
