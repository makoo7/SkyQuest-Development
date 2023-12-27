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

    'use strict';
    
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            report_selection: { required: true },
            'reports[]': { required: '#selected_reports:checked' },
            single_ppt_price: { required: true, number: true },
            single_word_price: { required: true, number: true },
            single_excel_price: { required: true, number: true },
            single_powerBI_price: { required: true, number: true },
            multiple_ppt_price: { required: true, number: true },
            multiple_word_price: { required: true, number: true },
            multiple_excel_price: { required: true, number: true },
            multiple_powerBI_price: { required: true, number: true },
            enterprise_ppt_price: { required: true, number: true },
            enterprise_word_price: { required: true, number: true },
            enterprise_excel_price: { required: true, number: true },
            enterprise_powerBI_price: { required: true, number: true },
            
        },
        messages: {
            report_selection: { required: "Please select any one."},
            'reports[]': { required: "Please select reports."},
            single_ppt_price: { required: "Please enter price.", number: "Please provide a valid price." },
            single_word_price: { required: "Please enter price.", number: "Please provide a valid price." },
            single_excel_price: { required: "Please enter price.", number: "Please provide a valid price." },
            single_powerBI_price: { required: "Please enter price.", number: "Please provide a valid price." },
            multiple_ppt_price: { required: "Please enter price.", number: "Please provide a valid price." },
            multiple_word_price: { required: "Please enter price.", number: "Please provide a valid price." },
            multiple_excel_price: { required: "Please enter price.", number: "Please provide a valid price." },
            multiple_powerBI_price: { required: "Please enter price.", number: "Please provide a valid price." },
            enterprise_ppt_price: { required: "Please enter price.", number: "Please provide a valid price." },
            enterprise_word_price: { required: "Please enter price.", number: "Please provide a valid price." },
            enterprise_excel_price: { required: "Please enter price.", number: "Please provide a valid price." },
            enterprise_powerBI_price: { required: "Please enter price.", number: "Please provide a valid price." },
        },
    });
});