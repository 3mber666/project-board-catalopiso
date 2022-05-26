<?php namespace Premmerce\Wishlist\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\Wishlist\WishlistPlugin;
use Premmerce\Wishlist\Models\WishlistModel;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class WishlistTable extends \WP_List_Table
{

    /**
     * @var WishlistModel
     */
    private $model;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * PriceTypesTable constructor.
     *
     * @param FileManager $fileManager
     * @param WishlistModel $model
     */
    public function __construct(FileManager $fileManager, WishlistModel $model)
    {
        parent::__construct([
            'singular' => __('lists', WishlistPlugin::DOMAIN),
            'plural'   => __('list', WishlistPlugin::DOMAIN),
            'ajax'     => false,
        ]);

        $this->fileManager = $fileManager;
        $this->model = $model;

        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns(),
        ];

        $this->prepare_items();
    }

    /**
     * Fill data table cells
     *
     * @param array $item
     * @param string $columnName
     *
     * @return mixed
     */
    protected function column_default($item, $columnName)
    {
        if (array_key_exists($columnName, $item)) {
            return $item[$columnName];
        }

        return '';
    }

    /**
     * Render data for cell checkbox
     *
     * @param array $item
     *
     * @return string
     */
    protected function column_cb($item)
    {
        return '<input type="checkbox" name="ids[]" id="cb-select-'. $item['ID'] .'" value="'. $item['ID'] .'">';
    }

    /**
     * Render data for cell name
     *
     * @param array $item
     *
     * @return string
     */
    protected function column_name($item)
    {
        return vsprintf(
            '<a href="%s?page=premmerce-wishlist&wl-action=view&wl-id=%s">%s</a>',
            [
                admin_url('admin.php'),
                $item['ID'],
                $item['name']
            ]
        );
    }

    /**
     * Render data for cell products
     *
     * @param array $item
     *
     * @return string
     */
    protected function column_products($item)
    {
        return count(array_filter($item['products']));
    }

    /**
     * Render data for cell user
     *
     * @param array $item
     *
     * @return string
     */
    protected function column_user($item)
    {
        if ($user = get_user_by('ID', $item['user_id'])) {
            return '<a href="' . get_edit_user_link($user->ID) . '">' . $user->user_login . '</a>';
        } else {
            return __('No user', WishlistPlugin::DOMAIN);
        }
    }

    /**
     * Render data for date created
     *
     * @param array $item
     *
     * @return string
     */
    protected function column_date_created($item)
    {
        return date_i18n(get_option('date_format'), strtotime($item['date_created']));
    }

    /**
     * Render data for date modified
     *
     * @param array $item
     *
     * @return string
     */
    protected function column_date_modified($item)
    {
        return date_i18n(get_option('date_format'), strtotime($item['date_modified']));
    }

    /**
     * Render table filters/actions
     *
     * @param string $position
     */
    protected function extra_tablenav($position)
    {
        if ($position == 'top') {

            $modifiedDate = [
                "day"    => __('Day', WishlistPlugin::DOMAIN),
                "day3"   => __('Three days', WishlistPlugin::DOMAIN),
                "week"   => __('Week', WishlistPlugin::DOMAIN),
                "month"  => __('Month', WishlistPlugin::DOMAIN),
                "month3" => __('Three months', WishlistPlugin::DOMAIN),
                "month6" => __('Six months', WishlistPlugin::DOMAIN),
                "year"   => __('Year', WishlistPlugin::DOMAIN),
            ];

            $this->fileManager->includeTemplate('admin/wishlist-extra-tablenav.php', [
                'modifiedDate' => $modifiedDate
            ]);
        }
    }

    /**
     * Return array with columns titles
     *
     * @return array
     */
    public function get_columns()
    {
        return [
            'cb' => '<input type="checkbox">',
            'name' => __('Name', WishlistPlugin::DOMAIN),
            'user' => __('User', WishlistPlugin::DOMAIN),
            'products' => __('Products count', WishlistPlugin::DOMAIN),
            'date_created' => __('Created', WishlistPlugin::DOMAIN),
            'date_modified' => __('Modified', WishlistPlugin::DOMAIN)
        ];
    }

    /**
     * Return array with sortable columns
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        return [
            'name' => ['name','asc'],
            'date_created' => ['date_created','asc'],
            'date_modified' => ['date_modified','asc'],
        ];
    }

    /**
     * Set actions list for bulk
     *
     * @return array
     */
    protected function get_bulk_actions()
    {
        return ['delete' => __('Delete', WishlistPlugin::DOMAIN)];
    }

    /**
     * Generate row actions
     *
     * @param object $item
     * @param string $column_name
     * @param string $primary
     *
     * @return string
     */
    protected function handle_row_actions($item, $column_name, $primary )
    {
        if ( $primary !== $column_name ) {
            return '';
        }

        $actions['edit'] = vsprintf('<a href="%s?page=premmerce-wishlist&wl-action=view&wl-id=%s">%s</a>',
            [
                admin_url('admin.php'),
                $item['ID'],
                __('View', WishlistPlugin::DOMAIN),
            ]
        );

        $actions['delete'] = vsprintf('<a class="submitdelete" href="%s?action=%s&wishlist=%s" data-action--delete>%s</a>',[
            admin_url('admin-post.php'),
            'premmerce_delete_wishlist',
            $item['ID'],
            __('Delete', WishlistPlugin::DOMAIN),
        ]);

        $pageSlug = 'project-boards';
        
        if ($pageId = get_option(WishlistPlugin::OPTION_PAGE)) {
            $post = get_post($pageId);

            if ($post) {
                $pageSlug = $post->post_name;
            }
        }

        $actions['view'] = vsprintf('<a href="%s?key=%s">%s</a>',
            [
                site_url($pageSlug),
                $item['wishlist_key'],
                __('View on site', WishlistPlugin::DOMAIN),
            ]
        );

        return $this->row_actions( $actions );
    }

    /**
     * Set items data in table
     */
    public function prepare_items()
    {
        $params = [];

        if (isset($_GET['orderby'])) {
            $params['orderby'] = $_GET['orderby'];
            $params['order']   = $_GET['order'];
        }

        if (isset($_POST['s']) && !empty($_POST['s'])) {
            $value = wp_unslash(trim($_POST['s']));

            $users = new \WP_User_Query([
                'search' => '*' . $value . '*',
            ]);

            $ids = [];
            foreach ($users->get_results() as $user) {
                $ids[] = $user->ID;
            }

            $usersMeta = new \WP_User_Query([
                'meta_query' => [
                    'relation' => 'OR',
                    [
                        'key' => 'first_name',
                        'value' => $value,
                        'compare' => 'LIKE'
                    ],
                    [
                        'key' => 'last_name',
                        'value' => $value,
                        'compare' => 'LIKE'
                    ]
                ]
            ]);

            foreach ($usersMeta->get_results() as $user) {
                if (!in_array($user->ID, $ids)) {
                    $ids[] = $user->ID;
                }
            }

            if (strtolower($value) == 'no user') {
                $ids[] = 0;
            }

            if ($ids) {
                $params['where_in']['user_id'] = implode(',', $ids);
            }
        }

        if (isset($value) && !isset($params['where_in'])) {
            $data = [];
        } else {
            $data = $this->model->getWishlists($params);
        }



        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args([
            'total_items' => $totalItems,
            'per_page'    => $perPage,
        ]);

        $this->items = array_slice($data,(($currentPage-1)*$perPage),$perPage);
    }

    /**
     * Render if no items
     */
    public function no_items()
    {
        _e('No wishlists found.', WishlistPlugin::DOMAIN);
    }
}
