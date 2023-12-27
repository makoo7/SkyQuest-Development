// about-hero-section
$(document).ready(function() {
    $(".abt-hero-slider").slick({
       slidesToShow: 1,
       slidesToScroll: 1,
       arrows: true,
       dots: true,
       centerMode: false,
       margin: 20,
       speed: 700,
       infinite: false,
       autoplaySpeed: 5000,
       autoplay: false,
       draggable: false,
       responsive: [{
             breakpoint: 1025,
             settings: {
                slidesToShow: 1
             }
          },
          {
             breakpoint: 991,
             settings: {
                slidesToShow: 1
             }
          },
          {
             breakpoint: 768,
             settings: {
                slidesToShow: 1
             }
          }
       ]
    });
});

// contact us form validation
$("#frmcontactus").validate({
   errorElement: 'span',
   ignore: ".ignore",
   rules: {
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
           maxlength: 150,
           validateTextField: true
       },
       subject: {
           required: true,
           minlength: 3,
           maxlength: 150,
           validateTextField: true
       },
       message: {
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
           email: "Please enter a valid email address"
       },
       company_name: {
           required: "Please provide a company name",
           minlength: "Please insert at least 3 letters",
           maxlength: "Please keep company name length upto 150 characters",
           validateTextField: "Please enter a valid company name",
       },
       subject: {
           required: "Please provide a subject",
           minlength: "Please insert at least 3 letters",
           maxlength: "Please keep subject length upto 150",
           validateTextField: "Please enter a valid subject",
       },
       message: {
           required: "Please provide a message",
           minlength: "Please insert at least 3 letters",
           validateTextField: "Please enter a valid message",
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