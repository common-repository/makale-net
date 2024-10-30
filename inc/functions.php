<?php

/**
 * @param $username
 * @param $api
 * @return bool
 * Kullanıcı adı ve api doğrulama
 */
function makale_net_is_api( $username, $api ) {
    $response = wp_remote_get( 'https://www.makale.net/api/uyelik_bilgisi?API-KEY=' . $api );
    $result   = wp_remote_retrieve_body( $response );

    if( !$result )
        return false;

    $obj = json_decode( $result );

    if( $obj->error )
        return false;

    $objectUserName = $obj->musteri->kullaniciAdi;

    if( $username != $objectUserName )
        return false;

    return true;
}

/**
 * @return bool
 * WP_Options tablosundaki verilere göre apiyi server tarafında doğrular
 */
function makale_net_check_api() {
    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];
    $username = $settings[ 'username' ];

    return makale_net_is_api( $username, $api );
}

/**
 * Sunucudaki api doğrulanmazsa WP_Options tablosundaki aktivasyon durumunu false yapar
 * Kullanıcıyı Ayarlar sayfasına yönlendirir
 */
function makale_net_change_status_if_needed() {
    $settings = get_option( 'makale_net', true );
    if( !makale_net_check_api() ) {
        $settings[ 'status' ] = false;
        update_option( 'makale_net', $settings );

        $url = admin_url( 'admin.php?page=makale-net_ayarlar' );

        $string = '<script type="text/javascript">';
        $string .= 'window.location = "' . $url . '"';
        $string .= '</script>';
        echo $string;
    } else {
        $settings[ 'status' ] = true;
        update_option( 'makale_net', $settings );
    }
}

/**
 * @return bool
 * WP_Options tablosundaki verilere göre apiyi yerel olarak doğrulama
 */
function is_makale_net_activated() {
    $settings = get_option( 'makale_net', true );

    if( !empty( $settings[ 'status' ] ) ) {
        $status = $settings[ 'status' ];

        if( $status )
            return true;
    }

    return false;
}

/**
 * @return array
 * Sitedeki kategorileri getirir
 */
function makale_net_categories() {
    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];
    $response = wp_remote_get( 'https://www.makale.net/api/hazirmakale_kategori_listesi?API-KEY=' . $api );
    $result   = wp_remote_retrieve_body( $response );
    $objects  = json_decode( $result );
    $catArray = array();

    foreach( $objects->kategoriler as $object ) {
        $catArray[ $object->ID ] = $object->kategori;
    }

    return $catArray;
}

/**
 * @return array|mixed|object
 * Üyelik bilgilerini çek
 */
function makale_net_uyelik_bilgileri() {
    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];
    $response = wp_remote_get( 'https://www.makale.net/api/uyelik_bilgisi?API-KEY=' . $api );
    $result   = wp_remote_retrieve_body( $response );
    $objects  = json_decode( $result );
    return $objects->musteri;
}

function makale_net_durum_degistir( $makaleID = null, $durum = null, $type = null ) {
    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];

    if( $type == 'hazirmakale' )
        $typeID = 'HMID';
    else
        $typeID = 'MAKID';

    $url      = 'https://www.makale.net/api/' . $type . '_kullanim_degistir?API-KEY=' . $api . '&' . $typeID . '[]=' . $makaleID . '&durum=' . $durum;
    $response = wp_remote_get( $url );
    $result   = wp_remote_retrieve_body( $response );
    $objects  = json_decode( $result );

    return $objects->error;
}

function makale_net_arsiv_degistir( $makaleID = null, $durum = null ) {
    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];
    $url      = 'https://www.makale.net/api/makalesiparisi_arsiv_degistir?API-KEY=' . $api . '&SMID[]=' . $makaleID . '&arsiv=' . $durum;
    $response = wp_remote_get( $url );
    $result   = wp_remote_retrieve_body( $response );
    $objects  = json_decode( $result );

    return $objects->error;
}

/**
 * Session start
 */
function makale_net_session_start() {
    if( !session_id() )
        session_start();
}

add_action( 'admin_init', 'makale_net_session_start', 1 );