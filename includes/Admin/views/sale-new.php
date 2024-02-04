<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'New Sale', 'sales-tracker' ); ?></h1>
	<a href="<?php echo admin_url( 'admin.php?page=sales-tracker' ); ?>" class="page-title-action"><?php esc_html_e( 'View All Sales', 'sales-tracker' ); ?></a>
	<form action="" method="post">
		<div class="sale-tracker-form">
			<div class="st-form-item">
				<label for="amount"><?php esc_html_e( 'Amount', 'sales-tracker' ); ?></label>
				<input type="number" name="amount" id="amount" placeholder="<?php esc_attr_e( 'Amount', 'sales-tracker' ); ?>" value="">
				<?php if ( $this->has_error( 'amount' ) ) : ?>
					<p class="message error"><?php echo esc_html( $this->get_error( 'amount' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="st-form-item">
				<label for="buyer"><?php esc_html_e( 'Buyer', 'sales-tracker' ); ?></label>
				<input type="text" name="buyer" id="buyer" placeholder="<?php esc_attr_e( 'Enter buyer name', 'sales-tracker' ); ?>" value="">
				<?php if ( $this->has_error( 'buyer' ) ) : ?>
					<p class="message error"><?php echo esc_html( $this->get_error( 'buyer' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="receipt-id"><?php esc_html_e( 'Receipt ID', 'sales-tracker' ); ?></label>
				<input type="text" name="receipt_id" id="receipt-id" placeholder="<?php esc_attr_e( 'Enter receipt ID', 'sales-tracker' ); ?>" value="">
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="items"><?php esc_html_e( 'Items', 'sales-tracker' ); ?></label>
				<textarea name="items" id="items" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Purchased Items', 'sales-tracker' ); ?>"></textarea>
				<!-- Question: Will this be a repeater? Yes -->
			</div>
			<div class="st-form-item">
				<label for="buyer-email"><?php esc_html_e( 'Buyer Email', 'sales-tracker' ); ?></label>
				<input type="email" name="buyer_email" id="buyer-email" placeholder="<?php esc_attr_e( 'Enter buyer email', 'sales-tracker' ); ?>" value="">
				<?php if ( $this->has_error( 'buyer_email' ) ) : ?>
					<p class="message error"><?php echo esc_html( $this->get_error( 'buyer_email' ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="st-form-item">
				<label for="city"><?php esc_html_e( 'City', 'sales-tracker' ); ?></label>
				<input type="text" name="city" id="city" placeholder="<?php esc_attr_e( 'Enter city name', 'sales-tracker' ); ?>" value="">
			</div>
			<div class="st-form-item">
				<label for="phone"><?php esc_html_e( 'Phone', 'sales-tracker' ); ?></label>
				<input type="tel" name="phone" id="phone" placeholder="<?php esc_attr_e( 'Enter phone number', 'sales-tracker' ); ?>" value="">
			</div>
			<div class="st-form-item">
				<label for="entry_by"><?php esc_html_e( 'Entry By', 'sales-tracker' ); ?></label>
				<input type="number" name="entry_by" id="entry_by" placeholder="<?php esc_attr_e( 'Staff ID', 'sales-tracker' ); ?>" value="">
			</div>
			<div class="st-form-item st-item-full-width">
				<label for="note"><?php esc_html_e( 'Note', 'sales-tracker' ); ?></label>
				<textarea name="note" id="note" cols="30" rows="2" placeholder="<?php esc_attr_e( 'Enter note', 'sales-tracker' ); ?>"></textarea>
			</div>
			<?php wp_nonce_field( 'new-tracker-item' ); ?>
			<?php submit_button( esc_html__( 'Add Sale', 'sales-tracker' ), 'primary', 'add_tracker_item' ); ?>
		</div>
	</form>
</div>
