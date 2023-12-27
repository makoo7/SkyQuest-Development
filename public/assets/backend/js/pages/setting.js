$(document).ready(function () {
    'use strict';
    $("#frmAddUpdate").validate({
        ignore: '',
        errorElement: 'span',
        rules: {
            satisfied_customers: { required: true, number: true },
            customer_retention_rate: { required: true, number: true },
            years_in_business: { required: true, number: true },
            country_network: { required: true, number: true },
            team_members: { required: true, number: true },
            years_of_team_experience: { required: true, number: true },
            forecast_year: { required: true },
            
        },
        messages: {
            satisfied_customers: { 
                required: "Please provide no of satisfied customers.", 
                number: "Please provide a valid number."},
            customer_retention_rate: { 
                required: "Please provide no of customer retention rate.", 
                number: "Please provide a valid number."},
            years_in_business: { 
                required: "Please provide no of years in business.", 
                number: "Please provide a valid number."},
            country_network: { 
                required: "Please provide no of country network.", 
                number: "Please provide a valid number."},
            team_members: { 
                required: "Please provide no of team members.", 
                number: "Please provide a valid number."},
            years_of_team_experience: { 
                required: "Please provide no of years of team experience.", 
                number: "Please provide a valid number."},
            forecast_year: { 
                required: "Please provide forecast year." },
        },
    });
});