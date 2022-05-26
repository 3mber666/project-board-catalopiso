<?php namespace Premmerce\Wishlist\Admin;

use Premmerce\Wishlist\WishlistPlugin;
use Premmerce\Wishlist\Models\WishlistModel;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class WishlistViewTable extends \WP_List_Table
{

    /**
     * @var WishlistModel
     */
    private $model;

    /**
     * @var int
     */
    private $wishlistId;

    /**
     * PriceTypesTable constructor.
     *
     * @param int $wishListId
     * @param WishlistModel $model
     */
    public function __construct($wishListId, WishlistModel $model)
    {
        parent::__construct([
            'singular' => __('products', WishlistPlugin::DOMAIN),
            'plural'   => __('product', WishlistPlugin::DOMAIN),
            'ajax'     => false,
        ]);

        $this->model = $model;
        $this->wishlistId = $wishListId;

        $this->_column_headers = [
            $this->get_columns(),
        ];

        $this->prepare_items();
    }

    /**
     * Render data for cell Name
     *
     * @param array $item
     *
     * @return mixed
     */
    protected function column_name($item)
    {
        return vsprintf(
            '<a href="%s">%s</a>',
            [
                get_edit_post_link($item->get_parent_ID() ? $item->get_parent_ID() : $item->get_ID()),
                $item->get_name()
            ]
        );
    }

    /**
     * Return array with columns titles
     *
     * @return array
     */
    public function get_columns()
    {
        return [
            'name' => __('Name', WishlistPlugin::DOMAIN),
        ];
    }

    /**
     * Set items data in table
     */
    public function prepare_items()
    {
        $this->items = [];

        $wishlist = $this->model->getWishlistById($this->wishlistId);

        if ($wishlist) {
            $this->items = $this->model->getProductsByIds($wishlist['products']);
        }
    }
}
