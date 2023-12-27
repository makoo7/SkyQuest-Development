$(document).ready(function () {
    'use strict';
    $("#frmLogin").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        },
        messages: {
            password: {
                required: "Please provide a password"
            },
            email: "Please enter a valid email address"
        }
    });

    $("#frmChangePassword").validate({
        errorElement: 'span',
        rules: {
            current_password: {
                required: true,
                minlength: 8
            },
            new_password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#new_password"
            }
        },
        messages: {
            current_password: {
                required: "Please provide a current password",
                minlength: "Your password must be at least 8 characters long"
            },
            new_password: {
                required: "Please provide a new password",
                minlength: "Your password must be at least 8 characters long"
            },
            password_confirmation: {
                required: "Please provide a confirm password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above"
            }
        }
    });

    $("#frmForgotPassword").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: "Please enter a valid email address"
        }
    });

    $("#frmResetPassword").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long"
            },
            password_confirmation: {
                required: "Please provide a confirm password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above"
            },
            email: "Please enter a valid email address"
        }
    });


    $("#frmMyProfile").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true
            },
            user_name: {
                required: true
            },
            image: {
                // extension : "jpeg|png|gif",
                filesize: 10
            },
            phone: { 
                maxlength: 12
            },
        },
        messages: {
            user_name: { required: "Please provide a user name" },
            email: { required: "Please enter a valid email address" },
        }
    });

});

function deleteAdminAvatar(id) {
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
                    url: baseUrl + 'deleteImage',
                    dataType: 'json',
                    data: { 'id': id },
                    success: function (data) {
                        // window.location = baseUrl + 'profile';
                        $(".loader").hide();
                        $('#image_preview').attr('src',window.location.origin+'/assets/backend/images/default-avatar.png');
                        $("#profile_pic").attr('src', window.location.origin + '/assets/backend/images/default-avatar.png');
                        toastr.success("Profile picture deleted successfully.");
                        $(".deleteMetaImg").hide();
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
