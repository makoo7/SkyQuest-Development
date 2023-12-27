$(document).ready(function () {
    'use strict';
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            position: { required: true },
            description: { required: true },
            no_of_position: { number: true, maxlength: 3 }
            
        },
        messages: {
            position: { required: "Please provide a position."},
            description: { required: "Please provide a description."},
            no_of_position: { number: "Please provide a valid number."},
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
    fetch_data('career',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('career',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('career',1);
});

$(document).on('click', '#career_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('career',page);
});