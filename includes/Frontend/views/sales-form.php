<div class="wrap">
	<form action="" method="post" id="st-fe-sales-form">
		<div class="sale-tracker-form">
			<div class="st-form-item">
				<label for="amount"><?php esc_html_e( 'Amount', 'sales-tracker' ); ?></label>
				<input type="number" name="amount" id="amount" placeholder="<?php esc_attr_e( 'Amount', 'sales-tracker' ); ?>" value="">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="buyer"><?php esc_html_e( 'Buyer', 'sales-tracker' ); ?></label>
				<input type="text" name="buyer" id="buyer" placeholder="<?php esc_attr_e( 'Enter buyer name', 'sales-tracker' ); ?>" value="">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="receipt-id"><?php esc_html_e( 'Receipt ID', 'sales-tracker' ); ?></label>
				<input type="text" name="receipt_id" id="receipt-id" placeholder="<?php esc_attr_e( 'Enter receipt ID', 'sales-tracker' ); ?>" value="">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="items"><?php esc_html_e( 'Items', 'sales-tracker' ); ?></label>
				<textarea name="items" id="items" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Purchased Items', 'sales-tracker' ); ?>"></textarea>
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="buyer-email"><?php esc_html_e( 'Buyer Email', 'sales-tracker' ); ?></label>
				<input type="email" name="buyer_email" id="buyer-email" placeholder="<?php esc_attr_e( 'Enter buyer email', 'sales-tracker' ); ?>" value="">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="city"><?php esc_html_e( 'City', 'sales-tracker' ); ?></label>
				<input type="text" name="city" id="city" placeholder="<?php esc_attr_e( 'Enter city name', 'sales-tracker' ); ?>" value="">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="phone"><?php esc_html_e( 'Phone', 'sales-tracker' ); ?></label>
				<input type="tel" name="phone" id="phone" placeholder="<?php esc_attr_e( 'Enter phone number', 'sales-tracker' ); ?>" value="">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item">
				<label for="entry_by"><?php esc_html_e( 'Entry By', 'sales-tracker' ); ?></label>
				<input type="number" name="entry_by" id="entry_by" placeholder="<?php esc_attr_e( 'Staff ID', 'sales-tracker' ); ?>" value="">
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="note"><?php esc_html_e( 'Note', 'sales-tracker' ); ?></label>
				<textarea name="note" id="note" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Enter note', 'sales-tracker' ); ?>"></textarea>
				<span class="fe-validation-message"></span>
			</div>
			<div class="st-form-item st-item-full-width">
				<?php wp_nonce_field( 'st-fe-sales-form-nonce' ); ?>
				<input type="hidden" name="action" value="st_fe_sales_form">
				<input type="submit" name="st_fe_submit" value="<?php esc_html_e( 'Add Sale', 'sales-tracker' ); ?>">
			</div>
			
		</div>
	</form>
</div>
