<?php use Premmerce\Wishlist\WishlistPlugin; ?>

<h2><?php _e('Premmerce WooCommerce Wishlist', WishlistPlugin::DOMAIN); ?></h2>

<table class="wp-list-table widefat fixed striped list">
    <thead>
        <tr>
            <th scope="col" id="name" class="manage-column column-name"><?php _e('Name',WishlistPlugin::DOMAIN); ?></th>
            <th scope="col" id="products" class="manage-column column-products"><?php _e('Products count',WishlistPlugin::DOMAIN); ?></th>
            <th scope="col" id="date_created" class="manage-column column-date_created"><?php _e('Created',WishlistPlugin::DOMAIN); ?></th>
            <th scope="col" id="date_modified" class="manage-column column-date_modified"><?php _e('Modified',WishlistPlugin::DOMAIN); ?></th>
        </tr>
    </thead>

    <tbody id="the-list">
        <?php if (count($wishlists)): ?>
            <?php foreach ($wishlists as $item): ?>
                <tr>
                    <td class="name column-name" data-colname="Name">
                        <a href="<?php echo admin_url('admin.php').'?page=premmerce-wishlist&wl-action=view&wl-id='.$item['ID']; ?>"><?php echo $item['name']; ?></a>
                    </td>
                    <td class="products column-products" data-colname="Products count">
                        <?php echo count($item['products']); ?>
                    </td>
                    <td class="date_created column-date_created" data-colname="Created">
                        <time>
                            <?php echo date_i18n(get_option( 'date_format' ), strtotime($item['date_created']));  ?>
                        </time>
                    </td>
                    <td class="date_modified column-date_modified" data-colname="Modified">
                        <time>
                            <?php echo date_i18n(get_option( 'date_format' ), strtotime($item['date_modified']));  ?>
                        </time>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" align="center"><?php _e('No wishlists',WishlistPlugin::DOMAIN); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
