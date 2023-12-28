document.addEventListener("contextmenu", (e) => {
    e.preventDefault();
}, false);

// document.addEventListener("keydown", (e) => {
//     if (e.ctrlKey || e.keyCode == 123) {
//         e.stopPropagation();
//         e.preventDefault();
//     }
// });

$(document).bind("ajaxSend", function () {
    $(".page-loader").show();
}).bind("ajaxComplete", function () {
    $(".page-loader").hide();
});

function showLoader() {
    $('.spinner-border').show();
    $('.modal-login-btn').attr('disabled', true);
};

$("#copyLink").click(function () {
    var e = document.body.appendChild(document.createElement("input"));
    e.value = window.location.href;
    e.select();
    document.execCommand("copy");
    navigator.clipboard.writeText(window.location.href);
    e.parentNode.removeChild(e);
    toastr.success("Link Copied Successfully");
});

var popupSize = {
    width: 780,
    height: 550
};

$('.social-button').on('click', function (e) {
    var verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
        horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

    var popup = window.open($(this).prop('href'), 'social',
        'width=' + popupSize.width + ',height=' + popupSize.height +
        ',left=' + verticalPos + ',top=' + horisontalPos +
        ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

    if (popup) {
        popup.focus();
        e.preventDefault();
    }
});

$(document).ready(function () {
    'use strict';
    /* Hide Header on on scroll down */
    var c, currentScrollTop = 0,
        navbar = $('.navigation-bar');

    $(window).scroll(function () {
        var a = $(window).scrollTop();
        var b = navbar.height();

        currentScrollTop = a;

        if (c < currentScrollTop && a > b + b) {
            navbar.addClass("scrollUp");
        } else if (c > currentScrollTop && !(a <= b)) {
            navbar.removeClass("scrollUp");
        }
        c = currentScrollTop;
    });

    /* to show toastr messages */
    if (emsg && emsg != "") {
        toastr.clear();
        if (ecls == "error") {
            toastr.error(emsg);
        } else {
            toastr.success(emsg);
        }
    }

    /* password types */
    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    /* mega-menu click-event */
    $('.navigation-icn').click(function () {
        $('.mega-navigation').show();
    });

    $('.cancel-btn').click(function () {
        $('.mega-navigation').hide();
    });

    /* bookmark alert */
    $('.book-mark-tag').click(function () {
        $(".login-view").toggle();
    });
    $('.close').click(function () {
        $(".login-view").hide();
    });

    /* lets-talk-nav */
    $('#lets-talk').click(function () {
        $(".lets-nav").show();
        $('.lets-bg').show();
    });
    $('#lets-close').click(function () {
        $(".lets-nav").hide();
        $('.lets-bg').hide();
    });
    $('.lets-bg').click(function () {
        $(".lets-nav").hide();
        $(".lets-bg").hide();
    });

    /* slick slider */
    $(() => {
        var createSlick = () => {
            let slider = $(
                ".slider");

            slider.not(
                ".slick-initialized"
            ).slick({
                lazyLoad: 'ondemand',
                autoplay: true,
                autoplaySpeed: 5000,
                infinite: true,
                dots: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 600,
                draggable: false,
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        adaptiveHeight: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                ]
            });
        };

        createSlick();

        $(window).on(
            "resize orientationchange",
            createSlick);
    });

    // common validations 
    jQuery.validator.addMethod("numericplus", function (value, element) {
        if (/^[+]?[0-9]+$/i.test(value)) {
            return true;
        } else {
            return false;
        };
    }, "Numbers and plus only");

    jQuery.validator.addMethod("validateTextField", function (value, element) {
        if (value.toLowerCase().indexOf("javascript") >= 0) {
            return false;
        } else if (value.toLowerCase().indexOf("script") >= 0) {
            return false;
        } else if (value.toLowerCase().indexOf("createelement") >= 0) {
            return false;
        } else if (value.toLowerCase().indexOf("appendchild") >= 0) {
            return false;
        } else {
            return true;
        }
    }, "Please enter a valid information");

    jQuery.validator.addMethod("validateEmail", function (value, element) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test(value);
    }, "Please enter a valid email address");

    /* login form validation */
    $("#frmlogin").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true,
                validateEmail: true
            },
            password: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Please provide an email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please provide a password"
            }
        },
        submitHandler: function (form) {
            showLoader();
            form.submit();
        }
    });

    /* register form validation */
    $("#frmregister").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true,
                validateEmail: true
            },
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#frmregister #register_password"
            }
        },
        messages: {
            email: {
                required: "Please provide an email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long"
            },
            password_confirmation: {
                required: "Please provide a confirm password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above"
            },
        },
        submitHandler: function (form) {
            showLoader();
            form.submit();
        }
    });

    /* forgot pwd form validation */
    $("#frmForgotPassword").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true,
                validateEmail: true
            }
        },
        messages: {
            email: {
                required: "Please provide an email address",
                email: "Please enter a valid email address"
            }
        },
        submitHandler: function (form) {
            showLoader();
            form.submit();
        }
    });

    /* reset pwd form validation */
    $("#frmresetpwd").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true,
                validateEmail: true
            },
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#frmresetpwd #resetpwd_password"
            }
        },
        messages: {
            email: {
                required: "Please provide an email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long"
            },
            password_confirmation: {
                required: "Please provide a confirm password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above"
            },
        },
        submitHandler: function (form) {
            showLoader();
            form.submit();
        }
    });

    /* Login form validation and close event */
    $('#loginModal').on('hide.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $("#frmlogin").find(".error").text("");
        $("#frmlogin").find(".invalid-feedback").children("strong").text("");
    });

    /* Register form validation and close event */
    $('#registerModal').on('hide.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $("#frmregister").find(".error").text("");
        $("#frmregister").find(".invalid-feedback").children("strong").text("");
    });

    /* Forgot Password form validation and close event */
    $('#forgotpwdModal').on('hide.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $("#frmForgotPassword").find(".error").text("");
        $("#frmForgotPassword").find(".invalid-feedback").children("strong").text("");
    });

    /* Reset Password form validation and close event */
    $('#resetpwdModal').on('hide.bs.modal', function () {
        $(this).find('form').trigger('reset');
        $("#frmresetpwd").find(".error").text("");
        $("#frmresetpwd").find(".invalid-feedback").children("strong").text("");
    });

    /* Number Counters */
    $('.number').each(function () {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 4000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    $("#feedbackSlider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        centerMode: false,
        margin: 20,
        speed: 300,
        draggable: true,
        infinite: true,
        autoplaySpeed: 3000,
        autoplay: true,

    });

    $('#searchtxt').on('keyup', function () {
        var searchtxt = $(this).val();
        if (searchtxt.length >= 2) {
            $.ajax({
                type: "POST",
                data: { searchtxt: searchtxt },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                url: baseUrl + "searchContent",
                success: function (data) {
                    if (data.success == "1") {
                        $("#suggestions").html(data.output);
                        $("#suggestions").show();
                    }
                },
                error: function (data, e) {
                    toastr.error("Something went wrong. Please try again later.");
                }
            });
        }
    });

    $('#searchtxtWhite').on('keyup', function () {
        var searchtxtWhite = $(this).val();
        if (searchtxtWhite.length >= 2) {
            $.ajax({
                type: "POST",
                data: { searchtxtWhite: searchtxtWhite },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                url: baseUrl + "searchContent",
                success: function (data) {
                    if (data.success == "1") {
                        $("#suggestionsWhite").html(data.output);
                        $("#suggestionsWhite").show();
                    }
                },
                error: function (data, e) {
                    toastr.error("Something went wrong. Please try again later.");
                }
            });
        }
    });

    $('#searchtxtMobile').on('keyup', function () {
        var searchtxtMobile = $(this).val();
        if (searchtxtMobile.length >= 2) {
            $.ajax({
                type: "POST",
                data: { searchtxtMobile: searchtxtMobile },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                url: baseUrl + "searchContent",
                success: function (data) {
                    if (data.success == "1") {
                        $("#suggestionsMobile").html(data.output);
                        $("#suggestionsMobile").show();
                    }
                },
                error: function (data, e) {
                    toastr.error("Something went wrong. Please try again later.");
                }
            });
        }
    });

    $('#searchtxtMobileWhite').on('keyup', function () {
        var searchtxtMobileWhite = $(this).val();
        if (searchtxtMobileWhite.length >= 2) {
            $.ajax({
                type: "POST",
                data: { searchtxtMobileWhite: searchtxtMobileWhite },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                url: baseUrl + "searchContent",
                success: function (data) {
                    if (data.success == "1") {
                        $("#suggestionsMobileWhite").html(data.output);
                        $("#suggestionsMobileWhite").show();
                    }
                },
                error: function (data, e) {
                    toastr.error("Something went wrong. Please try again later.");
                }
            });
        }
    });

    /* ACCOUNTS PAGE */
    $('.nav-collapse-btn').on('click', function () {
        $(this).toggleClass('active');
        $('.link-list').toggleClass('collapsed');
        $('.account-tabs').toggleClass('active');
    })
});

function alertBookmark() {
    toastr.error("Please login to add the bookmark.");
}

$("#viewBookmark").on('click', function () {
    toastr.error("Please login to view the bookmarks.");
});

function toggleBookmark(user_id, entity_type, entity_id, isDetailpage = '') {
    var isOnDetailPage = '';
    if (isDetailpage) isOnDetailPage = isDetailpage;

    $.ajax({
        type: "POST",
        data: { user_id: user_id, entity_type: entity_type, entity_id: entity_id, isOnDetailPage: isOnDetailPage },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "toggleBookmark",
        success: function (data) {
            if (data.success == "1") {
                $("#bookmarktag[data-id='" + entity_id + "']").attr("src", data.html);
                toastr.success(data.message);
            }
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
}

/* For Report pages - Starts */
function getFileType(report_id, license_type) {
    $.ajax({
        type: "POST",
        data: { report_id: report_id, license_type: license_type },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "reports/getReportFileType",
        success: function (data) {
            if (data.success == "1") {
                $("#file_type").html(data.html);
                $("#report_price").html("$" + data.price);
                $("#price").val(data.price);
                $("#stickyPrice h5").html("$" + data.price);
            }
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
}

$("#file_type").on('change', function () {
    var report_id = $("#report_id").val();
    var license_type = $("#license_type").val();
    var file_type = $("#file_type").val();
    $.ajax({
        type: "POST",
        data: { report_id: report_id, license_type: license_type, file_type: file_type },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "reports/getReportPrice",
        success: function (data) {
            if (data.success == "1") {
                $("#report_price").html("$" + data.price);
                $("#price").val(data.price);
                $("#stickyPrice h5").html("$" + data.price);
            }
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
})

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

$(window).scroll(function () {
    var product = $(window).scrollTop();
    if (product >= 800) {
        $(".sticky-poroduct").addClass("active");
    } else {
        $(".sticky-poroduct").removeClass("active");
    }
});
/* For Report pages - Ends */

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Delete cookie
function deleteCookie(cname) {
    const d = new Date();
    d.setTime(d.getTime() + (24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=;" + expires + ";path=/";
}

// Read cookie
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Set cookie consent
function acceptCookieConsent() {
    deleteCookie('user_cookie_consent');
    setCookie('user_cookie_consent', 1, 30);
    $("#cookieNotice").hide();
}
let cookie_consent = getCookie("user_cookie_consent");
if (cookie_consent == "") {
    document.getElementById("cookieNotice").style.display = "block";
} else {
    $("#cookieNotice").hide();
}
function declineCookieConsent() {
    setCookie('user_cookie_consent', 2, 30);
    $("#cookieNotice").hide();
}

function getFinalReportPrice(report_id, license_type, file_type) {
    var report_price = '';
    $.ajax({
        type: "POST",
        async: false,
        data: { report_id: report_id, license_type: license_type, file_type: file_type },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "reports/getReportPrice",
        success: function (data) {
            if (data.success == "1") {
                report_price = (data.price).replace(",", "");
            }
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
    return report_price;
}