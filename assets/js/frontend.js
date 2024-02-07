; (function ($) {
	$( '#st-fe-sales-form' ).on(
		'submit',
		function (e) {
			e.preventDefault();

			$this = $( this );

			const data = $( this ).serialize();
			$.post(
				salesTracker.ajax_url,
				data,
				function (response) {
					if (response.success) {
						console.log( response.data.message );
						// Reset the form
						console.log( $this[0].reset() );

					} else {
						console.log( response.data.message );
					}
				}
			)
			.fail(
				function (response) {
					console.log( salesTracker.submission_error );
				}
			)
		}
	);
}(jQuery));