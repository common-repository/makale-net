<?php

function makale_net_enqueue_admin_scripts( $hook ) {
    if( $hook == 'toplevel_page_makale-net'
        || $hook == 'makale-net_page_makale-net_siparis-ver'
        || $hook == 'makale-net_page_makale-net_makale_siparislerim'
        || $hook == 'makale-net_page_makale-net_hazir-makale-al'
        || $hook == 'makale-net_page_makale-net_hazir-makalelerim'
        || $hook == 'makale-net_page_makale-net_bakiye-yukle'
        || $hook == 'makale-net_page_makale-net_ayarlar' ) {

        // Styles
        wp_enqueue_style( 'mn-admin-util', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/util.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-main', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/main.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-pagination', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/pagination.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/select2.min.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-sweet-alert', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/sweetalert2.min.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-bootstrap-buttons', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/bootstrap-buttons.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-fontawesome5', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/fontawesome.all.min.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-animate', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/animate.min.css', array(), MAKALE_NET_PLUGIN_VERSION );
        wp_enqueue_style( 'mn-admin-custom', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/css/makale-net.css', array(), MAKALE_NET_PLUGIN_VERSION );

        // Scripts
        wp_enqueue_script( 'mn-admin-main', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/js/main.js', array( 'jquery' ), MAKALE_NET_PLUGIN_VERSION, true );
        wp_enqueue_script( 'mn-admin-pagination', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/js/pagination.js', array( 'jquery' ), MAKALE_NET_PLUGIN_VERSION, true );
        wp_enqueue_script( 'mn-admin-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/js/select2.min.js', array( 'jquery' ), MAKALE_NET_PLUGIN_VERSION, true );
        wp_enqueue_script( 'mn-admin-sweet-alert', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/js/sweetalert2.all.min.js', array( 'jquery' ), MAKALE_NET_PLUGIN_VERSION, true );
        wp_enqueue_script( 'mn-admin-tippy', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/js/tippy.all.min.js', array( 'jquery' ), MAKALE_NET_PLUGIN_VERSION, true );
        wp_enqueue_script( 'mn-admin-repeater', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/js/jquery.repeater.min.js', array( 'jquery' ), MAKALE_NET_PLUGIN_VERSION, true );

        wp_register_script( 'mn-admin-custom', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/admin/js/makale-net.js', array( 'jquery' ), MAKALE_NET_PLUGIN_VERSION, true );
        $translation_array = array(
            'ajaxUrl'                          => admin_url( 'admin-ajax.php' ),
            'paginationPrev'                   => __( 'Önceki', 'makale-net' ),
            'paginationNext'                   => __( 'Sonraki', 'makale-net' ),
            'swalOk'                           => __( 'Tamam', 'makale-net' ),
            'swalApply'                        => __( 'Uygula', 'makale-net' ),
            'swalConfirm'                      => __( 'Onayla', 'makale-net' ),
            'swalYes'                          => __( 'Evet', 'makale-net' ),
            'swalSend'                         => __( 'Gönder', 'makale-net' ),
            'swalBuy'                          => __( 'Satın Al', 'makale-net' ),
            'swalClose'                        => __( 'Kapat', 'makale-net' ),
            'swalDraft'                        => __( 'Taslak Olarak Kaydet', 'makale-net' ),
            'swalBakiyeOnayBefore'             => __( 'Bakiyenizden', 'makale-net' ),
            'swalBakiyeOnayAfter'              => __( 'kredi düşecek. Devam etmek istiyor musunuz?', 'makale-net' ),
            'swalBuySuccess'                   => __( 'Satın alma başarılı!', 'makale-net' ),
            'swalBuyError'                     => __( 'Satın alma başarısız!', 'makale-net' ),
            'swalPublish'                      => __( 'Yayımla', 'makale-net' ),
            'swalPublishAuthor'                => __( 'Yazar', 'makale-net' ),
            'swalPublishNow'                   => __( 'Hemen Yayımla', 'makale-net' ),
            'swalSelectCat'                    => __( 'Kategori seçin', 'makale-net' ),
            'swalMustSelectCat'                => __( 'Lütfen kategori seçiniz!', 'makale-net' ),
            'swalPublishOnayAfter'             => __( 'adlı makale için yapmak istediğinizi seçin.', 'makale-net' ),
            'swalBulkPublishOnay'              => __( 'Seçilen makaleler yayımlanacak. Devam etmek istiyor musunuz?', 'makale-net' ),
            'swalBulkDraftOnay'                => __( 'Seçilen makaleler taslak olarak kaydedilecek. Devam etmek istiyor musunuz?', 'makale-net' ),
            'swalBulkArsivOnay'                => __( 'Seçilen siparişler arşivlendi olarak kaydedilecek. Devam etmek istiyor musunuz?', 'makale-net' ),
            'swalBulkNoArsivOnay'              => __( 'Seçilen siparişler arşivden çıkartılacak. Devam etmek istiyor musunuz?', 'makale-net' ),
            'swalBulkStatusTrueOnay'           => __( 'Seçilen makaleler kullanıldı olarak işaretlenecek. Devam etmek istiyor musunuz?', 'makale-net' ),
            'swalBulkStatusFalseOnay'          => __( 'Seçilen makaleler kullanılmadı olarak işaretlenecek. Devam etmek istiyor musunuz?', 'makale-net' ),
            'swalDurumOnay'                    => __( 'Durumu değiştirmek istediğinizden emin misiniz?', 'makale-net' ),
            'swalCantDeleteFirst'              => __( 'İlk kaydı silemezsiniz', 'makale-net' ),
            'swalBulkTitle'                    => __( 'Girilen başlık tüm satırlara eklenecektir.', 'makale-net' ),
            'swalBulkTitlePlaceholder'         => __( 'ÖRN: Web sitesi nasıl kurulur?', 'makale-net' ),
            'swalMustTitle'                    => __( 'Lütfen başlık girin!', 'makale-net' ),
            'swalBulkKelimeSayisiTitle'        => __( 'Seçilen kelime sayısı tüm satırlarda etkili olacaktır.', 'makale-net' ),
            'swalBulkAnahtarKelimeTitle'       => __( 'Girilen anahtar kelime tüm satırlara eklenecektir. Lütfen anahtar kelimeleri virgül (,) ile ayırınız.', 'makale-net' ),
            'swalBulkAnahtarKelimePlaceholder' => __( 'ÖRN: internet sitesi, web sitesi, responsive tasarım', 'makale-net' ),
            'swalMustAnahtarKelime'            => __( 'Lütfen anahtar kelime girin!', 'makale-net' ),
            'swalBulkKullanimTitle'            => __( 'Seçilen kullanım miktarı tüm satırlarda etkili olacaktır.', 'makale-net' ),
            'swalAddTagLinkTitle'              => __( 'Anahtar kelimelerinizi linklendirin.', 'makale-net' ),
            'swalMustTagTitle'                 => __( 'Link verebilmek için önce anahtar kelime girmelisiniz.', 'makale-net' ),
            'swalTagLinkPlaceholder'           => __( 'https://websiteniz.com', 'makale-net' ),
            'swalMustTagText'                  => __( 'Gireceğiniz anahtar kelimelerin arasına " virgül (,) " koymayı unutmayınız.', 'makale-net' ),
            'swalTeslimTarihi'                 => __( 'Tahmini teslim tarihi:', 'makale-net' ),
            'swalFiyati'                       => __( 'Fiyat:', 'makale-net' ),
            'swalBakiyeniz'                    => __( 'Bakiyeniz:', 'makale-net' ),
            'swalTagLinksInfo'                 => __( '<strong>Dikkat:</strong> Linklendirme işlemi onaylandıktan sonra, seçili satırda anahtar kelime değişikliği yapılamaz. Satın aldığınız makalelerdeki anahtar kelimeler belirttiğiniz URL linklendirmesi yapılarak teslim edilir.', 'makale-net' ),
            'jsApi'                            => !empty( get_option( 'makale_net', true )[ 'api' ] ) ? get_option( 'makale_net', true )[ 'api' ] : '',
        );
        wp_localize_script( 'mn-admin-custom', 'mnObject', $translation_array );
        wp_enqueue_script( 'mn-admin-custom' );
    }
}

add_action( 'admin_enqueue_scripts', 'makale_net_enqueue_admin_scripts' );