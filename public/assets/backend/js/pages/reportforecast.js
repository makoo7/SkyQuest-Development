$(document).ready(function () {
    if($("#selected_reports").val()=='selection'){
        $("#selectionTab").show();
    }else{
        $("#selectionTab").hide();
    }

    $("#selected_reports").change(function () {
        $("#selectionTab").show();
    });

    $("#all_reports").change(function () {
        $("#selectionTab").hide();
    });

    $("#reports").select2({
        placeholder: "Select Reports",
        allowClear: true,
    })

    $('#forecast_period').mask('0000-0000');

    'use strict';
    
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            report_selection: { required: true },
            'reports[]': { required: '#selected_reports:checked' },
            historical_year: { required: true, number: true, minlength: 4, maxlength: 4 },
            base_year: { required: true, number: true, minlength: 4, maxlength: 4 },
            forecast_year: { required: true, number: true, minlength: 4, maxlength: 4 },
            forecast_period: { required: true, minlength: 9, maxlength: 9 },
            
        },
        messages: {
            report_selection: { required: "Please select any one."},
            'reports[]': { required: "Please select reports."},
            historical_year: { required: "Please enter historical year." },
            base_year: { required: "Please enter base year." },
            forecast_year: { required: "Please enter forecast year." },
            forecast_period: { required: "Please enter forecast period." },
        },
    });
});