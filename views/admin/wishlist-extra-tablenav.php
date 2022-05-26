<?php use Premmerce\Wishlist\WishlistPlugin; ?>

<div class="alignleft actions">

    <select name="deleteByModifiedDate" id="deleteByModifiedDate">
        <option value="-1"><?php _e('Modified date older than:', WishlistPlugin::DOMAIN); ?></option>

        <?php foreach ($modifiedDate as $key => $value): ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" class="button" value="<?php _e('Delete wishlists without user', WishlistPlugin::DOMAIN); ?>">
</div>
