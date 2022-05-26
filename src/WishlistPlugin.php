<?php namespace Premmerce\Wishlist;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\Wishlist\Admin\Admin;
use Premmerce\Wishlist\Frontend\Frontend;
use Premmerce\Wishlist\RestApi\RestApi;
use Premmerce\Wishlist\Models\WishlistModel;
use Premmerce\Wishlist\Widget\WishlistWidget;


/**
 * Class WishlistPlugin
 *
 * @package Premmerce\Wishlist
 */
class WishlistPlugin
{

    const DOMAIN = 'premmerce-wishlist';
    const OPTION_PAGE = 'premmerce-wishlist-page-id';

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
     * @var AdminNotifier
     */
    private $notifier;

    /**
     * PluginManager constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->model       = new WishlistModel();
        $this->storage     = new WishlistStorage($this->model);
        $this->fileManager = new FileManager($file, 'premmerce-woocommerce-wishlist');
        $this->notifier    = new AdminNotifier();

        add_action('widgets_init', [$this, 'registerWidgets']);

        add_action('init', [$this, 'loadTextDomain']);
        add_action('admin_init', [$this, 'checkRequirePlugins']);
    }

    /**
     * Register wishlist widget
     */
    public function registerWidgets()
    {
        $widget = new WishlistWidget($this->fileManager, $this->model, $this->storage);

        register_widget($widget);
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        $valid = count($this->validateRequiredPlugins()) === 0;

        if ($valid) {
            new RestApi($this->fileManager, $this->model, $this->storage);

            if (is_admin()) {
                new Admin($this->fileManager, $this->model);
            }

            if ( ! is_admin() || wp_doing_ajax()) {
                $GLOBALS['premmerce_wishlist_frontend'] = new Frontend($this->fileManager, $this->model,
                    $this->storage);
            }
        }
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
        $this->model->createTables();

        $new = false;
        if ($pageId = get_option(self::OPTION_PAGE)) {
            if ( ! get_post($pageId)) {
                $new = true;
            }
        } else {
            $new = true;
        }

        if ($new) {
            $name = get_page_by_title('Wishlists') ? 'Premmerce Wishlists' : 'Wishlists';

            $post_data = [
                'post_title'   => $name,
                'post_content' => '[wishlist_page]',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => 'page',
            ];

            $newId = wp_insert_post($post_data);
            update_option(self::OPTION_PAGE, $newId);
        }
    }

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
        if ($pageId = get_option(self::OPTION_PAGE)) {
            wp_delete_post($pageId, true);
            delete_option($pageId);
        }
    }

    /**
     * Fired during plugin uninstall
     */
    public static function uninstall()
    {
        $model = new WishlistModel();
        $model->deleteTables();

        if ($pageId = get_option(self::OPTION_PAGE)) {
            wp_delete_post($pageId, true);
            delete_option($pageId);
        }
    }

    /**
     * Check required plugins and push notifications
     */
    public function checkRequirePlugins()
    {
        $message = __('The %s plugin requires %s plugin to be active!', self::DOMAIN);

        $plugins = $this->validateRequiredPlugins();

        if (count($plugins)) {
            foreach ($plugins as $plugin) {
                $error = sprintf($message, 'Premmerce WooCommerce Wishlist', $plugin);
                $this->notifier->push($error, AdminNotifier::ERROR, false);
            }
        }

    }

    /**
     * Validate required plugins
     *
     * @return array
     */
    private function validateRequiredPlugins()
    {

        $plugins = [];

        if ( ! function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        /**
         * Check if WooCommerce is active
         **/
        if ( ! (is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php'))) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
        }

        return $plugins;
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain(self::DOMAIN, false, $name . '/languages/');
    }
}
