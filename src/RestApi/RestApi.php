<?php namespace Premmerce\Wishlist\RestApi;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\Wishlist\Frontend\WishlistFunctions;
use Premmerce\Wishlist\WishlistPlugin;
use Premmerce\Wishlist\Models\WishlistModel;
use Premmerce\Wishlist\WishlistStorage;

/**
 * Class API
 * @package Premmerce\Wishlist\API
 */
class RestApi{
	const APINAMESPACE = 'premmerce/wishlist';

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
	 * @var string
	 */
	private $pageSlug;

	/**
	 * API constructor.
	 *
	 * @param FileManager $fileManager
	 * @param WishlistModel $model
	 * @param WishlistStorage $storage
	 */
	public function __construct(FileManager $fileManager, WishlistModel $model, WishlistStorage $storage){

		$this->fileManager = $fileManager;
		$this->model       = $model;
		$this->storage     = $storage;

		$this->pageSlug = 'project-boards';
		if($pageId = get_option(WishlistPlugin::OPTION_PAGE)){
			$post = get_post($pageId);

			if($post){
				$this->pageSlug = $post->post_name;
			}
		}

		add_action('rest_api_init', [$this, 'registerRestRoutes']);

		add_action('wc_ajax_premmerce_wishlist_popup', [$this, 'wishlistAddPopupAjax']);
	}

	/**
	 *  Register REST API routes
	 */
	public function registerRestRoutes(){
		register_rest_route(self::APINAMESPACE, '/add', [
			'methods'  => \WP_REST_Server::CREATABLE,
			'callback' => [$this, 'wishlistAdd'],
		]);

		register_rest_route(self::APINAMESPACE, '/add/popup', [
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => [$this, 'wishlistAddPopupRest'],
		]);

		register_rest_route(self::APINAMESPACE, '/page', [
			'methods'  => \WP_REST_Server::ALLMETHODS,
			'callback' => [$this, 'wishlistPage'],
		]);

		register_rest_route(self::APINAMESPACE, '/page/rename/(?P<wishlistKey>[a-zA-Z0-9]{13})', [
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => [$this, 'wishlistRenameForm'],
		]);

		register_rest_route(self::APINAMESPACE, '/delete/(?P<wishlistKey>[a-zA-Z0-9]{13})', [
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => [$this, 'wishlistDelete'],
		]);

		register_rest_route(self::APINAMESPACE, '/delete/(?P<wishlistKey>[a-zA-Z0-9]{13})/(?P<productId>\d+)', [
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => [$this, 'wishlistDeleteProduct'],
		]);
	}


	/**
	 * Prepare information for add to wishlist.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function wishlistAdd(\WP_REST_Request $request){
		$data      = $request->get_params();

		$productId = $this->productIdToWishlist($data);

        $wishlist_key = isset($data['wishlist_key']) ? $data['wishlist_key'] : false;

		if(isset($data['wishlist_id'])){

		    $wishlistId = (int)$data['wishlist_id'];

			if(in_array($wishlistId, [- 1, 0])){
				$result = $this->addProductToWishlistNew($data);
			}else{
				$result = $this->model->addProductToWishlistById($wishlistId, $productId);
			}
		}else{
			$result = $this->addProductToWishlist($productId);
		}

		$this->storage->stopAction('wishlist_add');

		if($this->isAjaxRequest()){

			$isMove = isset($_GET['wishlist_move_from'])? true : false;

			if($isMove){
				$routeUrl = home_url('wp-json/premmerce/wishlist/page');
				$title    = __('Move to another list', WishlistPlugin::DOMAIN);
			}else{
				$routeUrl = home_url('wp-json/premmerce/wishlist/add');
				$title    = __('Add to your board', WishlistPlugin::DOMAIN);
			}

			return rest_ensure_response([
				'success' => $result? $result : false,
				'html'    => $this->fileManager->renderTemplate('frontend/wishlist-popup.php', [
					'productId'    => $productId,
					'wishlists'    => $this->getWishlistsAll(),
					'success'      => $result? $result : false,
					'isMove'       => $isMove,
					'wishlist_key' => $wishlist_key,
					'routeUrl'     => $routeUrl,
					'title'        => $title,
					'pageSlug'     => $this->pageSlug,
				]),
			]);
		}else{
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
	}

	/**
	 * Get form for wishlist create and select
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function wishlistAddPopupAjax(){
		echo $this->wishlistAddPopup($_REQUEST);
	}

	/**
	 * Get form for wishlist create and select
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function wishlistAddPopupRest(\WP_REST_Request $request){
		return $this->wishlistAddPopup($request->get_params());
	}

	/**
	 * @param array $params
	 *
	 * @return string
	 */
	public function wishlistAddPopup($params){

		$productId = $this->productIdToWishlist($params);

		$wishlist_key = isset($_GET['wishlist_key']) ? $_GET['wishlist_key'] : false;

		$isMove = isset($_GET['wishlist_move_from'])? true : false;

		if($isMove){
			$routeUrl = home_url('wp-json/premmerce/wishlist/page');
			$title    = __('Move to another board', WishlistPlugin::DOMAIN);
		}else{
			$routeUrl = home_url('wp-json/premmerce/wishlist/add');
			$title    = __('Add to your board', WishlistPlugin::DOMAIN);
		}

		return $this->fileManager->renderTemplate('frontend/wishlist-popup.php', [
			'productId'    => $productId,
			'wishlists'    => $this->getWishlistsAll(),
			'success'      => false,
            'wishlist_key' => $wishlist_key,
			'isMove'       => $isMove,
			'routeUrl'     => $routeUrl,
			'title'        => $title,
			'pageSlug'     => $this->pageSlug,
		]);
	}

	/**
	 * Get all list of wislists
	 *
	 * @return array|mixed
	 */
	public function getWishlistsAll(){
		$wishlistAll = [];

		if(is_user_logged_in()){
			$wishlistAll = $this->model->getWishlistsByUserId(get_current_user_id());
		}else{
			if($this->storage->cookieIsSet()){
				$wishlistAll = $this->model->getWishlistsByKeys($this->storage->cookieGet());
			}
		}

		return $wishlistAll;
	}

	/**
	 * Control for front page form
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function wishlistPage(\WP_REST_Request $request){
		$data = $request->get_params();

		if(isset($data['rename'])){
			return $this->wishlistRename($data);
		}

		if(isset($data['move'])){
			return $this->wishlistMove($data);
		}
	}

	/**
	 * Move products between two wishlists
	 *
	 * @param $data
	 *
	 * @return mixed|\WP_REST_Response
	 */
	private function wishlistMove($data){

		$result = null;

		$defaults = [
			'wishlist_key'     => null,
			'wishlist_move_to' => null,
		];

		$defaults = array_replace($defaults, $data);

		$key    = $defaults['wishlist_key'];
		$moveTo = $defaults['wishlist_move_to'];

		if($key && $moveTo){
			$products = isset($data['product_ids'])? $data['product_ids'] : [];
			$result   = $this->model->moveProductsToWishlist($key, $moveTo, $products);
		}

		if($this->isAjaxRequest()){
			return rest_ensure_response([
				'success' => $result ? $result : false,
				'reload'  => true,
			]);
		}else{
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
	}

	/**
	 * Rename wishlist
	 *
	 * @param array $data
	 *
	 * @return mixed|\WP_REST_Response
	 */
	private function wishlistRename($data){
		$result = null;

		$name   = isset($data['wishlist_name'])? $data['wishlist_name'] : WishlistModel::$default_name;
		$result = $this->model->renameWishlistByKey($data['wishlist_key'], $name);

		if($this->isAjaxRequest()){

			return rest_ensure_response([
				'success' => $result? $result : false,
				'reload'  => true,
			]);

		}else{
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
	}

	/**
	 * Return form for rename wishlist
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function wishlistRenameForm(\WP_REST_Request $request){
		$data     = $request->get_params();
		$wishlist = $this->model->getWishlistByKey($data['wishlistKey']);

		return $this->fileManager->renderTemplate('frontend/wishlist-rename.php', [
			'wishlist'           => $wishlist,
			'apiUrlWishListPage' => site_url('wp-json/premmerce/wishlist/page'),
		]);

	}

	/**
	 * Delete product from wishlist
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function wishlistDelete(\WP_REST_Request $request){
		$result = null;
		$data   = $request->get_params();

		$wishlist = $this->model->getWishlistByKey($data['wishlistKey']);

		if($wishlist){
			$result = $this->model->deleteWishlists([$wishlist['ID']]);

			if(!is_user_logged_in() && $this->storage->cookieIsSet()){
				$this->storage->cookieDeleteWishlist($data['wishlistKey']);
			}
		}

		if($this->isAjaxRequest()){
			return rest_ensure_response(['success' => $result? $result : false]);
		}else{
			wp_redirect(remove_query_arg('key',$_SERVER['HTTP_REFERER']));
			exit;
		}
	}

	/**
	 * Delete product from wishlist
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function wishlistDeleteProduct(\WP_REST_Request $request){
		$data = $request->get_params();

		$result = $this->model->deleteProductFromWishlist($data['wishlistKey'], WishlistFunctions::getInstance()->getDefaultLanguageProductId($data['productId']));

		if($this->isAjaxRequest()){
			return rest_ensure_response(['success' => $result? $result : false]);
		}else{
			wp_redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
	}

	/**
	 * Add product to wishlist or create new wish list and add product
	 *
	 * @param int $productId
	 *
	 * @return bool
	 */
	private function addProductToWishlist($productId){
		$wishlist = [];
		$userId   = get_current_user_id();

		if(is_user_logged_in()){
			$wishlist = $this->model->getDefaultWishlistByUserId($userId);
		}else{
			if($this->storage->cookieIsSet()){
				$wishlist = $this->model->getDefaultWishlistByKeys($this->storage->cookieGet());
			}
		}

		$key = $wishlist ? $wishlist['wishlist_key'] : null;

		if($key){
			return $this->model->addProductToWishlistByKey($key, $productId);
		}else{
			$keyNew = $this->createWishlist($productId, $userId, WishlistModel::$default_name, 1);

			if($keyNew){
				if(!is_user_logged_in()){
					$this->storage->cookieAddWishlist($keyNew);
				}

				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * Create new wishlist and add product
	 *
	 * @param array $data
	 *
	 * @return bool
	 */
	private function addProductToWishlistNew($data){
		$userId = get_current_user_id();

		$name = WishlistModel::$default_name;

		if(isset($data['wishlist_name']) && !empty($data['wishlist_name'])){
			$name = $data['wishlist_name'];
		}

		$default = $data['wishlist_id'] == 0 ? 1 : 0;

		$key = $this->createWishlist($this->productIdToWishlist($data), $userId, $name, $default);

		if($key){
			if(!is_user_logged_in()){
				$this->storage->cookieAddWishlist($key);
			}

			return true;
		}

		return false;
	}

	/**
	 * Create new wishlist and return wishlist key
	 *
	 * @param int $productId
	 * @param int $userId
	 * @param string $wishlistName
	 * @param int $default
	 *
	 * @return string
	 */
	private function createWishlist($productId, $userId = 0, $wishlistName = null, $default = 0){
		$now = date('Y-m-d H:i:s');
		$key = uniqid();

		$data = [
			'user_id'       => $userId,
			'wishlist_key'  => $key,
			'products'      => $productId,
			'date_created'  => $now,
			'date_modified' => $now,
			'default'       => $default,
		];

		if($wishlistName){
			$data['name'] = $wishlistName;
		}

		if($this->model->create($data)){
			return $key;
		}
	}

	/**
	 * Checks the type of request
	 *
	 * @return bool
	 */
	private function isAjaxRequest(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true;
		}

		return false;
	}

	/**
	 * Get product or variable id
	 *
	 * @param $data
	 *
	 * @return int
	 */
	private function productIdToWishlist($data){
		$defaults = [
			'wishlist_product_id'   => '',
			'wishlist_variation_id' => '',
		];

		$defaults = array_replace($defaults, $data);

		$id = (int)$defaults['wishlist_variation_id']? $defaults['wishlist_variation_id'] : $defaults['wishlist_product_id'];

		return WishlistFunctions::getInstance()->getDefaultLanguageProductId($id);
	}

}
