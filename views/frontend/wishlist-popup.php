<?php
    use Premmerce\Wishlist\WishlistPlugin;

    global $premmerce_wishlist_frontend;
?>

<div class="wl-modal wl-modal--sm">

    <div class="wl-modal__header">
        <div class="wl-modal__header-title">
            <?php echo $title ?>
        </div>
        <div class="wl-modal__header-close" data-modal-close>
            <i class="wl-modal__header-close-ico">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                    <path d="M.66.7l.71-.71 15 15-.71.71z"/>
                    <path d="M16.02 1.06l-.7-.72-15.3 15.3.72.72z"/>
                </svg>
            </i>
        </div>
    </div>

    <form method="post" action="<?php echo $routeUrl; ?>" data-wishlist-ajax>

        <div class="wl-modal__content">

            <?php if($success) :?>
                <div class="typo">
                    <?php _e('Item successfuly added to your Wishlist!',WishlistPlugin::DOMAIN); ?>
                </div>
            <?php else: ?>
                <div class="wl-form">

                    <!-- Default list -->
                    <?php if (!count($wishlists)): ?>
                        <div class="wl-form__field">
                            <label class="wl-form__checkbox">
                                <span class="wl-form__checkbox-field">
                                    <input type="radio" value="0" name="wishlist_id" checked>
                                </span>
                                <span class="wl-form__checkbox-inner">
                                    <span class="wl-form__checkbox-title">
                                        <?php echo \Premmerce\Wishlist\Models\WishlistModel::$default_name ?>
                                    </span>
                                </span>
                            </label>
                        </div>
                    <?php endif; ?>

                    <!-- User lists -->
                    <?php if (count($wishlists)): ?>
                        <div class="wl-form__field">
                            <div class="wl-form__label">
                                <?php _e('Select a list',WishlistPlugin::DOMAIN); ?>
                            </div>
                            <div class="wl-form__inner">
                                <?php foreach ($wishlists as $wishlist): ?>
                                    <label class="wl-form__checkbox">
                                        <span class="wl-form__checkbox-field">
                                            <?php if($isMove) :?>
                                                <input type="radio" required value="<?php echo $wishlist['wishlist_key'] ?>" name="wishlist_move_to"
                                                    <?php echo premmerce_wishlist()->isProductInWishlist($productId, $wishlist['wishlist_key']) ? 'disabled' : ''?>
                                                >
                                            <?php else : ?>
                                                <?php $checked = $wishlist['default'] ? 'checked': ''; ?>
                                                <input type="radio" required <?php echo $checked; ?> value="<?php echo $wishlist['ID'] ?>" name="wishlist_id">
                                            <?php endif; ?>
                                        </span>
                                        <span class="wl-form__checkbox-inner">
                                            <span class="wl-form__checkbox-title">
                                                <?php echo $wishlist['name']; ?>
                                            </span>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Create a new list -->
                    <?php if(!$isMove) :?>
                        <div class="wl-form__field" data-wishlist-new-scope>
                            <div class="wl-form__label">
                                <?php _e('Create a new list',WishlistPlugin::DOMAIN); ?>
                            </div>
                            <div class="wl-form__inner">
                                <label class="wl-form__checkbox">
                                <span class="wl-form__checkbox-field">
                                    <input type="radio" value="-1" name="wishlist_id" data-wishlist-new-radio>
                                </span>
                                    <span class="wl-form__checkbox-inner">
                                    <span class="wl-form__checkbox-title">
                                        <input class="wl-form-control" type="text" name="wishlist_name" data-wishlist-new-input>
                                    </span>
                                </span>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                </div><!-- /.form -->
            <?php endif; ?>

        </div><!-- /.modal__content -->

        <div class="wl-modal__footer">
            <div class="wl-modal__footer-row">
                <div class="wl-modal__footer-btn hidden-xs">
                    <button class="button alt" type="reset"
                            data-modal-close>
                        <?php _e('Close',WishlistPlugin::DOMAIN); ?>
                    </button>
                </div>
                <div class="wl-modal__footer-btn">

                    <?php if($success) :?>
                        <a class="button alt" href="<?php echo home_url($pageSlug); ?>"
                           data-button-loader="button">
                            <?php _e('View Project Board',WishlistPlugin::DOMAIN); ?>
                        </a>
                    <?php else: ?>
                        <button class="button alt" type="submit" data-button-loader="button">
                            <?php if($isMove) :?>
                                <span><?php _e('Move',WishlistPlugin::DOMAIN); ?></span>
                            <?php else: ?>
                                <span><?php _e('Add',WishlistPlugin::DOMAIN); ?></span>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <?php if($success) :?>
            <div hidden data-ajax-grab="wishlist-link--<?php echo $productId; ?>">
                <?php $premmerce_wishlist_frontend->renderWishlistBtn($productId); ?>
            </div>
        <?php endif; ?>

        <?php if($isMove): ?>
            <input type="hidden" name="wishlist_key" value="<?php echo $wishlist_key; ?>">
            <input type="hidden" name="product_ids[]" value="<?php echo $productId; ?>">
            <input type="hidden" name="move" value="true">
        <?php else: ?>
            <input type="hidden" name="submit" id="submit" class="button alt" value="<?php _e('Wishlist', WishlistPlugin::DOMAIN) ?>"/>
            <input type="hidden" name="wishlist_product_id" value="<?php echo $productId; ?>">
        <?php endif; ?>
        <?php wp_nonce_field('wp_rest'); ?>
    </form>
</div>