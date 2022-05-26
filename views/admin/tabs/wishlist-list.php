<?php use Premmerce\Wishlist\WishlistPlugin; ?>

<div class="wrap">

    <form action="" method="POST">
        <?php
            $table->search_box(__('Search by User',WishlistPlugin::DOMAIN),'search');
            $table->display();
        ?>
    </form>

</div>
