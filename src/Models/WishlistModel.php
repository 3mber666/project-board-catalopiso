<?php namespace Premmerce\Wishlist\Models;

/**
 * Class WishlistModel
 * @package Premmerce\Wishlist\Models
 */
class WishlistModel
{

    /**
     * Db table name without prefix
     */
    const TBL_NAME_WISHLIST = 'premmerce_wishlist';

    /**
     *  Default wishlist name
     */
    public static $default_name;

    /**
     * Table name with prefix
     *
     * @var string
     */
    private $tblWishlist = '';

    /**
     * Charset for DB table
     *
     * @var string
     */
    private $charset = '';

    /**
     * Collate for DB table
     *
     * @var string
     */
    private $collate = '';


    /**
     * @var \wpdb
     */
    private $wpdb;

    /**
     * AdminModel constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        self::$default_name = __('My Project Board', 'premmerce-wishlist');

        $this->charset = $wpdb->charset;
        $this->collate = $wpdb->collate;

        $this->tblWishlist = $wpdb->prefix . self::TBL_NAME_WISHLIST;
    }

    /**
     * Create plugin tables
     */
    public function createTables()
    {
        $sql = vsprintf(
            'CREATE TABLE IF NOT EXISTS %s (
              `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) unsigned DEFAULT NULL,
              `name` varchar(255) NOT NULL DEFAULT "",
              `wishlist_key` varchar(13) DEFAULT "",
              `products` text,
              `date_created` datetime,
              `date_modified` datetime,
              `default` tinyint(1) DEFAULT 0,
              PRIMARY KEY  (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARACTER SET %s COLLATE %s;',
            [
                $this->tblWishlist,
                $this->charset,
                $this->collate
            ]
        );

        $this->runQuery($sql);
    }

    /**
     * Delete plugin tables.
     */
    public function deleteTables()
    {
        $sql = 'DROP TABLE IF EXISTS ' . $this->tblWishlist;
        $this->runQuery($sql);
    }

    /**
     * Execute query
     *
     * @param string $sql
     *
     * @return bool
     */
    private function runQuery($sql)
    {
        if ($this->wpdb->query($sql) !== false) {
            return true;
        }
    }

    /**
     * Prepare query to execute
     *
     * @param string $sql
     * @param array $args
     *
     * @return string
     */
    private function prepare($sql, $args)
    {
        return $this->wpdb->prepare($sql, $args);
    }

    /**
     * Create new wishlist
     *
     * @param array $data
     *
     * @return bool
     */
    public function create($data)
    {
        if (!empty($data['wishlist_key'])) {
            if (!isset($data['name'])) {
                $data['name'] = self::$default_name;
            }

            $sql = $this->prepare(
                "INSERT INTO `" . $this->tblWishlist . "` (`user_id`,`name`,`wishlist_key`,`products`,`date_created`,`date_modified`,`default`) 
                VALUES (%d,%s,%s,%s,%s,%s,%d);",
                [
                    $data['user_id'],
                    $data['name'],
                    $data['wishlist_key'],
                    implode(',',[$data['products']]),
                    $data['date_created'],
                    $data['date_modified'],
                    $data['default'],
                ]
            );

            return $this->runQuery($sql);
        }
    }

    /**
     * Add product to wishlist by key
     *
     * @param string $key
     * @param int $productId
     *
     * @return bool
     */
    public function addProductToWishlistByKey($key, $productId)
    {
        $wishlist = $this->getWishlistByKey($key);

        if ($wishlist) {
            return $this->addProductToWishlist($wishlist, $productId);
        }

        return false;
    }

    /**
     * Add product to wishlist by id
     *
     * @param string $id
     * @param int $productId
     *
     * @return bool
     */
    public function addProductToWishlistById($id, $productId)
    {
        $wishlist = $this->getWishlistById($id);

        if ($wishlist) {
            return $this->addProductToWishlist($wishlist, $productId);
        }

        return false;
    }

    /**
     * @param array $wishlist
     * @param int $productId
     *
     * @return bool
     */
    private function addProductToWishlist($wishlist, $productId)
    {
        $products = $wishlist['products'] ? $wishlist['products'] : [];

        if (!in_array($productId, $products)) {
            $products[] = $productId;

            $sql = $this->prepare("UPDATE `" . $this->tblWishlist . "` SET `products` = %s, `date_modified` = %s WHERE `ID` = %d;",
                [
                    implode(',',$products),
                    date('Y-m-d H:i:s'),
                    $wishlist['ID'],
                ]
            );

            return $this->runQuery($sql);
        } else {
            return true;
        }
    }

    /**
     * Get wishlist by wishlist key
     *
     * @param string $key
     *
     * @return array|mixed
     */
    public function getWishlistByKey($key)
    {
        if (!empty($key)) {
            $sql = $this->prepare("SELECT * FROM `" . $this->tblWishlist . "` WHERE `wishlist_key` = %s;", $key);

            return $this->getWishlistBySql($sql);
        }

        return [];
    }

    /**
     * Get wishlists by list of keys
     *
     * @param array $keys
     *
     * @return array
     */
    public function getWishlistsByKeys($keys)
    {
        if ($keys && is_array($keys)) {
            $sql = vsprintf(
                "SELECT * FROM `%s` WHERE `wishlist_key` IN (%s);",
                [
                    $this->tblWishlist,
                    '"' . implode('", "', $keys) . '"'
                ]
            );

            return $this->getWishlistsBySql($sql);
        }

        return [];
    }

    /**
     * Get default wishlist by list of keys
     *
     * @param array $keys
     *
     * @return array
     */
    public function getDefaultWishlistByKeys($keys)
    {
        if ($keys && is_array($keys)) {
            $sql = vsprintf(
                "SELECT * FROM `%s` WHERE `default` = 1 AND `wishlist_key` IN (%s);",
                [
                    $this->tblWishlist,
                    '"' . implode('", "', $keys) . '"'
                ]
            );

            return $this->getWishlistBySql($sql);
        }

        return [];
    }

    /**
     * Combine default wishlists to one
     *
     * @param int $userId
     *
     * @return bool
     */
    public function combineDefaultsWishlistsByUserID($userId) {

        $sql = $this->prepare("SELECT * FROM `" . $this->tblWishlist . "` WHERE `user_id` = %d AND `default` = 1 ORDER BY `ID`;", [$userId]);
        $wishlistsDefault = $this->getWishlistsBySql($sql);

        if (count($wishlistsDefault) > 1) {
            $ids = array_column($wishlistsDefault, 'ID');

            $products = [];
            foreach ($wishlistsDefault as $wl) {
                $products = array_merge($products, $wl['products']);
            }

            $this->deleteWishlists(array_slice($ids,1));

            $sql = $this->prepare("UPDATE `" . $this->tblWishlist . "` SET `products` = %s, `date_modified` = %s WHERE `ID` = %d;",
                [
                    implode(',',array_unique($products)),
                    date('Y-m-d H:i:s'),
                    $ids[0],
                ]
            );

            return $this->runQuery($sql);
        }
    }

    /**
     * Get wishlists by user id
     *
     * @param int $userId
     *
     * @return array|mixed
     */
    public function getWishlistsByUserId($userId)
    {
        if (!empty($userId)) {
            $sql = $this->prepare("SELECT * FROM `" . $this->tblWishlist . "` WHERE `user_id` = %d;", [$userId]);

            return $this->getWishlistsBySql($sql);
        }

        return [];
    }

    /**
     * Get default wishlist by user id
     *
     * @param int $userId
     *
     * @return array|mixed
     */
    public function getDefaultWishlistByUserId($userId)
    {
        if (!empty($userId)) {
            $sql = $this->prepare("SELECT * FROM `" . $this->tblWishlist . "` WHERE `user_id` = %d AND `default` = 1;", [$userId]);

            return $this->getWishlistBySql($sql);
        }

        return [];
    }

    /**
     * Get wishlist by wishlist id
     *
     * @param int $id
     *
     * @return array|mixed
     */
    public function getWishlistById($id)
    {
        if (!empty($id)) {
            $sql = $this->prepare("SELECT * FROM `" . $this->tblWishlist . "` WHERE `ID` = %d;", $id);

            return $this->getWishlistBySql($sql);
        }

        return [];
    }

    /**
     * Run query to get wishlist and return it
     *
     * @param string $sql
     *
     * @return array|mixed
     */
    private function getWishlistBySql($sql)
    {
        $wishlist = $this->getResults($sql);

        if ($wishlist) {
            $wishlist[0]['products'] = explode(',',$wishlist[0]['products']);

            return $wishlist[0];
        }

        return [];
    }

    /**
     * Run query to get wishlist and return it
     *
     * @param string $sql
     *
     * @return array|mixed
     */
    private function getWishlistsBySql($sql)
    {
        $wishlist = $this->getResults($sql);

        if ($wishlist) {
            foreach ($wishlist as &$w) {
                $w['products'] = explode(',',$w['products']);
            }

            return $wishlist;
        }

        return [];
    }

    /**
     * Get all wishlists by params
     *
     * @param array $params
     *
     * @return array
     */
    public function getWishlists($params = [])
    {
        $orderbyColumns = ['ID','name','date_created','date_modified'];

        $orderby = '';
        if (isset($params['orderby']) && in_array($params['orderby'], $orderbyColumns)) {
            $order = 'ASC';
            switch (strtolower($params['order'])) {
                case 'asc':
                    $order = 'ASC';
                    break;
                case 'desc':
                    $order = 'DESC';
                    break;
            }

            $orderby = " ORDER BY `" . $params['orderby'] . "` " . $order;
        }

        $where = "";
        if (isset($params['where_in'])) {
            $where = " WHERE";
            foreach ($params['where_in'] as $column => $value) {
                $where .= " `" . $column . "` IN (" . $value . ")";
            }
        }

        $sql = "SELECT * FROM `" . $this->tblWishlist . "`" . $where . $orderby;

        $wishlist = $this->getResults(trim($sql));

        foreach ($wishlist as &$wl) {
            $wl['products'] = explode(',',$wl['products']);
        }

        return $wishlist;
    }

    public function getWishlistsByProductId($id) {
        $sql = "SELECT * FROM `" . $this->tblWishlist . "` WHERE `products` LIKE '%" . $id . "%';";

        $wishlist = $this->getResults(trim($sql));

        foreach ($wishlist as &$wl) {
            $wl['products'] = explode(',',$wl['products']);
        }

        return $wishlist;
    }

    /**
     * Get data from tables
     *
     * @param string $sql
     *
     * @return array
     */
    private function getResults($sql)
    {
        return $this->wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * Get wishlist products by products ids
     *
     * @param  array $products
     *
     * @return array
     */
    public function getProductsByIds($products)
    {
        $data = [];

        foreach ($products as $id) {
            if ($product = wc_get_product($id)) {
                $data[] = $product;
            }
        }

        return $data;
    }

    /**
     * Move product between two wishlist
     *
     * @param string $from
     * @param string $to
     * @param array $products
     *
     * @return bool
     */
    public function moveProductsToWishlist($from, $to, $products)
    {
        $result = false;

        if ($wlFrom = $this->getWishlistByKey($from)) {
            $wlProducts = $wlFrom['products'] ? $wlFrom['products'] : [];

            foreach ($products as $product) {
                if (in_array($product, $wlProducts)) {
                    unset($wlProducts[array_search($product, $wlProducts)]);
                }
            }

            $sql = $this->prepare(
                "UPDATE `" . $this->tblWishlist . "` SET `products` = %s, `date_modified` = %s WHERE `wishlist_key` = %s;",
                [
                    implode(',',$wlProducts),
                    date('Y-m-d H:i:s'),
                    $from,
                ]
            );

            $this->runQuery($sql);
        }

        if ($wlTo = $this->getWishlistByKey($to)) {
            $wlProducts = $wlTo['products'] ? $wlTo['products'] : [];

            foreach ($products as $product) {
                if (!in_array($product, $wlProducts)) {
                    $wlProducts[] = $product;
                }
            }

            $sql = $this->prepare(
                "UPDATE `" . $this->tblWishlist . "` SET `products` = %s, `date_modified` = %s WHERE `wishlist_key` = %s;",
                [
                    implode(',',$wlProducts),
                    date('Y-m-d H:i:s'),
                    $to,
                ]
            );

            $result = $this->runQuery($sql);
        }

        return $result;
    }

    /**
     * Set user id to wishlists
     *
     * @param int $userId
     * @param array $keys
     *
     * @return array|bool
     */
    public function setUserToWishlistsByKeys($userId, $keys)
    {
        if ($keys && is_array($keys)) {

            $sql = $this->prepare(
                "UPDATE `" . $this->tblWishlist . "` SET `user_id` = %d, `date_modified` = %s WHERE `wishlist_key` IN (". "'" . implode("','", $keys) . "'" .");",
                [
                    (int) $userId,
                    date('Y-m-d H:i:s'),
                ]
            );

            return $this->runQuery($sql);
        }

        return [];
    }

    /**
     * Rename wishlist
     *
     * @param string $key
     * @param string $name
     *
     * @return bool
     */
    public function renameWishlistByKey($key, $name)
    {
        $wishlist = $this->getWishlistByKey($key);

        if ($wishlist) {
            $name = !empty($name) ? $name : self::$default_name;

            $sql = $this->prepare(
                "UPDATE `" . $this->tblWishlist . "` SET `name` = %s, `date_modified` = %s WHERE `wishlist_key` = %s;",
                [
                    $name,
                    date('Y-m-d H:i:s'),
                    $key,
                ]
            );

            return $this->runQuery($sql);
        }
    }

    /**
     * Delete product from wishlsit
     *
     * @param string $key
     * @param int $productId
     *
     * @return bool
     */
    public function deleteProductFromWishlist($key, $productId)
    {
        $wishlist = $this->getWishlistByKey($key);

        if ($wishlist) {
            $products = $wishlist['products'] ? $wishlist['products'] : [];

            if (in_array($productId, $products)) {
                unset($products[array_search($productId, $products)]);

                $sql = $this->prepare(
                    "UPDATE `" . $this->tblWishlist . "` SET `products` = %s, `date_modified` = %s WHERE `wishlist_key` = %s;",
                    [
                        implode(',',$products),
                        date('Y-m-d H:i:s'),
                        $key,
                    ]
                );

	            return $this->runQuery($sql);
            }
        }
    }

    /**
     * Delete wishlist by ids
     *
     * @param array $ids
     *
     * @return bool
     */
    public function deleteWishlists($ids = [])
    {
        $sql = "DELETE FROM `" . $this->tblWishlist . "` WHERE `id` IN (" . implode(', ', $ids) . ");";

        return $this->runQuery($sql);
    }

    /**
     * Delete wishlist by modified date interval
     *
     * @param string $type
     *
     * @return bool
     */
    public function deleteWishlistsByDateInterval($type)
    {
        switch ($type) {
            case 'day':     $interval = ['type' => 'DAY' ,   'value' => 1]; break;
            case 'day3':    $interval = ['type' => 'DAY' ,   'value' => 3]; break;
            case 'week':    $interval = ['type' => 'DAY' ,   'value' => 7]; break;
            case 'month':   $interval = ['type' => 'MONTH' , 'value' => 1]; break;
            case 'month3':  $interval = ['type' => 'MONTH' , 'value' => 3]; break;
            case 'month6':  $interval = ['type' => 'MONTH' , 'value' => 6]; break;
            case 'year':    $interval = ['type' => 'YEAR' ,  'value' => 1]; break;
            default: return false;
        }

        $sql = vsprintf(
            'DELETE FROM `%s` WHERE `user_id` = 0 AND `date_modified` < (NOW() - INTERVAL %d %s);',
            [
                $this->tblWishlist,
                $interval['value'],
                $interval['type'],
            ]
        );

        return $this->runQuery($sql);
    }
}
