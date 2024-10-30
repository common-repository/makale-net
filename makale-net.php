<?php
/*
Plugin Name: Makale.net
Plugin URI: https://wordpress.org/plugins/makale-net/
Description: Makale.net wordpress eklentisi, satın aldığınız %100 Özgün ve SEO uyumlu içerikleri Wordpress sitenizde yayınlamanızı sağlar. Eklenti üzerinden yeni SEO uyumlu hazır makaleler satın alabilir veya ihtiyaçlarınıza uygun makale siparişi verebilirsiniz.
Author: makale.net
Author URI: https://www.makale.net
Text Domain: makale-net
Domain Path: /languages/
Version: 1.0.3
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MAKALE_NET_PLUGIN_VERSION', '1.0.3' );

/**
 * Plugin hooks. Includes activation and deactivation hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/plugin-hooks.php';

/**
 * Register scripts
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/register-scripts.php';

/**
 * Generate admin pages
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/generate-admin-pages.php';

/**
 * Plugin functions
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/functions.php';

/**
 * Ajax functions
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/ajax-functions.php';

/**
 * Admin display
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-pages/main-page.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-pages/siparis-ver.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-pages/makale-siparislerim.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-pages/hazir-makale-al.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-pages/hazir-makalelerim.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-pages/bakiye-yukle.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-pages/settings.php';