$(document).ready(function () {
    'use strict';
    $("#start_date").change(function(){
        $("#end_date").attr('min', $("#start_date").val());
    });

    $.validator.addMethod("greaterThan", 
    function(value, element, params) {
        if($("#start_date").val() && $("#end_date").val()){
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) >= new Date($(params).val());
            }

            return isNaN(value) && isNaN($(params).val()) 
                || (Number(value) >= Number($(params).val())); 
        }else{
            return true;
        }
    },'Must be greater than start date.');

    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            "fields[]": { required: true },
            end_date: { greaterThan: "#start_date" }
        },
        messages: {
            "fields[]": { required: "Please select a field."},            
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