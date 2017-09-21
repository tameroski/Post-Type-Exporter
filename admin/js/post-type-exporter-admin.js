(function( $ ) {
	'use strict';

	$(document).ready(function(){

		// Showing the export form for each CPT
		for(var i = 0; i < data.post_types.length; i++){
			$($('.edit-php.post-type-'+data.post_types[i]+' .wrap .page-title-action')[0]).after('<div id="pte-export"><input id="" class="datepicker start" placeholder="'+ data.label_start +'"/><input class="datepicker end" placeholder="'+ data.label_end +'"/><a href="#" class="page-title-action" data-post-type="'+data.post_types[i]+'">'+ data.label_export_button +'</a><div class="message"></div></div>');
		}
		
		// Date picker init
		$('.datepicker').pickadate({
			format: 'dd/mm/yyyy',
		});

		// Submit
		$('#pte-export a').click(function(e){
			e.preventDefault();

			var start = $('#pte-export .start').val();
			var end = $('#pte-export .end').val();
			var post_type = $(this).data('post-type');
			var $message = $('#pte-export .message');

			$message.text('');

			if (start.length == 0){
				$message.text(data.error_start);
			}else if (end.length == 0){
				$message.text(data.error_end);
			}else if (post_type.length == 0){
				$message.text(data.error_cpt);
			}else{
				document.location = data.link + "?post_type="+post_type+"&start=" + start + "&end=" + end;
			}
		});
	});

})( jQuery );
