$(document).ready(function(){
    // For A Delete Record Popup
    $('.remove-record').click(function() {
        var url = $(this).attr('data-url');
        $(".remove-record-model").attr("action",url);
    });

    $('.remove-data-from-delete-form').click(function() {
        $(".remove-record-model").attr("action",'');
    });
    
    $('.modal').click(function() {
        // $(".remove-record-model").attr("action",'');
    });
});