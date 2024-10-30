<?php

/**
 * Satın al
 */
function makale_net_satin_al() {
    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];
    $ID       = sanitize_text_field( $_POST[ 'ID' ] );
    $url      = 'https://www.makale.net/api/hazirmakale_satin_al?API-KEY=' . $api . '&HMID[]=' . $ID;
    $response = wp_remote_get( $url );
    $result   = wp_remote_retrieve_body( $response );
    $objects  = json_decode( $result );

    if( !$objects->error )
        echo json_encode(
            array(
                'type'         => 'success',
                'message'      => __( 'Satın Alma Başarılı!', 'makale-net' ),
                'SID'          => $objects->SID,
                'html'         => sprintf( __( '<strong>Sipariş kodunuz: %s</strong>', 'makale-net' ), $objects->siparisNo ),
                'guncelBakiye' => $objects->musteri->bakiye,
            ) );
    else
        echo json_encode( array( 'type' => 'error', 'message' => __( 'Satın Alma Başarısız!', 'makale-net' ), 'html' => '' ) );

    exit();
}

add_action( 'wp_ajax_makale_net_satin_al', 'makale_net_satin_al' );

/**
 * Makele yayımla
 */
function makale_net_makale_yayimla() {
    $makaleID    = sanitize_text_field( $_POST[ 'ID' ] );
    $baslik      = wp_strip_all_tags( sanitize_text_field( $_POST[ 'baslik' ] ) );
    $icerik      = apply_filters('the_content', $_POST[ 'icerik' ]);
    $kategoriler = array( sanitize_text_field( $_POST[ 'cat' ] ) );
    $author      = sanitize_text_field( $_POST[ 'author' ] );
    $status      = sanitize_text_field( $_POST[ 'status' ] );
    $type        = sanitize_text_field( $_POST[ 'type' ] );

    $post_args = array(
        'post_title'    => $baslik,
        'post_content'  => $icerik,
        'post_status'   => $status,
        'post_category' => $kategoriler,
        'post_author'   => $author,
    );

    $postID = wp_insert_post( $post_args );

    if( $status == 'publish' )
        $message = __( 'Makaleniz yayımlandı!', 'makale-net' );
    else
        $message = __( 'Makaleniz taslak olarak kaydedildi!', 'makale-net' );

    if( $postID ) {
        makale_net_durum_degistir( $makaleID, 'true', $type );
        echo json_encode(
            array( 'type'    => 'success',
                   'message' => $message,
                   'button'  => __( 'Düzenle', 'makale-net' ),
                   'url'     => get_edit_post_link( $postID, 'revision' ),
                   'html'    => __( 'Düzenleme yapmak ister misiniz?', 'makale-net' ),
            )
        );
    } else
        echo json_encode( array( 'type' => 'error', 'message' => __( 'Makale yayımlanamadı!', 'makale-net' ) ) );
    exit();
}

add_action( 'wp_ajax_makale_net_makale_yayimla', 'makale_net_makale_yayimla' );

/**
 * Toplu makele yayımla
 */
function makale_net_makale_yayimla_bulk() {
    $status      = sanitize_text_field( $_POST[ 'status' ] );
    $type        = sanitize_text_field( $_POST[ 'type' ] );
    $kategoriler = array( sanitize_text_field( $_POST[ 'cat' ] ) );
    $author      = sanitize_text_field( $_POST[ 'author' ] );

    foreach( $_POST[ 'posts' ] as $object ) {
        $baslik    = wp_strip_all_tags( sanitize_text_field( $object[ 'makaleBaslik' ] ) );
        $icerik    = apply_filters('the_content', $object[ 'makaleIcerik' ]);
        $makaleID  = sanitize_text_field( $object[ 'makaleID' ] );
        $post_args = array(
            'post_title'    => $baslik,
            'post_content'  => $icerik,
            'post_status'   => $status,
            'post_category' => $kategoriler,
            'post_author'   => $author,
        );

        wp_insert_post( $post_args );
        makale_net_durum_degistir( $makaleID, 'true', $type );
    }

    if( $status == 'publish' )
        $message = __( 'Seçilen makaleler yayımlandı!', 'makale-net' );
    else
        $message = __( 'Seçilen makaleler taslak olarak kaydedildi!', 'makale-net' );

    echo json_encode(
        array( 'type'    => 'success',
               'message' => $message,
        )
    );
    exit();
}

add_action( 'wp_ajax_makale_net_makale_yayimla_bulk', 'makale_net_makale_yayimla_bulk' );

/**
 * Durum değiştir
 */
function makale_net_ajax_durum_degistir() {
    $makaleID = sanitize_text_field( $_POST[ 'ID' ] );
    $durum    = sanitize_text_field( $_POST[ 'durum' ] );
    $type     = sanitize_text_field( $_POST[ 'type' ] );

    makale_net_durum_degistir( $makaleID, $durum, $type );

    echo json_encode( array( 'type' => 'success', 'message' => __( 'Durum Değiştirildi!', 'makale-net' ), 'mdurum' => $durum ) );
    exit();
}

add_action( 'wp_ajax_makale_net_ajax_durum_degistir', 'makale_net_ajax_durum_degistir' );

/**
 * Toplu durum değiştir
 */
function makale_net_hazir_makale_durum_bulk() {
    $status = sanitize_text_field( $_POST[ 'status' ] );
    $type   = sanitize_text_field( $_POST[ 'type' ] );

    foreach( $_POST[ 'posts' ] as $object ) {
        $makaleID  = sanitize_text_field( $object[ 'makaleID' ] );

        makale_net_durum_degistir( $makaleID, $status, $type );
    }

    if( $status == 'true' )
        $message = __( 'Seçilen makaleler kullanıldı olarak işaretlendi!', 'makale-net' );
    else
        $message = __( 'Seçilen makaleler kullanılmadı olarak işaretlendi!', 'makale-net' );

    echo json_encode(
        array( 'type'    => 'success',
               'message' => $message,
        )
    );
    exit();
}

add_action( 'wp_ajax_makale_net_hazir_makale_durum_bulk', 'makale_net_hazir_makale_durum_bulk' );

/**
 *  Sipariş Ver
 */
function makale_net_siparis_ver_fiyat() {
    $satinAl  = sanitize_text_field( $_POST[ 'satinAl' ] );

    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];
    $url      = 'https://www.makale.net/api/makalesiparisi_satin_al?API-KEY=' . $api;

    $makaleler = array();

    foreach ($_POST['makaleler'] as $key => $makale){
        $makaleler[$key] = array(
            'baslik'        => sanitize_text_field( $makale[ 'baslik' ] ),
            'kelimeSayisi'  => sanitize_text_field( $makale[ 'kelimeSayisi' ] ),
            'linkler'       => sanitize_text_field( $makale[ 'linkler' ] ),
            'anahtarKelime' => sanitize_text_field( $makale[ 'anahtarKelime' ] ),
            'kullanim'      => sanitize_text_field( $makale[ 'kullanim' ] ),
        );
    }

    $body = array(
        'express'     => sanitize_text_field( $_POST[ 'express' ] ),
        'projeAdi'    => sanitize_text_field( $_POST[ 'projeAdi' ] ),
        'MSID'        => sanitize_text_field( $_POST[ 'MSID' ] ),
        'MTUID'       => sanitize_text_field( $_POST[ 'MTUID' ] ),
        'MKID'        => sanitize_text_field( $_POST[ 'MKID' ] ),
        'MAID'        => sanitize_text_field( $_POST[ 'MAID' ] ),
        'siparisNotu' => sanitize_text_field( $_POST[ 'siparisNotu' ] ),
        'makaleler'   => $makaleler,
        'satinAl'     => $satinAl
    );

    $args = array(
        'method'      => 'POST',
        'timeout'     => '30',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array('Cache-Control'=>'no-cache'),
        'body'        => $body,
        'cookies'     => array()
    );

    $response = wp_remote_post( $url, $args );
    $result   = wp_remote_retrieve_body( $response );
    $objects  = json_decode( $result );

    if( $objects->error )
        echo json_encode( array( 'type' => 'error', 'result' => $result, 'title' => $objects->error_msg ) );
    else
        echo json_encode(
            array(
                'type'         => $satinAl == '0' ? 'warning' : 'success',
                'title'        => $satinAl == '0' ? __( 'Siparişi Onaylayın!', 'makale-net' ) : __( 'Siparişiniz Onaylandı!', 'makale-net' ),
                'toplamKelime' => $objects->siparisDetay->toplamKelimeSayisi,
                'toplamMakale' => $objects->siparisDetay->toplamMakaleAdedi,
                'toplamFiyat'  => $objects->siparisDetay->toplamFiyat,
                'teslimTarihi' => $objects->siparisDetay->teslimTarihi,
                'url'          => esc_url( admin_url( 'admin.php?page=makale-net_makale_siparislerim' ) ),
            )
        );

    exit();
}

add_action( 'wp_ajax_makale_net_siparis_ver_fiyat', 'makale_net_siparis_ver_fiyat' );

/**
 * Arşiv değiştir
 */
function makale_net_ajax_arsiv_degistir() {
    $makaleID = sanitize_text_field( $_POST[ 'ID' ] );
    $durum    = sanitize_text_field( $_POST[ 'arsiv' ] );

    makale_net_arsiv_degistir( $makaleID, $durum );

    echo json_encode( array( 'type' => 'success', 'message' => __( 'Arşiv Değiştirildi!', 'makale-net' ), 'mdurum' => $durum ) );
    exit();
}

add_action( 'wp_ajax_makale_net_ajax_arsiv_degistir', 'makale_net_ajax_arsiv_degistir' );

/**
 * Toplu makele arşiv değiştir
 */
function makale_net_hazir_makale_arsiv_bulk() {
    $durum = sanitize_text_field( $_POST[ 'arsiv' ] );

    foreach( $_POST[ 'posts' ] as $object ) {
        $makaleID = sanitize_text_field( $object[ 'makaleID' ] );
        makale_net_arsiv_degistir( $makaleID, $durum );
    }

    if( $durum == 'true' )
        $message = __( 'Seçilen siparişler arşivlendi!', 'makale-net' );
    else
        $message = __( 'Seçilen siparişler arşivden çıkartıldı!', 'makale-net' );

    echo json_encode(
        array( 'type'    => 'success',
               'message' => $message,
        )
    );
    exit();
}

add_action( 'wp_ajax_makale_net_hazir_makale_arsiv_bulk', 'makale_net_hazir_makale_arsiv_bulk' );