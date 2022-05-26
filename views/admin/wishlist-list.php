<?php use Premmerce\Wishlist\WishlistPlugin; ?>

<div class="wrap">

    <h1 class="wp-heading-inline"><?php _e('Premmerce WooCommerce Wishlist',WishlistPlugin::DOMAIN); ?></h1>

    <hr class="wp-header-end">

    <form action="" method="POST">
        <?php
            $table->search_box(__('Search by User',WishlistPlugin::DOMAIN),'search');
            $table->display();
        ?>
    </form>

</div>
