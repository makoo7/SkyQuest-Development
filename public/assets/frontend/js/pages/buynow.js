$(document).ready(function () {
    $("#phonecode").select2({
        placeholder: "Country Code*",
    })

    var country_text = $("#phonecode option:selected").text();
    if(country_text=='India (+91)'){
        $("#PaypalMethod").hide();
        $("#PaypalOption").prop("checked",false);
        $("#RazorpayOption").prop("checked",true);
    }else{
        $("#PaypalMethod").show();
    }
    
    $("#phonecode").on('change', function() {
        var country_text = $("#phonecode option:selected").text();
        if(country_text=='India (+91)'){
            $("#PaypalMethod").hide();
            $("#PaypalOption").prop("checked",false);
            $("#RazorpayOption").prop("checked",true);
        }else{
            $("#PaypalMethod").show();
        }    
    });
});

// sample a request form validation
$("#frmbuynow").validate({
    errorElement: 'span',
    ignore: ".ignore",
    rules: {
        name: {
            required: true,
            minlength: 3,
            maxlength: 100,
            validateTextField: true
        },
        email: {
            required: true,
            email: true,
            maxlength: 150,
            validateEmail: true
        },
        phone: {
            required: true,
            numericplus: true,
            minlength: 8,
            maxlength: 12
        },
        company_name: {
            required: true,
            minlength: 3,
            maxlength: 150,
            validateTextField: true
        },
        designation:{
            maxlength: 150,
            validateTextField: true
        },
        linkedin_link: {
            validateTextField: true,
        },
        message: {
            maxlength: 150,
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
        name: {
            required: "Please provide a full name",
            minlength: "Please insert at least 3 letters",
            maxlength: "Please keep name length upto 100 characters",
            validateTextField: "Please enter a valid full name",
        },
        email: {
            required: "Please provide an email address",
            email: "Please enter a valid email address",
            maxlength: "Please keep email length upto 150 characters",
        },
        phone: {
            required: "Please provide a phone",
            numericplus: "Please enter a valid phone",
            minlength: "Please keep number length between 8 to 12",
            maxlength: "Please keep number length between 8 to 12",
        },
        company_name: {
            required: "Please provide a company name",
            minlength: "Please insert at least 3 letters",
            maxlength: "Please keep company name length upto 150 characters",
            validateTextField: "Please enter a valid company name",
        },
        designation:{
            maxlength: "Please keep job role length upto 150 characters",
            validateTextField: "Please enter a valid job title",
        },
        linkedin_link: {
            validateTextField: "Please enter a valid linkedIn profile link",
        },
        message: {
            maxlength: "Please keep research requirements length upto 150 characters",
            validateTextField: "Please enter a valid research requirements",
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