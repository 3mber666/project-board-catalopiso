<?php namespace Premmerce\Wishlist;

use Premmerce\Wishlist\Models\WishlistModel;

class WishlistStorage
{

    const COOKIE_NAME = 'premmerce_wishlist';

    const DAYS_IN_SECONDS = 60 * 60 * 24;

    /**
     * @var WishlistModel
     */
    private $model;

    /**
     * WishlistStorage constructor.
     *
     * @param WishlistModel $model
     */
    public function __construct(WishlistModel $model)
    {
        $this->model = $model;
    }

    /**
     * Check is set cookie with wishlist keys
     *
     * @return bool
     */
    public function cookieIsSet()
    {
        return isset($_COOKIE[self::COOKIE_NAME]);
    }

    /**
     * Set list of wishlist keys to cookie
     *
     * @param array $keys
     *
     * @return array
     */
    public function cookieSet($keys)
    {
        $keys = array_unique(array_column($this->model->getWishlistsByKeys($keys), 'wishlist_key'));
        $keys = json_encode($keys);

        setcookie(self::COOKIE_NAME, $keys, time() + self::DAYS_IN_SECONDS * 31 * 6, COOKIEPATH, COOKIE_DOMAIN);
    }

    /**
     * Get list of wishlist keys from cookie
     *
     * @return array
     */
    public function cookieGet()
    {

        if ($this->cookieIsSet()) {
            $data = json_decode(stripslashes($_COOKIE[self::COOKIE_NAME]));
            return $data ? $data : [];
        }

        return [];
    }

    /**
     * Delete cookie
     */
    public function cookieDelete()
    {
        setcookie(self::COOKIE_NAME, '', time() - 1, COOKIEPATH, COOKIE_DOMAIN);
    }

    /**
     * Add wishlist key to cookies
     *
     * @param string $key
     */
    public function cookieAddWishlist($key)
    {
        $keys = $this->cookieGet();

        if (!in_array($key, $keys)) {
            $keys[] = $key;
        }

        $this->cookieSet($keys);
    }

    /**
     * Delete wishlist key from cookies
     *
     * @param string $key
     */
    public function cookieDeleteWishlist($key)
    {
        $keys = $this->cookieGet();

        $index = array_search($key, $keys);

        if ($index) {
            unset($keys[$index]);
            $this->cookieSet($keys);
        }

        $this->cookieSet(array_unique(array_column($this->model->getWishlistsByKeys($keys), 'wishlist_key')));
    }

    public function startAction($action) {
        setcookie('premmerce_' . $action, 'true', time() + self::DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
    }

    public function stopAction($action) {
        setcookie('premmerce_' . $action, '', time() - 1, COOKIEPATH, COOKIE_DOMAIN);
    }

    public function isAction($action) {
        return isset($_COOKIE['premmerce_' . $action]);
    }
}