function removeBookmark(user_id,entity_type,entity_id){
    //alert(user_id + ',' + entity_type + ',' + entity_id);
    $.ajax({
        type: "POST",
        data: { user_id: user_id, entity_type: entity_type, entity_id: entity_id },
        headers: {
            'X-CSRF-TOKEN': _token
        },
        url: baseUrl + "removeBookmark",
        success: function (data) {
            if (data.success == "1") {
                //$("#bookmarktag").attr("src", data.html);
                if(entity_type=='insight'){
                    $("#insightData[data-id='"+entity_id+"']").hide();
                    if(data.count==0) $("#insightHead").hide();
                } else if(entity_type=='casestudy'){
                    $("#casestudyData[data-id='"+entity_id+"']").hide();
                    if(data.count==0) $("#casestudyHead").hide();
                } else if(entity_type='report'){
                    $("#reportData[data-id='"+entity_id+"']").hide();
                    if(data.count==0) $("#reportHead").hide();
                }
                
                if(data.total_count==0){
                    $("#myBookmarkSection").html("<h3 class='no-data-text text-center'>No Bookmarks Found!</h3>");
                }

                toastr.success(data.message);
            }
        },
        error: function (data, e) {
            toastr.error("Something went wrong. Please try again later.");
        }
    });
}