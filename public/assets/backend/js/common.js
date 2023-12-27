$('.loader').show();

function fetch_data(module,page) {
    var container = "#"+module+"_list";
	$.ajax({
		type: "POST",
		data: $('#searchFrm').serialize(),
		headers: {
			'X-CSRF-TOKEN': _token
		},
		url: baseUrl + module + "/ajax?page=" + page,
		success: function (data) {
			$(container).html(data);
		},
		error: function (data, e) {
			toastr.error("Something went wrong. Please try again later.");
		}
	});
}

$(document).on('submit', 'form', function () {
    $('.loader').show();
});

$(document).bind("ajaxSend", function () {
    $(".loader").show();
}).bind("ajaxComplete", function () {
    $(".loader").hide();
});
function initTinymce(){
    tinymce.init({
        forced_root_block: "p",
        force_br_newlines: true,
        force_p_newlines: false,
        selector: 'textarea.tinymce-editor',
        height: 300,
        menubar: false,
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
        toolbar: 'undo redo | code | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | ' +
        'outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | ' +
        'pagebreak | charmap emoticons | fullscreen  preview save print | ' +
        'insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',    
        fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
        //content_style: "body {font-size: 14pt;}",
        element_format : 'html',
        convert_urls: false,
        relative_urls: true,
        file_picker_types: 'image',
        images_upload_url: '/admin/upload'
    });
}


var segments = location.pathname.split('/');
toastr.options.timeOut = 20000;
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param * 1000000)
}, 'File size must be less than {0} MB');

$.validator.addMethod("extension", function (value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, "Please enter a value with a valid extension.");

// password types
$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

$(document).ready(function () {
    initTinymce()
    $('.loader').hide();
    'use strict';
    displayPlaceholderAsLabel();
    document.cookie = "myTimeZone = " + moment.tz.guess();

    /* Siderbasr navigation menu active */
    $('.childmenu li.active').parent().closest('li').addClass('active');
    $('.childmenu li.active').parent().closest('ul').addClass('show');
    $('.mainmenu li.active').find('a:first.show').attr('aria-expanded', 'true');
    if ($('.childmenu').length > 0) {
        $.each($('.childmenu'), function () {
            var $this = $(this);
            if ($this.find('li').length == 0) {
                $this.parent().closest('li').remove();
            }
        });
    }
    /* END */
    // ------------------------------------------------------- //
    // Sidebar Functionality
    // ------------------------------------------------------ //
    $('#toggle-btn').on('click', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');

        $('.side-navbar').toggleClass('shrinked');
        $('.content-inner').toggleClass('active');
        $(document).trigger('sidebarChanged');

        if ($(window).outerWidth() > 1183) {
            if ($('#toggle-btn').hasClass('active')) {
                $('.navbar-header .brand-small').hide();
                $('.navbar-header .brand-big').show();
            } else {
                $('.navbar-header .brand-small').show();
                $('.navbar-header .brand-big').hide();
            }
        }

        if ($(window).outerWidth() < 1183) {
            $('.navbar-header .brand-small').show();
        }
    });

    // ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

    var materialInputs = $('input.input-material');

    // activate labels for prefilled values
    materialInputs.filter(function () { return $(this).val() !== ""; }).siblings('.label-material').addClass('active');

    // move label on focus
    materialInputs.on('focus', function () {
        displayPlaceholderAsLabel();
    });
    // remove/keep label on blur
    materialInputs.on('keyup', function () {
        displayPlaceholderAsLabel();
    });
    materialInputs.on('blur', function () {
        displayPlaceholderAsLabel();
    });

    // ------------------------------------------------------- //
    // Footer
    // ------------------------------------------------------ //

    var contentInner = $('.content-inner');

    $(document).on('sidebarChanged', function () {
        adjustFooter();
    });

    $(window).on('resize', function () {
        adjustFooter();
    })

    function adjustFooter() {
        var footerBlockHeight = $('.main-footer').outerHeight();
        contentInner.css('padding-bottom', footerBlockHeight + 'px');
    }

    if (emsg && emsg != "") {
        toastr.clear();
        if (ecls == "error") {
            toastr.error(emsg);
        } else {
            toastr.success(emsg);
        }
    }

    $(document).on('change', "input[type=file]", function (e) {
        var fileName = e.target.files[0].name;        
        $(this).parents('.form-fileUpload').find(".uploadFile").text(fileName);
        displaySelectedFile(this, $(this).attr('id') + '_preview');
    });
});

function displayPlaceholderAsLabel() {
    $.each($('input.input-material'), function () {
        var $this = $(this);
        if ($this.val())
            $this.siblings('.label-material').addClass('active');
        else
            $this.siblings('.label-material').removeClass('active');
    });
    $.each($('select.input-material'), function () {
        var $this = $(this);
        if ($this.val())
            $this.siblings('.label-material').addClass('active');
        else
            $this.siblings('.label-material').removeClass('active');
    });
}

function displaySelectedFile(input, previewid) {
    if (input.files && input.files[0]) {
        var mimeType = input.files[0]['type'];//mimeType=image/jpeg or application/pdf etc...
        // console.log(mimeType);
        // jpeg, jpg, gif, png, webp, bmp
        if (mimeType.split('/')[0] === 'image/jpeg' ||
        mimeType.split('/')[0] === 'image/jpg' || 
        mimeType.split('/')[0] === 'image/png' || 
        mimeType.split('/')[0] === 'image/gif' || 
        mimeType.split('/')[0] === 'image/webp' || 
        mimeType.split('/')[0] === 'image/bmp' || 
        mimeType === 'image/jpeg' || mimeType === 'image/jpg' || mimeType === 'image/png' ||
        mimeType === 'image/gif' || mimeType === 'image/webp' || mimeType === 'image/bmp') {
            $('#' + previewid).show();
            $('#' + previewid + "_preview").hide();            
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + previewid).attr('src', e.target.result);
                $("#div_" + previewid).html('<img src="' + e.target.result + '" class="img-fluid image_preview">');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}

$(document).on('click', '.updateStatus', function () { 
	var self = this;
	var id = $(this).data('id');
    var url = baseUrl + segments[2] + "/status";
    var title = "Do you really want to change the status?";
    var body = "If you select yes, " + segments[2].replace(/[\_\-]+/g, ' ') + " status will be change.";

    $.prompt(body, {
        title: title,
        buttons: { "No": false, "Yes": true },
        focus: 1,
        submit: function (e, v, m, f) {
            if (v) {
                e.preventDefault();
                $('.loader').show();
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': _token
                    },
                    url: url,
                    data: { id: id },
                    success: function (data) {
                        toastr.clear();
                        if (data.success == "1") {
                            if (data.status == "1") {
                                $(self).children("i").addClass('green');
                                $(self).children("i").removeClass('red');
                            }
                            else{
                                $(self).children("i").addClass('red');
                                $(self).children("i").removeClass('green');
                            }
                            $('.loader').hide();
                            toastr.success(data.message);
                        }
                        else {
                            $('.loader').hide();
                            toastr.error(data.message);
                        }
                    }
                });
            }
            else if (v == false) {

            }
            $.prompt.close();
        }
    });
});

$(document).on('click', '.deleteData', function () { 
    var self = this;
    var id = $(this).data('id');
    var page = ($("#page").val()!='') ? $("#page").val() : 1;
    //alert(page); return false;
    var url = baseUrl + segments[2] + "/destroy";
    title = "Do you want to delete?";
    body = "If you select yes, " + segments[2].replace(/[\_\-]+/g, ' ') + " will be removed.";
    
    $.prompt(body, {
        title: title,
        buttons: { "No": false, "Yes": true },
        focus: 1,
        submit: function (e, v, m, f) {
            if (v) {
                $('.loader').show();
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-Token': _token
                    },
                    type: "POST",
                    url: url,
                    data: { id: id },
                    success: function (data) {
                        if (data.success == "1") {
                            $('#item_' + id).remove();
                            $('.loader').hide();
                            fetch_data(segments[2],page);
                            toastr.success(data.message);
                        }
                        else {
                            $('.loader').hide();
                            toastr.error(data.message);
                        }
                    }
                });
            }
            $.prompt.close();
        }
    });
});