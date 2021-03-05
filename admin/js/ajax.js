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
                dataType: 'json',
                success: function(res){
                    $('#list_cid').val(res.cid);
                }
            });
        });
    });
})(jQuery);