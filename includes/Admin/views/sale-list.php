<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Sales', 'sales-tracker' ); ?></h1>
	<a href="<?php echo admin_url( 'admin.php?page=sales-tracker&action=new' ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Sale', 'sales-tracker' ); ?></a>
	<?php if ( isset( $_GET['inserted'] ) ) : ?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Sale item has been inserted successfully!', 'sales-tracker' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( isset( $_GET['sale-deleted'] ) && $_GET['sale-deleted'] == 'true' ) : ?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Sale item has been deleted successfully!', 'sales-tracker' ); ?></p>
		</div>
	<?php endif; ?>
	<form action="" method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
		<?php
			$table = new Sales\Tracker\Admin\Sales_List();
			$table->prepare_items();
			$table->search_box( 'search', 'search_id' );
			$table->display();
		?>
	</form>
</div>
