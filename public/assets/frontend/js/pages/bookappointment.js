$(document).ready(function () {

    $('#appointment_time').datetimepicker({
        mask:'39/19/9999, 29:59',
        minDate:0, // disable past date
        format:'d/m/Y, H:i',
        formatDate:'d/m/Y, H:i',        
    }).on('change', function() {
        $(this).valid();  // triggers the validation test
    });

    jQuery.validator.addMethod("maskformat", function (value, element) {
        //console.log(value);
        if (value=='__/__/____, __:__') {
            return false;
        } else {
            return true;
        };
    }, "Please provide an appointment date and time");

    // book appointment form validation
    $("#frmbookappointment").validate({
        errorElement: 'span',
        ignore: ".ignore",
        rules: {
            appointment_time: {
                required: true,
                maskformat: true,
            },
            name: {
                required: true,
                minlength: 3,
                validateTextField: true
            },
            phone: {
                required: true,
                numericplus: true,
                minlength: 8,
                maxlength: 12
            },
            email: {
                required: true,
                email: true,
                validateEmail: true
            },
            company_name: {
                required: true,
                minlength: 3,
                validateTextField: true
            },
            hiddenRecaptcha: {
                required: function () {
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        },
        messages: {
            appointment_time: {
                required: "Please provide an appointment date and time",
            },
            name: {
                required: "Please provide a name",
                minlength: "Please insert at least 3 letters",
                validateTextField: "Please enter a valid name",
            },
            phone: {
                required: "Please provide a phone",
                numericplus: "Please enter a valid phone",
                minlength: "Please keep number length between 8 to 12",
                maxlength: "Please keep number length between 8 to 12",
            },
            email: {
                required: "Please provide an email address",
                email: "Please enter a valid email address",
            },
            company_name: {
                required: "Please provide a company name",
                minlength: "Please insert at least 3 letters",
                validateTextField: "Please enter a valid company name",
            },
            hiddenRecaptcha : {
                required: "Google captcha is required.",
            },
        },
        submitHandler: function (form) {            
            showLoader();
            form.submit();
            $('.page-loader').show();
        }
    });

    // Book appointment form validation and close event
    $('#book-appointment').on('hide.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $("#frmbookappointment").find(".error").text("");
        $("#frmbookappointment").find(".invalid-feedback").children("strong").text("");
    });    

});