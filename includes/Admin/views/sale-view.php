<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Purchase History', 'sales-tracker' ); ?></h1>
    <a href="<?php echo admin_url( 'admin.php?page=sales-tracker' ); ?>" class="page-title-action"><?php esc_html_e( 'View All Sales', 'sales-tracker' ); ?></a>
    <div class="single-sale-item">
        <?php
        $fields = [
            'amount' => 'Amount',
            'buyer' => 'Buyer',
            'receipt_id' => 'Receipt ID',
            'items' => 'Items',
            'buyer_email' => 'Buyer Email',
            'city' => 'City',
            'phone' => 'Phone',
            'entry_by' => 'Entry By',
            'note' => 'Note'
        ];

        foreach ( $fields as $field => $label ) {
            if ( ! empty( $sale_item->$field ) ) {
                $class = in_array( $field, ['receipt_id', 'items', 'note'] ) ? 'st-item-full-width' : '';
                ?>
                <div class="st-sale-item <?php echo esc_attr( $class ); ?>">
                    <p><?php esc_html_e( $label . ':', 'sales-tracker' ); ?></p>
                    <p><?php esc_html_e( $sale_item->$field ); ?></p>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>