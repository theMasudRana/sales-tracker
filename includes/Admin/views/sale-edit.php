<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Sale', 'sales-tracker' ); ?></h1>
	<hr class="wp-header-end">
	<div class="st-notification">
		<p></p>
		<button class="st-notification-dismiss">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
				<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
			</svg>
		</button>
	</div>

	<form action="" method="post" id="sales-form">
		<div class="sale-tracker-form">
			<div class="st-form-item">
				<label for="amount"><?php esc_html_e( 'Amount', 'sales-tracker' ); ?></label>
				<input type="number" name="amount" id="amount" placeholder="<?php esc_attr_e( 'Amount', 'sales-tracker' ); ?>" value="<?php echo esc_attr( $sale_item->amount ); ?>">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="buyer"><?php esc_html_e( 'Buyer', 'sales-tracker' ); ?></label>
				<input type="text" name="buyer" id="buyer" placeholder="<?php esc_attr_e( 'Enter buyer name', 'sales-tracker' ); ?>" value="<?php echo esc_attr( $sale_item->buyer ); ?>">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="receipt_id"><?php esc_html_e( 'Receipt ID', 'sales-tracker' ); ?></label>
				<input type="text" name="receipt_id" id="receipt_id" placeholder="<?php esc_attr_e( 'Enter receipt ID', 'sales-tracker' ); ?>" value="<?php echo esc_attr( $sale_item->receipt_id ); ?>">
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="items"><?php esc_html_e( 'Items', 'sales-tracker' ); ?></label>
				<textarea name="items" id="items" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Purchased Items', 'sales-tracker' ); ?>"><?php echo esc_textarea( $sale_item->items ); ?></textarea>
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="buyer_email"><?php esc_html_e( 'Buyer Email', 'sales-tracker' ); ?></label>
				<input type="email" name="buyer_email" id="buyer_email" placeholder="<?php esc_attr_e( 'Enter buyer email', 'sales-tracker' ); ?>" value="<?php echo esc_attr( $sale_item->buyer_email ); ?>">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="city"><?php esc_html_e( 'City', 'sales-tracker' ); ?></label>
				<input type="text" name="city" id="city" placeholder="<?php esc_attr_e( 'Enter city name', 'sales-tracker' ); ?>" value="<?php echo esc_attr( $sale_item->city ); ?>">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="phone"><?php esc_html_e( 'Phone', 'sales-tracker' ); ?></label>
				<input type="tel" name="phone" id="phone" placeholder="<?php esc_attr_e( 'Enter phone number', 'sales-tracker' ); ?>" value="<?php echo esc_attr( $sale_item->phone ); ?>">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="entry_by"><?php esc_html_e( 'Entry By', 'sales-tracker' ); ?></label>
				<input type="number" name="entry_by" id="entry_by" placeholder="<?php esc_attr_e( 'Staff ID', 'sales-tracker' ); ?>" value="<?php echo esc_attr( $sale_item->entry_by ); ?>">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="note"><?php esc_html_e( 'Note', 'sales-tracker' ); ?></label>
				<textarea name="note" id="note" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Enter note', 'sales-tracker' ); ?>"><?php echo esc_textarea( $sale_item->note ); ?></textarea>
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-actions-wrapper">
				<?php wp_nonce_field( 'st-sale-submission-nonce' ); ?>
				<input type="hidden" name="id" value="<?php echo esc_attr( $sale_item->id ); ?>">
				<input type="hidden" name="action" value="st-sale-submission-action">
				<input type="submit" name="st_sale_submission_button" value="<?php esc_html_e( 'Update Sale', 'sales-tracker' ); ?>" class="st_sale_submission_button" disabled>
			</div>
		</div>
	</form>
</div>