(function( $ ) {
	
	$(document).ready(function(){

		var themes = $('.list-item').length;
		var i = 0;
		$('#choose-themes').html(i);
		$('#count-themes').html(themes);

		$('#mailtrain-next').on('click',function(){
			var name = $('#mailtrain_name').val();
			var email = $('#mailtrain_email').val();
			if(name !== '' && email !== '') {
				$('#mailtrain_name').removeClass('border-danger');
				$('#mailtrain_email').removeClass('border-danger');
				if($('#user-data').is(':visible')){
					$('#user-data').slideUp();
				}
				$('#name-user').html(name);
				$('#lists').slideDown();
			} else {
				$('#mailtrain_name').addClass('border-danger');
				$('#mailtrain_email').addClass('border-danger');
			}
		});
		$('#prev-button').on('click',function(){
			if($('#user-data').not(':visible')){
				$('#user-data').slideDown();
			}
			$('#lists').slideUp();
		});
		$('.list-item-select').on('click',function(){
			if($(this).is(':checked')){
				i++;
				$(this).parent().parent().parent().addClass('bg-warning');
				$('#choose-themes').html(i);
			} else {
				i--;
				$(this).parent().parent().parent().removeClass('bg-warning');
				$('#choose-themes').html(i);
			}
			
		});

	});

})( jQuery );
