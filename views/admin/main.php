<?php use Premmerce\Wishlist\WishlistPlugin; ?>

<div class="wrap">
    <h2><?php _e('Premmerce WooCommerce Wishlist', WishlistPlugin::DOMAIN) ?></h2>
    <h2 class="nav-tab-wrapper">
		<?php foreach($tabs as $tab => $name): ?>
			<?php $class = ($tab == $current)? ' nav-tab-active' : ''; ?>
            <a class='nav-tab<?php echo $class ?>'
               href='?page=premmerce-wishlist&tab=<?php echo $tab ?>'><?php echo $name ?></a>
		<?php endforeach; ?>
    </h2>
	<?php $file = __DIR__ . "/tabs/{$current}.php" ?>
	<?php if(file_exists($file)): ?>
		<?php include $file ?>
	<?php endif; ?>
</div>

