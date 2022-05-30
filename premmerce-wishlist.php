<?php

use Premmerce\Wishlist\WishlistPlugin;

/**
 *
 * @wordpress-plugin
 * Plugin Name:       Catalopiso Project Board [Modded]
 * Plugin URI:        https://tugasvirtualsolutions.com/
 * Description:       This plugin provides the possibility for your customers to create wishlists with the further possibility to share them with friends.
 * Version:           1.2.4
 * Author:            Tugas Virtual Solution
 * Author URI:        https://tugasvirtualsolutions.com/
 * License:           GPL-2.0+
 * Text Domain:       project-board
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 6.3.1
 */

// If this file is called directly, abort.
if ( ! defined('WPINC')) {
    die;
}

call_user_func(function () {

    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

    if ( ! get_option('premmerce_version')) {
        require_once plugin_dir_path(__FILE__) . '/freemius.php';
    }

    $main = new WishlistPlugin(__FILE__);

    register_activation_hook(__FILE__, [$main, 'activate']);

    register_deactivation_hook(__FILE__, [$main, 'deactivate']);

    if (function_exists('premmerce_pw_fs')) {
        premmerce_pw_fs()->add_action('after_uninstall', [WishlistPlugin::class, 'uninstall']);
    } else {
        register_uninstall_hook(__FILE__, [WishlistPlugin::class, 'uninstall']);
    }


    $main->run();
});

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/strayd0g/project-board-catalopiso',
	__FILE__,
	'premmerce-wishlist'
);

$myUpdateChecker->setBranch('main');
$myUpdateChecker->setAuthentication('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');

