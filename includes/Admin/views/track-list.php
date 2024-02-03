<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Sales', 'sales-tracker' ); ?></h1>
    <a href="<?php echo admin_url( 'admin.php?page=sales-tracker&action=new'); ?>" class="page-title-action"><?php esc_html_e('Add New', 'sales-tracker')?></a>
    <form action="" method="post">
        <?php
            $table = new Sales\Tracker\Admin\Track_list();
            $table->prepare_items();
            $table->search_box( 'search', 'search_id' );
            $table->display();
        ?>
    </form>
</div>