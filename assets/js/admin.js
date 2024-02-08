; (function ($) {
	const salesForm = $('#sales-form');
	const notificationWrapper = $('.st-notification');
	const notificationText = $('.st-notification p');
	const dismissButton = $('.st-notification-dismiss');
	const submitButton = $('.st_sale_submission_button');


	// Validate the form
	const inputElements = [
		'#buyer',
		'#amount',
		'#receipt_id',
		'#items',
		'#buyer_email',
		'#note',
		'#city',
		'#phone',
		'#entry_by'
	];


	/**
	 *  Validate the input fields
	 */
	function validateInput(event) {
		const input = $(event.target);
		const value = input?.val()?.trim();
		const validationMessage = input.next('.fe-validation-message');
		validationMessage.text('');
		input.removeClass('error');

		const isValid = {
			valid: true,
			message: ''
		};

		switch (input.attr('id')) {
			case 'amount':
			case 'phone':
			case 'entry_by':
				isValid.valid = /^\d+$/.test(value);
				isValid.message = 'Please enter only numbers.';
				break;
			case 'buyer':
				isValid.valid = /^[a-zA-Z0-9\s]{1,20}$/.test(value);
				isValid.message = 'Please enter text, spaces, and numbers only, not more than 20 characters.';
				break;
			case 'receipt_id':
			case 'items':
			case 'city':
				isValid.valid = /^[a-zA-Z\s]*$/.test(value);
				isValid.message = 'Please enter only text and spaces.';
				break;
			case 'buyer_email':
				isValid.valid = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value);
				isValid.message = 'Please enter a valid email.';
				break;
			case 'note':
				isValid.valid = /^[\s\S]{0,30}$/.test(value);
				isValid.message = 'Please enter not more than 30 words.';
				break;
		}

		if (!isValid.valid) {
			input.addClass('error');
			validationMessage.text(isValid.message);
		} else {
			input.removeClass('error');
		}
		updateSubmitButton();
	}

	/**
	 * Update the submit button
	 */
	function updateSubmitButton() {
		const allFieldsValid = inputElements.every(
			(selector) => {
				const input = $(selector);
				const value = input.val()?.trim();
				const validationMessage = input.next('.fe-validation-message').text();
				return value !== '' && validationMessage === '';
			}
		);

		submitButton.prop('disabled', !allFieldsValid);
	}

	// Attach event listeners for input validation
	inputElements.forEach(
		(selector) => {
			$(selector).on('keyup change', validateInput);
		}
	);

	// Initial validation for form fields
	updateSubmitButton();


	function formSubmission() {
		salesForm.on(
			'submit',
			function (event) {
				event.preventDefault();
				const data = salesForm.serialize();

				$.post(
					salesTracker.ajax_url,
					data,
					response => {
						if (response?.success) {
							notificationText.html(response.data.message);
							notificationWrapper.addClass('success show');
							salesForm.trigger('reset');
							// Disable the submit button
							submitButton.prop('disabled', true);
						} else if (response?.data?.nonce_error) {
							notificationText.html(response.data.nonce_error_message);
							notificationWrapper.addClass('error show');
						} else {
							notificationText.html(response.data.message);
							notificationWrapper.addClass('error show');
						}
					}
				)
					.fail(
						response => {
							notificationText.html(response.data.message);
							notificationWrapper.addClass('error show');
							salesForm.trigger('reset');
						}
					);
			}
		);

		dismissButton.on(
			'click',
			() => {
				notificationWrapper.removeClass('success show');
			}
		);
	}

	// Call the function
	formSubmission();
}(jQuery));
