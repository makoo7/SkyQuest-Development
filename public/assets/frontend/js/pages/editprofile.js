$(document).ready(function() {    
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".file-upload").on('change', function(){
        readURL(this);
    });
    
    $(".upload-button").on('click', function() {
       $(".file-upload").click();
    });
});


jQuery.validator.addMethod("numericplus", function (value, element) {
    if(value!=''){
        if (/^[+]?[0-9]+$/i.test(value)) {
            return true;
        } else {
            return false;
        }
    } else { return true; }
}, "Numbers and plus only");

// sample a request form validation
$("#frmeditProfile").validate({
    errorElement: 'span',
    ignore: ".ignore",
    rules: {
        user_name: {
           required: true,
           minlength: 3,
           validateTextField: true
        },
        email: {
            required: true,
            email: true,
            validateEmail: true
        },
        phone: {
            numericplus: true,
            minlength: 8,
            maxlength: 12
        },
        company_name: {
            minlength: 3,
            maxlength: 150,
            validateTextField: true
        },
    },
    messages: {
        user_name: {
           required: "Please provide user name",
           minlength: "Please insert at least 3 letters",
           validateTextField: "Please enter a valid user name",
        },
        email: {
            required: "Please provide an email address",
            email: "Please enter a valid email address"
        },
        phone: {
            numericplus: "Please enter a valid phone",
            minlength: "Please keep number length between 8 to 12",
            maxlength: "Please keep number length between 8 to 12",
        },
        company_name: {
            minlength: "Please insert at least 3 letters",
            maxlength: "Please keep company name length upto 150 characters",
            validateTextField: "Please enter a valid company name",
        },
    },
    submitHandler: function (form) {
        showLoader();
        form.submit();
        $('.page-loader').show();
    }
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
                    url: baseUrl + 'userDeleteImage',
                    dataType: 'json',
                    data: { 'id': id },
                    success: function (data) {
                        $(".loader").hide();
                        if (data.success == "1") {
                            $('#image_preview').attr('src', window.location.origin + '/assets/frontend/images/default-avatar.png');
                            toastr.success(data.message);
                            $(".deleteImg").hide();
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