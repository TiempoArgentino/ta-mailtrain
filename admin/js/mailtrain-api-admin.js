(function( $ ) {
	$(document).ready(function(){
		var i=1;
		$('#add_field').on('click',function(){
			i++;
			var campo = `<tr class="extra-field" id="extra-field-${i}">
			<th scope="row" class="label-row"><label>Label</label></th><td><input type="text" class="large-text" name="extra-field[]"  /></td>
			<th scope="row" class="label-row"><label>Merge</label></th><td><input type="text" class="large-text medium merge-field" name="extra-field-merge[]"  /><span class="dashicons-trash dashicons remove-price" data-id="#extra-field-${i}" style="cursor:pointer"></span></td>
			</tr>`;
			$('#extra_fields tbody').append(campo);
		});
	});
	
	$(document).on('click', '.remove-price', function() {
		var id = $(this).data('id');
		$(id).remove();
	});
})( jQuery );
