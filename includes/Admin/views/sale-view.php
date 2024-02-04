<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Purchase History', 'sales-tracker' ); ?></h1>
    <div class="single-sale-item">
        <div class="st-sale-item">
            <p><?php esc_html_e( 'Amount:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->amount ); ?></p>
        </div>
        <div class="st-sale-item">
            <p><?php esc_html_e( 'Buyer:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->buyer ); ?></p>
        </div>
        <div class="st-sale-item st-item-full-width">
            <p><?php esc_html_e( 'Receipt ID:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->receipt_id ); ?></p>
        </div>
        <div class="st-sale-item st-item-full-width">
            <p><?php esc_html_e( 'Items:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->items ); ?></p>
        </div>
        <div class="st-sale-item">
            <p><?php esc_html_e( 'Buyer Email:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->buyer_email ); ?></p>
        </div>
        <div class="st-sale-item">
            <p><?php esc_html_e( 'City:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->city ); ?></p>
        </div>
        <div class="st-sale-item">
            <p><?php esc_html_e( 'Phone:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->phone ); ?></p>
        </div>
        <div class="st-sale-item">
            <p><?php esc_html_e( 'Entry By:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->entry_by ); ?></p>
        </div>
        <div class="st-sale-item st-item-full-width">
            <p><?php esc_html_e( 'Note:', 'sales-tracker' ); ?></p>
            <p><?php esc_html_e( $sale_item->note ); ?></p>
        </div>
    </div>
</div>