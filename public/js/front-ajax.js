(function($) {

    // $(document).ready(function(){
    //     $('#button-ma-widget-front').on('click',function(){
    //       var the_email = $('#the_email').val();
    //       var the_list = $('#the_list').val();
    //           $.ajax({
    //               type: 'post',
    //               url: widget_front_ajax.url,
    //               data:{
    //                   action: widget_front_ajax.action,
    //                   _ajax_nonce: widget_front_ajax._ajax_nonce,
    //                   the_email: the_email,
    //                   the_list: the_list
    //               },
    //               success: function(response){
    //                   console.log('listo '+ response)
    //               },
    //               error: function(response){
    //                   console.log('el error '+ response)
    //               }        
    //           });
    //     });
    // });

    $(document).on('click','#finish-button',function(){

        var name = $('#mailtrain_name').val();
        var email = $('#mailtrain_email').val();
        var terms = $('#terms-and-conditions').is(':checked') ? 'yes' : 'no';
        var lists = $('.list-item-select');
        var id = $('#mailtrain_user_id').val();
        var list = [];
        $.each(lists, function() {
            if($(this).is(':checked')) {
                list.push($(this).val());
            }
        });

        list.join(',');

        $.ajax({
            type: 'post',
            url: ajax_mailtrain.url,
            data: {
                action: ajax_mailtrain.action,
                _ajax_nonce: ajax_mailtrain._ajax_nonce,
                name: name,
                email: email,
                lists: list,
                terms: terms,
                id: id
            },
            success: function(response){
                console.log(response)
                if(response === 'ok') {
                    $('#msg-ok').html('');
                    $('#thanks-newsletter').slideDown();
                    $('#lists').slideUp();
                    $('#mailtrain_name').val('');
                    $('#mailtrain_email').val('');
                    if($('#terms-and-conditions').is(':checked')){
                        $('#terms-and-conditions').prop('checked',false);
                    }
                } else {
                    $('#msg-ok').html(response);
                }
               
            },
            error: function(response) {
                console.log(response);
            }
        });
    });


})(jQuery);