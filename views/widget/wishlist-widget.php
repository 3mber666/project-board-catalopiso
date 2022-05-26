<?php use Premmerce\Wishlist\WishlistPlugin; ?>

<?php echo $args['before_widget']; ?>

<?php if ($title) : ?>
    <?php echo $args['before_title'] . $title . $args['after_title']; ?>
<?php endif ?>

<div>
    <a href="<?php echo premmerce_wishlist()->getWishlistUrl(); ?>">
        <?php _e('Wishlist',WishlistPlugin::DOMAIN); ?>
        (<?php echo premmerce_wishlist()->wishlistTotal(); ?>)
    </a>
</div>

<?php echo $args['after_widget']; ?>
