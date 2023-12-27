$(document).ready(function () {
    'use strict';
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            name: { required: true },
            "permission[]": { required: true }
        },
        messages: {
            name: { required: "Please provide a role name."},
            "permission[]": { required: "Please select a permission."},
        },
        errorPlacement: function( error, element ){
            if(element.is( ":checkbox" )){
            // error append here
            error.appendTo('#chkerror');
            }
            else {
            error.insertAfter(element);
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
    fetch_data('roles',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('roles',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('roles',1);
});

$(document).on('click', '#roles_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('roles',page);
});

$(".permissionchk").on('change', function(e) {
    //if ($(this).prop('checked')==true){
        var roleName = $(this).data('role');    
        var moduleName = $(this).data('module');
        var roleArr = roleName.split('-');        
        var role = roleArr[roleArr.length - 1];
        
        roleArr.pop();
        var roleString = roleArr.join('-');
        
        $("input[data-module='"+moduleName+"']").each((i, e) => {
            if($(e).is(':checked')){
                $("input[data-role='"+roleString+"-list']").prop('checked', true);
            }
        });

        /* if(role=='add' || role=='edit' || role=='delete' || role=='view' || role=='import' || role=='export'){            
            $("input[data-role='"+roleString+"-list']").prop('checked', true);
        } */
    //}
});

