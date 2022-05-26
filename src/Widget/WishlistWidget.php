<?php namespace Premmerce\Wishlist\Widget;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\Wishlist\WishlistPlugin;
use Premmerce\Wishlist\Models\WishlistModel;
use Premmerce\Wishlist\WishlistStorage;

class WishlistWidget extends \WP_Widget
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var WishlistModel
     */
    private $model;

    /**
     * @var WishlistStorage
     */
    private $storage;

    /**
     * WishlistWidget constructor.
     *
     * @param FileManager $fileManager
     * @param WishlistModel $model
     * @param WishlistStorage $storage
     */
    public function __construct(FileManager $fileManager, WishlistModel $model, WishlistStorage $storage)
    {
        $this->fileManager = $fileManager;
        $this->model = $model;
        $this->storage = $storage;

        parent::__construct(
            'premmerce_wishlist_widget',
            __('Premmerce Wishlist', WishlistPlugin::DOMAIN),
            ['description' => __('List of Wishlists', WishlistPlugin::DOMAIN) ]
        );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $this->fileManager->includeTemplate('widget/wishlist-widget.php', [
            'args'          => $args,
            'title'         => isset($instance['title']) ? $instance['title'] : '',
        ]);
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance
     *
     * @return void
     */
    public function form($instance)
    {
        $this->fileManager->includeTemplate('widget/wishlist-widget-form.php', [
            'title'     => isset($instance['title']) ? $instance['title'] : '',
            'widget'    => $this,
        ]);
    }

    /**
     * Processing widget options on save
     *
     * @param array $newInstance
     * @param array $oldInstance
     *
     * @return array
     */
    public function update($newInstance, $oldInstance)
    {
        $instance['title'] = (!empty( $newInstance['title'])) ? strip_tags($newInstance['title']) : '';
        
        return $instance;
    }
}
