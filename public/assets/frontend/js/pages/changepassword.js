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
    },
    submitHandler: function (form) {
        showLoader();
        form.submit();
        $('.page-loader').show();
    }
});