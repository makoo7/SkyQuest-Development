$(document).ready(function () {
    'use strict';

    var mode = segments[3];
    
    if(mode!='edit'){
        // show name of selected report files to import
        var selReportDiv = "";
        document.querySelector('#reportsInput').addEventListener('change', handleReportFileSelect, false);
        selReportDiv = document.querySelector("#selectedReportFiles");
        function handleReportFileSelect(e) {        
            if(!e.target.files) return;
            selReportDiv.innerHTML = "";        
            var reportfiles = e.target.files;
            if(reportfiles.length > 10){
                selReportDiv.innerHTML += "You can import upto 10 files only.";
                $("#reportSubmit").attr("disabled", true);
                return;
            }
            for(var i=0; i<reportfiles.length; i++) {
                var f = reportfiles[i];            
                selReportDiv.innerHTML += f.name + "<br/>";
            }       
        }

        // show name of selected graph files to import
        var selGraphDiv = "";	
        document.querySelector('#excelfile').addEventListener('change', handleGraphFileSelect, false);
        selGraphDiv = document.querySelector("#selectedGraphFiles");
        function handleGraphFileSelect(e) {        
            if(!e.target.files) return;        
            selGraphDiv.innerHTML = "";        
            var graphfiles = e.target.files;
            if(graphfiles.length > 10){
                selGraphDiv.innerHTML += "You can import upto 10 files only.";
                $("#graphSubmit").attr("disabled", true);
                return;
            }
            for(var i=0; i<graphfiles.length; i++) {
                var f = graphfiles[i];            
                selGraphDiv.innerHTML += f.name + "<br/>";
            }       
        }

        $(document).on('click', '#importBtn', function () {
            $('#importFrm').toggleClass('d-none');
        });    
        $(document).on('click', '#importgraphBtn', function () {
            $('#importGraphFrm').toggleClass('d-none');
        });
        $(document).on('change', '#report_type', function () {
            if($(this).val() != ''){
                $("#report_mode").show();
            }
        });
        $(document).on('change', '#report_mode', function () {
            if($(this).val() != ''){
                $('#reportsInput').trigger('click');
            }
        });
    }

    $("#frmAddUpdate").validate({
        //ignore: ':hidden:not(textarea)',
        ignore: '',
        errorElement: 'span',
        rules: {
            name: { required: true },
            slug: { required: true },
            //image: { required: true },
            image_alt: { required: true },
            s_c: { required: true },
            download: { required: true },
            pages: { required: true },
            meta_title: { required: true },
            meta_description: { required: true },
            sector_id: { required: true },
            industry_group_id: { required: true },
            industry_id: { required: true },
            sub_industry_id: { required: true },
            "segment_name[]": { required: true },
            license_type: { required: true },
            file_type: { required: true },
            price: { required: true, number: true },
            description: { required: function (element) {
                return $("#report_type").val()=='Dynamic';
            }, },
            toc: { required: function (element) {
                return $("#report_type").val()=='Dynamic';
            }, },
            whats_included: { required: function (element) {
                return $("#report_type").val()!='Upcoming';
            }, },
            methodologies: { required: function (element) {
                return $("#report_type").val()!='Upcoming';
            }, },
            analyst_support: { required: function (element) {
                return $("#report_type").val()!='Upcoming';
            }, },
            market_insights: { required: function (element) {
                return $("#report_type").val()!='Dynamic';
            }, },
            segmental_analysis: { required: function (element) {
                return $("#report_type").val()=='SD';
            }, },
            regional_insights: { required: function (element) {
                return $("#report_type").val()=='SD';
            }, },
            market_dynamics: { required: function (element) {
                return $("#report_type").val()=='SD';
            }, },
            competitive_landscape: { required: function (element) {
                return $("#report_type").val()=='SD';
            }, },
            key_market_trends: { required: function (element) {
                return $("#report_type").val()=='SD';
            }, },
            skyQuest_analysis: { required: function (element) {
                return $("#report_type").val()=='SD';
            }, },
        },
        messages: {
            name: { required: "Please enter report name."},
            slug: { required:  "Please enter slug." },
            //image: { required: "Please select a slug." },
            image_alt:{ required:  "Please enter image alt." },
            s_c: { required: "Please select S/C." },
            download: { required: "Please enter download." },
            pages: { required: "Please enter pages." },
            meta_title: { required: "Please enter meta title." },
            meta_description: { required: "Please enter meta description." },
            sector_id:{required: "Please select sector."},
            industry_group_id:{required: "Please select industry group."},
            industry_id:{required: "Please select industry."},
            sub_industry_id:{required: "Please select sub industry."},
            "segment_name[]": { required: "Please enter segment name." },
            license_type: { required: "Please enter license type." },
            file_type: { required: "Please enter file type." },
            price: { required: "Please enter price.", number: "Please enter valid price." },
            description: { required: "Please enter description." },
            toc: { required: "Please enter toc." },
            whats_included: { required: "Please enter whats included." },
            methodologies: { required: "Please enter methodologies." },
            analyst_support: { required: "Please enter analyst support." },
            market_insights: { required: "Please enter market insights." },
            segmental_analysis: { required: "Please enter segmental analysis." },
            regional_insights: { required: "Please enter regional insights." },
            market_dynamics: { required: "Please enter market dynamics." },
            competitive_landscape: { required: "Please enter competitive landscape." },
            key_market_trends: { required: "Please enter key market trends." },
            skyQuest_analysis: { required: "Please enter skyQuest analysis." },
        },
        errorPlacement: function(label, element) {
            // position error label after generated textarea
            if (element.is("textarea.tinymce-editor")) {
                label.insertAfter(element.next());
            } else {
                label.insertAfter(element)
            }
        },
        submitHandler: function (form) {
            //custom validation for name with array fields
            var valid = 0;
            var $firstEle ='';
            var $firstAcc ='';
            var $secAcc ='';
            $('input[name="license_type[]"]').each( function( key, value ) {
                if($(this).val().trim() == ''){
                    var id = $(this).attr('id');
                    $(this).parent().append('<span id="'+id+'-error" class="error">Please enter license type.</span>');
                    valid = 1;
                    if($firstEle == ''){
                        $firstEle = $(this);
                    }
                } else {
                    $(this).parent().find('#'+id+'-error').remove();
                }
            });
            $('input[name="file_type[]"]').each( function( key, value ) {
                if($(this).val().trim() == ''){
                    var id = $(this).attr('id');
                    $(this).parent().append('<span id="'+id+'-error" class="error">Please enter file type.</span>');
                    valid = 1;
                    if($firstEle == ''){
                        $firstEle = $(this);
                    }
                } else {
                    $(this).parent().find('#'+id+'-error').remove();
                }
            });
            $('input[name="price[]"]').each( function( key, value ) {
               
                if($(this).val().trim() == ''){
                    var id = $(this).attr('id');
                    $(this).parent().append('<span id="'+id+'-error" class="error">Please enter price.</span>');
                    valid = 1;
                    if($firstEle == ''){
                        $firstEle = $(this);
                    }
                } 
                else {
                    $(this).parent().find('#'+id+'-error').remove();
                }
            });
            $('input[name="faq_question[]"]').each( function( key, value ) {
                if($(this).val().trim() == ''){
                    var id = $(this).attr('id');
                    var accId =  $(this).data('id');
                    $(this).parent().append('<span id="'+id+'-error" class="error">Please enter faq question.</span>');
                    valid = 1;
                    $("#answer6").collapse("show");
                    if($firstEle == ''){
                        $firstEle = $(this);
                    }
                    if($firstAcc == ''){
                        $firstAcc = 'answer'+accId;
                    }
                } else {
                    $(this).parent().find('#'+id+'-error').remove();
                }
            });
            $('.faq_answer').each( function( key, value ) {
                if($(this).val().trim() == ''){
                    var id = $(this).attr('id');
                    var accId =  $(this).data('id');
                    $(this).parent().append('<span id="'+id+'-error" class="error">Please enter faq answer.</span>');
                    valid = 1;
                    if($firstEle == ''){
                        $firstEle = $(this);
                    }
                    if($secAcc == ''){
                        $secAcc = 'answer'+accId;
                    }
                } else {
                    $(this).parent().find('#'+id+'-error').remove();
                }
            });

            if(valid == 1){
               if($firstAcc!= ''){
                    $('#'+$firstAcc).collapse("show");
                } else if($secAcc != ''){
                    $('#'+$secAcc).collapse("show");
                }
                $firstEle.focus();
            } else {
                $('.loader').show();
                form.submit();
            }
        }
    });
});

$(document).on('click', '.sortColumn', function () {
    var column = $(this).data('column');
    var order =$('#sort_order').val();
    order = (order == 'asc')?'desc':'asc';
    $('#sort_by').val(column);
    $('#sort_order').val(order);
    fetch_data('report',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('report',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('report',1);
});

$(document).on('click', '#report_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('report',page);
});

$(document).on('click', '.deleteSegment', function () { 
    var self = this;
    var id = $(this).data('id');
    //alert(page); return false;
    var url = baseUrl + segments[2] + "/deleteSegment";
    title = "Do you want to delete?";
    body = "If you select yes, segment will be removed.";
    
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
                            $('#segment_all_' + id).remove();
                            $('#segment_name_' + id).remove();
                            $('#sub_segment_' + id).remove();
                            $('.loader').hide();
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
$(document).on('click', '.deleteSubSegment', function () { 
    var self = this;
    var id = $(this).data('id');
    var segment_id = $(this).data('segment');
    //alert(page); return false;
    var url = baseUrl + segments[2] + "/deleteSegment";
    title = "Do you want to delete?";
    body = "If you select yes, sub segment will be removed.";
    
    $.prompt(body, {
        title: title,
        buttons: { "No": false, "Yes": true },
        focus: 1,
        submit: function (e, v, m, f) {
            if (v) {
                $('#sub_segment_name_'+segment_id+ '_' + id).remove();
                $('#sub_segment_delete_'+segment_id+ '_' + id).remove();
               e.preventDefault();
            }
            $.prompt.close();
        }
    });
});
$(document).on('click', '.deleteSegmentStatic', function () { 
    var self = this;
    var id = $(this).data('id');
    var segment_id = $(this).data('segment');
    //alert(page); return false;
    var url = baseUrl + segments[2] + "/deleteSegment";
    title = "Do you want to delete?";
    body = "If you select yes, sub segment will be removed.";
    
    $.prompt(body, {
        title: title,
        buttons: { "No": false, "Yes": true },
        focus: 1,
        submit: function (e, v, m, f) {
            if (v) {
                $('#segment_all_' + id).remove();
               
               e.preventDefault();
            }
            $.prompt.close();
        }
    });
});
$(document).on('click', '.addSegment', function () { 
    var segment_html = $('#section-template').html();
    var segment_counter = $('#main_segment_counter').val();
    var sub_segment_counter = 1;
    var hidden_counter = '<input type="hidden" id="segment_counter_'+segment_counter+'" value="2"/>'
    segment_html = segment_html.replace(/{segcounter}/g,segment_counter).replace(/{subsegcounter}/g,sub_segment_counter).replace(/{hiddenCounter}/g,hidden_counter);
    $('#segment-section').append(segment_html);
    $('#main_segment_counter').val(parseInt(segment_counter)+1);
    //$('#segment_counter_'+segment_counter).val(parseInt(sub_segment_counter)+1);
});
$(document).on('click', '.deleteSegmentStatic', function () { 
    //var segment_html = $('#section-template').html();
    //$('#segment-section').append(segment_html);
});
$(document).on('click', '.addSubSegment', function () {
    
    var segment_id = $(this).data('segment');
    var segment_html = $('#sub-section-template').html();
    var segment_counter = $('#segment_counter_'+segment_id).val();
    segment_html = segment_html.replace(/{segment_id}/g,segment_id).replace(/{subsegcounter}/g,segment_counter);
    $('#sub_segment_box_'+segment_id).append(segment_html); 
    $('#segment_counter_'+segment_id).val(parseInt(segment_counter)+1);
});
$(document).on('click', '.deleteSubSegmentStatic', function () { 
    //var segment_html = $('#section-template').html();
    //$('#segment-section').append(segment_html);
});
$(document).on('click', '.addFaq', function () { 
    var faq_html = $('#faq-template').html();
    var faq_counter = $('#faq_counter').val();
    faq_html = faq_html.replace(/{faq_counter}/g,faq_counter);
    $('#additionalFaq').append(faq_html);
    $('#faq_counter').val(parseInt(faq_counter)+1);
    tinymce.remove();
    initTinymce();
});

$(document).on('click', '.deleteStaticFaq', function () { 
    var self = this;
    var id = $(this).data('id');
    //alert(page); return false;
    var url = baseUrl + segments[2] + "/deleteSegment";
    title = "Do you want to delete?";
    body = "If you select yes, faq will be removed.";
    
    $.prompt(body, {
        title: title,
        buttons: { "No": false, "Yes": true },
        focus: 1,
        submit: function (e, v, m, f) {
            if (v) {
                $('#faq_block_' + id).remove();
                e.preventDefault();
            }
            $.prompt.close();
        }
    });
});
$(document).on('click', '.deleteFaq', function () { 
    var self = this;
    var id = $(this).data('id');
    //alert(page); return false;
    var url = baseUrl + segments[2] + "/deleteFaq";
    title = "Do you want to delete?";
    body = "If you select yes, faq will be removed.";
    
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
                            $('#heading' + id).remove();
                            $('#answer' + id).remove();
                            $('.loader').hide();
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

function deleteAdminAvatar(id) {
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
                    url: baseUrl + 'deleteImage',
                    dataType: 'json',
                    data: { 'id': id },
                    success: function (data) {
                        $(".loader").hide();
                        if (data.success == "1") {
                            $('#image_preview').attr('src', window.location.origin + '/assets/backend/images/default-avatar.png');
                            toastr.success(data.message);
                            $(".deleteMetaImg").hide();
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


$("#sector_id").on('change', function() {
    var entity_id = this.value;
    $.ajax({
        type: "POST",
        data: { entity_id: entity_id, type: 'industry_group' },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "report/getIndustryData",
        success: function (data) {
            //console.log(data);
            $("#industry_group_id").html(data);
            $("#industry_id").html('');
            $("#sub_industry_id").html('');
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
});

$("#industry_group_id").on('change', function() {
    var entity_id = this.value;
    $.ajax({
        type: "POST",
        data: { entity_id: entity_id, type: 'industry' },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "report/getIndustryData",
        success: function (data) {
            $("#industry_id").html(data);
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
});

$("#industry_id").on('change', function() {
    var entity_id = this.value;
    $.ajax({
        type: "POST",
        data: { entity_id: entity_id, type: 'sub_industry' },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "report/getIndustryData",
        success: function (data) {
            $("#sub_industry_id").html(data);
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
});