$(document).ready(function () {
    'use strict';
});

$(document).on('click', '.sortColumn', function () {
    var column = $(this).data('column');
    var order =$('#sort_order').val();
    order = (order == 'asc')?'desc':'asc';
    $('#sort_by').val(column);
    $('#sort_order').val(order);
    fetch_data('free-sample-request',1);
});

$('#searchFrm').on('submit', function(e){
    e.preventDefault();
    fetch_data('free-sample-request',1);
});

$("#per_page").on('change', function(e) {
    e.preventDefault();
    fetch_data('free-sample-request',1);
});

$(document).on('click', '#free-sample-request_nav a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $("#page").val(page);
    fetch_data('free-sample-request',page);
});