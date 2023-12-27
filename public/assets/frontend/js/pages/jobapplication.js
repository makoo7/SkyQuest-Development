//validate file extension custom  method.
jQuery.validator.addMethod("extension", function (value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, "Please enter a value with a valid extension.");

// job application form validation
$("#frmapplyjob").validate({
    errorElement: 'span',
    ignore: ".ignore",
    rules: {
        first_name: {
            required: true,
            minlength: 3,
            validateTextField: true
        },
        last_name: {
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
        work_experience: {
            required: true,
            minlength: 3,
            validateTextField: true
        },
        notice_period: {
            required: true,
            minlength: 3,
            validateTextField: true
        },
        current_ctc: {
            required: true,
            validateTextField: true
        },
        expected_ctc: {
            required: true,
            validateTextField: true
        },
        portfolio_or_web: {
            required: true,
            url: true,
            validateTextField: true
        },
        resume: {
            required: true,
            extension: "pdf|doc|docx"
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
        first_name: {
            required: "Please provide a first name",
            minlength: "Please insert at least 3 letters",
            validateTextField: "Please enter a valid first name",
        },
        last_name: {
            required: "Please provide a last name",
            minlength: "Please insert at least 3 letters",
            validateTextField: "Please enter a valid last name",
        },
        phone: {
            required: "Please provide a phone",
            numericplus: "Please enter a valid phone",
            minlength: "Please keep number length between 8 to 12",
            maxlength: "Please keep number length between 8 to 12",
        },
        email: {
            required: "Please provide an email address",
            email: "Please enter a valid email address"
        },
        work_experience: {
            required: "Please provide the work experience",
            minlength: "Please insert at least 3 letters",
            validateTextField: "Please enter the valid work experience",
        },
        notice_period: {
            required: "Please provide the notice period",
            minlength: "Please insert at least 3 letters",
            validateTextField: "Please enter the valid notice period",
        },
        current_ctc: {
            required: "Please provide the current CTC",
            validateTextField: "Please enter the valid current CTC",
        },
        expected_ctc : {
            required: "Please provide the expected CTC",
            validateTextField: "Please enter the valid expected CTC",
        }, 
        portfolio_or_web: {
            required: "Please provide the portfolio/website URL",
            url: "Please enter the valid portfolio/website URL",
            validateTextField: "Please enter the valid portfolio/website URL",
        },
        resume : {
            required: "Please upload your resume",
            extension:"Please select only pdf and doc files"
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