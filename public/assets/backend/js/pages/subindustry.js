$(document).ready(function () {
    'use strict';
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            title: { required: true },
            industry_id: { required: true },
            slug: { required: true },
            initial: { required: true, maxlength: 5 },
            page_title: {required: true},
        },
        messages: {
            title: { required: "Please provide the name."},
            industry_id: { required: "Please select the industry."},
            slug: { required: "Please provide the slug."},
            initial: { required: "Please provide the initial."},
            page_title: {required: "Please provide a title"},
        },
        errorPlacement: function(label, element) {
            // position error label after generated textarea
            if (element.is("textarea.tinymce-editor")) {
                label.insertAfter(element.next());
            } else {
                label.insertAfter(element)
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
    fetch_data('sub-industry',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('sub-industry',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('sub-industry',1);
});

$(document).on('click', '#sub-industry_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('sub-industry',page);
});
