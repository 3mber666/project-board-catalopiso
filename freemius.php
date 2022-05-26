<?php

// Create a helper function for easy SDK access.
function premmerce_pw_fs() {
	global $premmerce_pw_fs;

	if ( ! isset( $premmerce_pw_fs ) ) {
		// Include Freemius SDK.
		require_once dirname(__FILE__) . '/freemius/start.php';

		$premmerce_pw_fs = fs_dynamic_init( array(
			'id'                  => '1586',
			'slug'                => 'premmerce-wishlist',
			'type'                => 'plugin',
			'public_key'          => 'pk_8e2f82a2ee152b676f85c9c890dd6',
			'is_premium'          => false,
			'has_addons'          => false,
			'has_paid_plans'      => false,
			'menu'                => array(
				'slug'           => 'premmerce-wishlist',
				'account'        => false,
				'contact'        => false,
				'support'        => false,
				'parent'         => array(
					'slug' => 'premmerce',
				),
			),
		) );
	}

	return $premmerce_pw_fs;
}

// Init Freemius.
premmerce_pw_fs();
// Signal that SDK was initiated.
do_action( 'premmerce_pw_fs_loaded' );