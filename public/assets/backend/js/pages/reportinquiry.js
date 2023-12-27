$(document).ready(function () {
    'use strict';
});

$(document).on('click', '.sortColumn', function () {
    var column = $(this).data('column');
    var order = $('#sort_order').val();
    order = (order == 'asc') ? 'desc' : 'asc';
    $('#sort_by').val(column);
    $('#sort_order').val(order);
    fetch_data('report-inquiry', 1);
});

$('#searchFrm').on('submit', function (e) {
    e.preventDefault();
    console.log($('#search').val() == '' || $('#start_date').val() == '' || $('#end_date').val() == '');
    if ($('#search').val() == '' && $('#start_date').val() == '' && $('#end_date').val() == '') {
        $('#error-head').removeAttr('hidden');
        fetch_data('report-inquiry', 1);
    } else if ($('#search').val() != '' || $('#start_date').val() != '' || $('#end_date').val() != '') {
        $('#error-head').attr('hidden', 'hidden');
        $('#end_date').css({ 'border-color': '', 'color': '' });
        fetch_data('report-inquiry', 1);
    } else {
        $('#end_date').css({ 'border-color': '', 'color': '' });
        fetch_data('report-inquiry', 1);
    }
});

$("#per_page").on('change', function (e) {
    e.preventDefault();
    fetch_data('report-inquiry', 1);
});

$(document).on('click', '#report-inquiry_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('report-inquiry', page);
});

$('#start_date').on('submit', function (e) {
    e.preventDefault();
    fetch_data('report-inquiry', 1);
});

$('#start_date').on('change', function (e) {
    e.preventDefault();
    console.log($('#end_date').val() == '');
    if ($('#end_date').val() != '' && $('#start_date').val() > $('#end_date').val()) {
        $('#start_date').css({ "border-color": 'red', 'color': 'red' });
        $('#start_date').val('');
    } else {
        $('#start_date').css({ 'border-color': '', 'color': '' });
    }
})

$('#end_date').on('change', function (e) {
    e.preventDefault();
    if ($('#end_date').val() < $('#start_date').val()) {
        $('#end_date').css({ "border-color": 'red', 'color': 'red' });
        // $('#end_date').attr('type','text');
        // $('#end_date').removeAttr('onfocus');
        // $('#end_date').attr({'data-toggle':"tooltip", 'data-placement':"top", 'title':"End date cannot be less than start date"});
        // $('#end_date').append('<style>.end_date::placeholder{color:red}</style>')

        // $('#end_date').attr('placeholder','End date cannot be less than start date');
        // $('#end_date').after('<span class="error">End date cannot be less than start date</span>');
        $('#end_date').val('');
    } else {
        $('#end_date').css({ 'border-color': '', 'color': '' });
    }
})

$('#end_date').on('submit', function (e) {
    e.preventDefault();
    fetch_data('report-inquiry', 1);
});

$('#export-button').on('click', function (e) {
    e.preventDefault();
    $.ajax({
        xhrFields: {
            responseType: 'blob',
        },
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': _token,
        },
        url: '/admin/report-inquiry/export',
        data: {
            search: $('#search').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
        },
        success: function (result, status, xhr) {
            var disposition = xhr.getResponseHeader('content-disposition');

            var filename = disposition.split('=')[1];
            var blob = new Blob([result], {
                type: 'application/vnd.ms-excel'
            });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;

            document.body.appendChild(link);

            link.click();
            document.body.removeChild(link);
        }
    });
});