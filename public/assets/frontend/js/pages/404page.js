$("#privacy_policy").on("click", function (e) {
    var checkbox = $(this);
    if (!checkbox.is(":checked")) {        
        // prevent from being unchecked
        this.checked=!this.checked;
    }
});

// 404 form validation
$("#frmnotfound").validate({
    errorElement: 'span',
    ignore: ".ignore",
    rules: {
        name: {
            required: true,
            minlength: 3,
            validateTextField: true
        },
        email: {
            required: true,
            email: true,
            validateEmail: true
        },
        country_id: {
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
        privacy_policy: {
            required: true,
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
            required: "Please provide a name",
            minlength: "Please insert at least 3 letters",
            validateTextField: "Please enter a valid name",
        },
        email: {
            required: "Please provide an email address",
            email: "Please enter a valid email address"
        },
        country_id: {
            required: "Please select a country",
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
        privacy_policy: {
            required: "Please indicate that you have read and agree to the Privacy Policy.",
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