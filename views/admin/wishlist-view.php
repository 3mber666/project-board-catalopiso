<?php use Premmerce\Wishlist\WishlistPlugin; ?>

<div class="wrap">

    <h1 class="wp-heading-inline"><?php echo _e('Wishlist', WishlistPlugin::DOMAIN) . ': ' . $wishlistName; ?></h1>

    <a href="<?php echo admin_url('admin.php') . '?page=premmerce-wishlist' ?>" class="page-title-action">
		<?php echo '← ' . __('Back to Premmerce WooCommerce Wishlist', WishlistPlugin::DOMAIN); ?>
    </a>

    <hr class="wp-header-end">

    <form method="post" action="" class="wishlist-products">
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="name"><?php _e('User', WishlistPlugin::DOMAIN) ?></label>
                </th>
                <td>
                    <p>
						<?php if($userLink): ?>
                            <a href="<?php echo $userLink; ?>">
								<?php echo $userName; ?>
                            </a>
						<?php else: ?>
							<?php echo $userName; ?>
						<?php endif; ?>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>

		<?php $table->display(); ?>
    </form>
</div>
