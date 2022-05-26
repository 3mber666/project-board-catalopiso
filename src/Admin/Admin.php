<?php namespace Premmerce\Wishlist\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\Wishlist\WishlistPlugin;
use Premmerce\Wishlist\Models\WishlistModel;

/**
 * Class Admin
 *
 * @package Premmerce\Wishlist\Admin
 */
class Admin{

	/**
	 * @var FileManager
	 */
	private $fileManager;

	/**
	 * @var WishlistModel
	 */
	private $model;

	/**
	 * Admin constructor.
	 *
	 * Register menu items and handlers
	 *
	 * @param FileManager $fileManager
	 * @param WishlistModel $model
	 */
	public function __construct(FileManager $fileManager, WishlistModel $model){
		$this->fileManager = $fileManager;
		$this->model       = $model;

		add_action('init', function(){
			if(!session_id()){
				session_start();
			}
		});

		add_action('admin_menu', [$this, 'addMenuPage']);
		add_action('admin_menu', [$this, 'addFullPack'], 100);

		add_action('show_user_profile', [$this, 'renderUserWishlists'], 21, 1);
		add_action('edit_user_profile', [$this, 'renderUserWishlists'], 21, 1);

		add_action('admin_post_premmerce_delete_wishlist', [$this, 'deleteWishlist']);

		add_action('wp_trash_post', [$this, 'deleteProductFromWishlists'], 10, 1);
		add_action('save_post', [$this, 'deleteProductFromWishlistStatus'], 10, 2);
	}

	/**
	 * Delete product from wishlists on change product status
	 *
	 * @param int $postId
	 * @param \WP_Post $post
	 */
	public function deleteProductFromWishlistStatus($postId, \WP_Post $post){
		if($post->post_type == 'product'){
			if(!in_array($post->post_status, ['publish', 'trash'])){
				$this->deleteProductFromWishlists($postId);
			}
		}
	}

	/**
	 * Get product id and variant ids and delete them from wishlists
	 *
	 * @param int $productId
	 */
	public function deleteProductFromWishlists($productId){
		$productId = (int)$productId;
		$product   = wc_get_product($productId);

		if($product){
			$ids   = $product->get_children();
			$ids[] = $productId;

			foreach($ids as $id){
				$this->deleteProductFromWishlist($id);
			}
		}
	}

	/**
	 * Delete product from all wishlists
	 *
	 * @param $productId
	 */
	private function deleteProductFromWishlist($productId){
		$wishlists = $this->model->getWishlistsByProductId($productId);

		foreach($wishlists as $wishlist){
			$this->model->deleteProductFromWishlist($wishlist['wishlist_key'], $productId);
		}
	}

	/**
	 * Render user wishlist table
	 *
	 * @param \WP_User $user
	 */
	public function renderUserWishlists(\WP_User $user){
		$this->registerAssets();

		$wishlists = $this->model->getWishlists(['where_in' => ['user_id' => $user->ID]]);

		$this->fileManager->includeTemplate('admin/wishlist-user-list.php', [
			'wishlists' => $wishlists,
		]);
	}

	/**
	 * Add submenu to premmerce menu page
	 *
	 * @return false|string
	 */
	public function addMenuPage(){
		global $admin_page_hooks;

		$premmerceMenuExists = isset($admin_page_hooks['premmerce']);

		$svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="16" style="fill:#82878c" viewBox="0 0 20 16"><g id="Rectangle_7"> <path d="M17.8,4l-0.5,1C15.8,7.3,14.4,8,14,8c0,0,0,0,0,0H8h0V4.3C8,4.1,8.1,4,8.3,4H17.8 M4,0H1C0.4,0,0,0.4,0,1c0,0.6,0.4,1,1,1 h1.7C2.9,2,3,2.1,3,2.3V12c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1V1C5,0.4,4.6,0,4,0L4,0z M18,2H7.3C6.6,2,6,2.6,6,3.3V12 c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1v-1.7C8,10.1,8.1,10,8.3,10H14c1.1,0,3.2-1.1,5-4l0.7-1.4C20,4,20,3.2,19.5,2.6 C19.1,2.2,18.6,2,18,2L18,2z M14,11h-4c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4c0.6,0,1-0.4,1-1C15,11.4,14.6,11,14,11L14,11z M14,14 c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C15,14.4,14.6,14,14,14L14,14z M4,14c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1 c0.6,0,1-0.4,1-1C5,14.4,4.6,14,4,14L4,14z"/></g></svg>';
		$svg = 'data:image/svg+xml;base64,' . base64_encode($svg);

		if(!$premmerceMenuExists){
			add_menu_page(
				'Premmerce',
				'Premmerce',
				'manage_options',
				'premmerce',
				'',
				$svg
			);
		}

		$page = add_submenu_page(
			'premmerce',
			__('Wishlists', WishlistPlugin::DOMAIN),
			__('Wishlists', WishlistPlugin::DOMAIN),
			'manage_options',
			WishlistPlugin::DOMAIN,
			[$this, 'controller']
		);

		if(!$premmerceMenuExists){
			global $submenu;
			unset($submenu['premmerce'][0]);
		}

		return $page;
	}

	public function addFullPack(){
		global $submenu;

		if(!function_exists('get_plugins')){
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();

		$premmerceInstalled = array_key_exists('premmerce-premium/premmerce.php', $plugins)
		                      || array_key_exists('premmerce/premmerce.php', $plugins);

		if(!$premmerceInstalled){
			$submenu['premmerce'][999] = [
				'Get premmerce full pack',
				'manage_options',
				admin_url('plugin-install.php?tab=plugin-information&plugin=premmerce'),
			];
		}
	}

	/**
	 * Module controller
	 */
	public function controller(){
		$this->registerAssets();

		$data = $_POST;

		$this->controllerBulkActions($data);
		$this->controllerExtraTablenav($data);
		$this->controllerMessages();

		if(isset($_GET['wl-action'])){
			$ptAction = $_GET['wl-action'];

			switch($ptAction){
				case 'view':
					$id = isset($_GET['wl-id'])? (int)$_GET['wl-id'] : null;
					$this->controllerView($id);
			}
		}else{
			$this->controllerPage();
		}
	}

	/**
	 * Module controller view wishlist products
	 *
	 * @param int $id
	 */
	private function controllerView($id){
		if($id && $wishlist = $this->model->getWishlistById($id)){
			$user = get_user_by('ID', $wishlist['user_id']);

			$this->fileManager->includeTemplate('admin/wishlist-view.php', [
				'table'        => new WishlistViewTable($id, $this->model),
				'wishlistName' => $wishlist['name'],
				'userName'     => $user? $user->user_login : 'No user',
				'userLink'     => $wishlist['user_id']? get_edit_user_link($wishlist['user_id']) : null,
			]);
		}else{
			$this->setMessages([__('You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', WishlistPlugin::DOMAIN)], 'error');
			$this->showMessages();
		}
	}

	/**
	 * Control Bulk actions
	 *
	 * @param array $data
	 */
	private function controllerBulkActions($data){
		if(isset($data['action']) && $data['action'] != - 1){
			$action = $data['action'];
		}elseif(isset($data['action2']) && $data['action2'] != - 1){
			$action = $data['action2'];
		}else{
			$action = '';
		}

		switch($action){
			case 'delete':
				$this->delete($data);
				break;
		}
	}

	/**
	 * Controller table filters/actions
	 *
	 * @param string $data
	 */
	private function controllerExtraTablenav($data){
		if(isset($data['deleteByModifiedDate'])){
			$this->deleteByModifiedDate($data['deleteByModifiedDate']);
		}
	}

	/**
	 * Control show error/complete messages
	 */
	private function controllerMessages(){
		if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
			$this->showMessages();
		}
	}

	/**
	 *  Module controller wishlist table
	 */
	private function controllerPage(){
		$current = isset($_GET['tab'])? $_GET['tab'] : null;

		$tabs['wishlist-list'] = __('Wishlists', WishlistPlugin::DOMAIN);

		if(function_exists('premmerce_pw_fs')){
			$tabs['contact'] = __('Contact Us', WishlistPlugin::DOMAIN);
			if(premmerce_pw_fs()->is_registered()){
				$tabs['account'] = __('Account', WishlistPlugin::DOMAIN);
			}
		}

		$this->fileManager->includeTemplate('admin/main.php', [
			'table'   => new WishlistTable($this->fileManager, $this->model),
			'current' => $current? $current : 'wishlist-list',
			'tabs'    => $tabs,
		]);
	}

	/**
	 * Delete wishlist by id
	 *
	 * @param array $data
	 */
	public function delete($data){
		if(isset($data['ids'])){
			if($this->model->deleteWishlists($data['ids'])){
				$this->setMessages([__('Items deleted.', WishlistPlugin::DOMAIN)]);
			}else{
				$this->setMessages([__('Error.', WishlistPlugin::DOMAIN)], 'error');
			}
		}
	}

	/**
	 * Delete wishlists by modified date
	 *
	 * @param string $type
	 */
	public function deleteByModifiedDate($type){
		if(!empty($type) && $type != - 1){
			if($this->model->deleteWishlistsByDateInterval($type)){
				$this->setMessages([__('Items deleted.', WishlistPlugin::DOMAIN)]);
			}else{
				$this->setMessages([__('Error.', WishlistPlugin::DOMAIN)], 'error');
			}
		}
	}

	/**
	 * Row action delete for table wishlist list
	 */
	public function deleteWishlist(){
		if(isset($_GET['wishlist']) && !empty($_GET['wishlist'])){
			$this->delete(['ids' => [$_GET['wishlist']]]);
		}

		wp_redirect(admin_url('admin.php') . '?page=premmerce-wishlist');
	}

	/**
	 * Control show error/complete messages
	 */
	public function messages(){
		if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
			$this->showMessages();
		}
	}

	/**
	 * Set error/complete messages
	 *
	 * @param array $message
	 * @param string $type
	 */
	private function setMessages($message = [], $type = 'updated'){
		$_SESSION['msg']     = $message;
		$_SESSION['msgType'] = $type;
	}

	/**
	 * Show error/complete messages
	 */
	private function showMessages(){
		foreach($_SESSION['msg'] as $m){
			echo '<div class="' . $_SESSION['msgType'] . '"><p>' . $m . '</p></div>';
		}

		unset($_SESSION['msg']);
		unset($_SESSION['msgType']);
	}

	/**
	 * Register plugin assets
	 */
	private function registerAssets(){
		wp_enqueue_style(WishlistPlugin::DOMAIN . '-style', $this->fileManager->locateAsset('admin/css/premmerce-wishlist.css'));
		wp_enqueue_script(WishlistPlugin::DOMAIN . '-scripts', $this->fileManager->locateAsset('admin/js/premmerce-wishlist.js'));
	}
}
