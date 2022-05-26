<?php namespace Premmerce\Wishlist\Frontend;

use Premmerce\Wishlist\Models\WishlistModel;
use Premmerce\Wishlist\WishlistPlugin;
use Premmerce\Wishlist\WishlistStorage;


class WishlistFunctions {


    /**
     * @var WishlistModel
     */
    private $model;

    /**
     * @var WishlistStorage
     */
    private $storage;


    private static $_instance = null;

    private function __construct(){
        $this->model = new WishlistModel();
        $this->storage = new WishlistStorage($this->model);
    }

    /**
     * Static class initialization
     *
     * @return WishlistFunctions
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function checkInWishlist($productId) {
        $wishlists = [];

        if (is_user_logged_in()) {
            $wishlists = $this->model->getWishlistsByUserId(get_current_user_id());
        } elseif ($this->storage->cookieIsSet()) {
            $wishlists = $this->model->getWishlistsByKeys($this->storage->cookieGet());
        }

	    $productId = $this->getDefaultLanguageProductId($productId);

	    foreach ($wishlists as $wl) {
            if (in_array($productId,$wl['products'])) {
                return true;
            }
        }

        return false;
    }

    public function wishlistTotal() {
        $wishlist = [];
        $countProducts = 0;

        if (is_user_logged_in()) {
            $wishlist = $this->model->getWishlistsByUserId(get_current_user_id());
        } elseif ($this->storage->cookieIsSet()) {
            $wishlist = $this->model->getWishlistsByKeys($this->storage->cookieGet());
        }

        if ($wishlist) {
            foreach ($wishlist as $wl) {
                $countProducts += count(array_filter($wl['products']));
            }
        }

        return $countProducts;
    }

    public function isProductInWishlist($id, $key) {

	    $id = $this->getDefaultLanguageProductId($id);

	    $wishlist = $this->model->getWishlistsByProductId($id);

        if ($wishlist[0]) {
            return $wishlist[0]['wishlist_key'] == $key;
        }

        return false;
    }

    public function getWishlistKeyByProductId($id) {

	    $id = $this->getDefaultLanguageProductId($id);

	    $wishlist = $this->model->getWishlistsByProductId($id);

        if ($wishlist[0]) {
            return $wishlist[0]['wishlist_key'];
        }

        return '';
    }

    public function getWishlists() {
        return $this->model->getWishlists();
    }

	/**
	 * Wishlist page permalink
	 *
	 * @return false|string
	 */
	public function getWishlistUrl()
	{
		$id = get_option(WishlistPlugin::OPTION_PAGE);
		if (function_exists('wpml_get_object_id')) {
			$id = wpml_get_object_id(17, 'post', true);
		}
		return get_permalink($id);
	}

	/**
	 * WPML Compatibility
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getDefaultLanguageProductId($id){
		$language_code = apply_filters('wpml_default_language', null);
		$id            = apply_filters('wpml_object_id', $id, 'product', true, $language_code);

		return $id;
	}

    /**
     * Return add popop route
     *
     * @param null $productId
     * @return string
     */
    public function getAddPopupUrl($productId){

        $url = add_query_arg([
            'wc-ajax' => 'premmerce_wishlist_popup',
            'wishlist_product_id' => $productId,
        ],home_url());

        return $url;
    }
}