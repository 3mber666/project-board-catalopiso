<?php
    use Premmerce\Wishlist\WishlistPlugin;
?>

<a href="<?= $deleteUrl; ?>" class="wl-button__remove" title="<?php _e('Delete',WishlistPlugin::DOMAIN); ?>">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
        <path d="M10-.01a10 10 0 1 0 10 10 10 10 0 0 0-10-10zm0 19a9 9 0 1 1 9-9 9 9 0 0 1-9 9z"/>
        <path d="M13.72 6.29a1 1 0 0 0-1.4 0l-6 6a1 1 0 0 0 1.4 1.4l6-6a1 1 0 0 0 0-1.4z"/>
        <path d="M6.31 6.29a1 1 0 0 1 1.4 0l6 6a1 1 0 1 1-1.4 1.4l-6-6a1 1 0 0 1 0-1.4z"/>
    </svg>
</a>