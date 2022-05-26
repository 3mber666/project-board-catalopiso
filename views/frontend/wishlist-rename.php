<?php use Premmerce\Wishlist\WishlistPlugin; ?>


<div class="wl-modal modal--sm">
    <div class="wl-modal__header">
        <div class="wl-modal__header-title">
            <?php _e('Rename', WishlistPlugin::DOMAIN) ?>
        </div>
        <div class="wl-modal__header-close" data-modal-close>
            <i class="wl-modal__header-close-ico">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M.66.7l.71-.71 15 15-.71.71z"/><path d="M16.02 1.06l-.7-.72-15.3 15.3.72.72z"/></svg>
            </i>
        </div>
    </div>
    <form method="post" action="<?php echo $apiUrlWishListPage; ?>" data-wishlist-ajax>
        <div class="wl-modal__content">
            <div class="wl-form">
                <div class="wl-form__field">
                    <div class="wl-form__label">
                        <?php _e('List name',WishlistPlugin::DOMAIN); ?>
                        <span class="wl-form__require-mark"></span>
                    </div>
                    <div class="wl-form__inner">
                        <input class="wl-form-control" required type="text" name="wishlist_name" value="<?php echo $wishlist['name']; ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="wl-modal__footer">
            <div class="wl-modal__footer-row">
                <div class="wl-modal__footer-btn hidden-xs">
                    <button class="button alt" type="reset" data-modal-close=""><?php _e('Close',WishlistPlugin::DOMAIN); ?></button>
                </div>
                <div class="wl-modal__footer-btn">
                    <button class="button alt" type="submit" data-button-loader="button">
                        <span><?php _e('Save',WishlistPlugin::DOMAIN); ?></span>
                    </button>
                </div>
            </div>
        </div>

        <?php wp_nonce_field('wp_rest'); ?>
        <input type="hidden" name="wishlist_key" value="<?php echo $wishlist['wishlist_key']; ?>">
        <input type="hidden" name="rename" class="button alt" style="padding: 0px 5px 0px 5px;" value="<?php _e('Rename', WishlistPlugin::DOMAIN) ?>"/>
    </form>
</div>