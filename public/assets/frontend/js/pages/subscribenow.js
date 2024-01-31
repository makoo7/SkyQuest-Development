$(document).ready(function () {
    $("#phonecode").select2({
        placeholder: "Country Code*",
    })
});

// sample a request form validation
$("#frmsubscribenow").validate({
    errorElement: 'span',
    ignore: ".ignore",
    rules: {
        plan: {
            required: true
        },
        name: {
            required: true,
            minlength: 3,
            validateTextField: true
        },
        lastname: {
            required: true,
            minlength: 3,
            validateTextField: true
        },
        email: {
            required: true,
            email: true,
            validateEmail: true
        },
        phonecode: {
            required: true,
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
        designation: {
            required: true,
            minlength: 3,
            maxlength: 100,
            validateTextField: true
        },
        linkedin_link: {
            validateTextField: true
        },
        message: {
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
        plan: {
            required: "Please provide a plan",
        },
        name: {
            required: "Please provide a first name",
            minlength: "Please insert at least 3 letters",
            validateTextField: "Please enter a valid first name",
        },
        lastname: {
            required: "Please provide a last name",
            minlength: "Please insert at least 3 letters",
            validateTextField: "Please enter a valid last name",
        },
        email: {
            required: "Please provide an email address",
            email: "Please enter a valid email address"
        },
        phonecode: {
            required: "Please provide a country code",
        },
        phone: {
            required: "Please provide a phone number",
            numericplus: "Please enter a valid phone number",
            minlength: "Please keep number length between 8 to 12",
            maxlength: "Please keep number length between 8 to 12",
        },
        company_name: {
            required: "Please provide a company name",
            minlength: "Please insert at least 3 letters",
            maxlength: "Please keep company name length upto 150 characters",
            validateTextField: "Please enter a valid company name",
        },
        designation: {
            required: "Please provide a job title",
            minlength: "Please insert at least 3 letters",
            maxlength: "Please keep job title length upto 100",
            validateTextField: "Please enter a valid job title",
        },
        linkedin_link: {
            validateTextField: "Please enter a valid linkedIn profile link",
        },
        message: {
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