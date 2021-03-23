(function($){
    $(document).ready(function(){
        $('#list_id').on('change',function(){
            var id = $(this).val();
            $.ajax({
                type:'post',
                url: ajax_var.url,
                data:{
                    action: ajax_var.action,
                    nonce: ajax_var.nonce,
                    id: id
                },
                success: function(res){
                    var response = $.parseJSON(res);
                    $('#list_cid').val(response['cid']);
                }
            });
        });
    });
})(jQuery);