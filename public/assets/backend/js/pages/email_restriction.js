$(document).ready(function () {
    'use strict';
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            email_domain: { required: true },
            email_category: { required: true},
        },
        messages: {
            email_domain: { required: "Please provide a email domain."},
            email_category: { required: "Please provide a email category.."},
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
    console.log(column);
    var order =$('#sort_order').val();
    console.log(order);
    order = (order == 'asc')?'desc':'asc';
    $('#sort_by').val(column);
    $('#sort_order').val(order);
    fetch_data('email-restriction',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('email-restriction',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('email-restriction',1);
});

$(document).on('click', '#email-restrictions_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('email-restriction',page);
});
