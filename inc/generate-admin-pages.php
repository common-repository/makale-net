<?php

/**
 * Main page
 */
function makale_net_main_menu() {
    $page_title = __( 'Makale.net', 'makale-net' );
    $menu_title = __( 'Makale.net', 'makale-net' );
    $capability = 'manage_options';
    $menu_slug = 'makale-net';
    $function = 'makale_net_main_page_display';
    $icon_url = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/img/menu_icon.png';
    $position = 2;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

add_action( 'admin_menu', 'makale_net_main_menu' );

/**
 * Sipariş Ver
 */
function makale_net_siparis_ver_menu() {
    if( !is_makale_net_activated() )
        return;

    $parent_slug = 'makale-net';
    $page_title = __( 'Sipariş Ver', 'makale-net' );
    $menu_title = __( 'Sipariş Ver', 'makale-net' );
    $capability = 'manage_options';
    $menu_slug = 'makale-net_siparis-ver';
    $function = 'makale_net_siparis_ver_display';

    add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}

add_action( 'admin_menu', 'makale_net_siparis_ver_menu' );

/**
 * Makale Siparişlerim
 */
function makale_net_makale_siparislerim_menu() {
    if( !is_makale_net_activated() )
        return;

    $parent_slug = 'makale-net';
    $page_title = __( 'Makale Siparişlerim', 'makale-net' );
    $menu_title = __( 'Makale Siparişlerim', 'makale-net' );
    $capability = 'manage_options';
    $menu_slug = 'makale-net_makale_siparislerim';
    $function = 'makale_net_makale_siparislerim_display';

    add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}

add_action( 'admin_menu', 'makale_net_makale_siparislerim_menu' );

/**
 * Hazır makale al
 */
function makale_net_hazir_makale_al_menu() {
    if( !is_makale_net_activated() )
        return;

    $parent_slug = 'makale-net';
    $page_title = __( 'Hazır Makale Satın Al', 'makale-net' );
    $menu_title = __( 'Hazır Makale Al', 'makale-net' );
    $capability = 'manage_options';
    $menu_slug = 'makale-net_hazir-makale-al';
    $function = 'makale_net_hazir_makale_al_display';

    add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}

add_action( 'admin_menu', 'makale_net_hazir_makale_al_menu' );

/**
 * Hazır makalelerim
 */
function makale_net_hazir_makalelerim_menu() {
    if( !is_makale_net_activated() )
        return;

    $parent_slug = 'makale-net';
    $page_title = __( 'Hazır Makalelerim', 'makale-net' );
    $menu_title = __( 'Hazır Makalelerim', 'makale-net' );
    $capability = 'manage_options';
    $menu_slug = 'makale-net_hazir-makalelerim';
    $function = 'makale_net_hazir_makalelerim_display';

    add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
}

add_action( 'admin_menu', 'makale_net_hazir_makalelerim_menu' );

/**
 * Bakiye yükle
 */
// function makale_net_bakiye_yukle_menu() {
//     if( !is_makale_net_activated() )
//         return;

//     $parent_slug = 'makale-net';
//     $page_title = __( 'Bakiye Yükle', 'makale-net' );
//     $menu_title = __( 'Bakiye Yükle', 'makale-net' );
//     $capability = 'manage_options';
//     $menu_slug = 'makale-net_bakiye-yukle';
//     $function = 'makale_net_bakiye_yukle_display';

//     add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
// }

// add_action( 'admin_menu', 'makale_net_bakiye_yukle_menu' );

/**
 * Bakiye yükle test
 */

 
/**
* add external link to Tools area
*/
function makale_net_bakiye_yukle_menu() {
    if( !is_makale_net_activated() )
        return;

    global $submenu;
    $url  = 'https://www.makale.net/uye/bakiye_yukle';
    $text = __('Bakiye Yükle', 'makale-netk');
    $submenu['makale-net'][] = array(
        "<span class='mn-bakiye-yukle-link'>$text</span>", 
        'manage_options', 
        $url
    );
}

add_action('admin_menu', 'makale_net_bakiye_yukle_menu');


// add_action('admin_menu', function() {
//     global $submenu;
//     echo "<pre>", print_r($submenu,1), "</pre>n";
// });



/**
 * Ayarlar
 */
function makale_net_ayarlar_menu() {
    $parent_slug = 'makale-net';
    $page_title = __( 'Ayarlar', 'makale-net' );
    $menu_title = __( 'Ayarlar', 'makale-net' );
    $capability = 'manage_options';
    $menu_slug = 'makale-net_ayarlar';
    $function = 'makale_net_ayarlar_display';

    add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    // remove the "main" submenue page
    remove_submenu_page('makale-net', 'makale-net');
}

add_action( 'admin_menu', 'makale_net_ayarlar_menu' );