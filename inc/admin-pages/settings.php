<?php

function makale_net_ayarlar_display() { ?>
    <div class="wrap">
        <div class="mn-d-flex">
            <h1><?php esc_html_e( 'Ayarlar', 'makale-net' ); ?></h1>
            <div class="ml-auto m-t-10">
                <img class="mn-logo" src="<?php echo esc_url( plugins_url( 'makale-net' ) . '/assets/admin/img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Logo', 'makale-net' ); ?>">
            </div>
        </div>

        <hr class="m-b-15">

        <?php
        $settings = get_option( 'makale_net', true );
        $optUsername = $settings[ 'username' ];
        $optApi = $settings[ 'api' ];

        if( makale_net_is_api( $optUsername, $optApi ) ) {
            $status = __( 'Doğrulanmış API-KEY', 'makale-net' );
            $statusClass = 'verified';
        } else {
            $status = __( 'Geçersiz API-KEY', 'makale-net' );
            $statusClass = 'unverified';
        }
        ?>

        <form method="post" action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" novalidate="novalidate">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="makale_net_username"><?php esc_html_e( 'Kullanıcı Adı:', 'makale-net' ); ?></label></th>
                        <td><input type="text" name="makale_net_username" id="makale_net_username" class="regular-text" placeholder="<?php esc_attr_e( 'Makale.net kullanıcı adı', 'makale-net' ); ?>" value="<?php echo esc_attr( $optUsername ); ?>"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="makale_net_api_key"><?php esc_html_e( 'API-KEY:', 'makale-net' ); ?></label></th>
                        <td><input type="text" name="makale_net_api_key" id="makale_net_api_key" class="regular-text" placeholder="<?php esc_attr_e( 'Makale.net API anahtarı', 'makale-net' ); ?>" value="<?php echo esc_attr( $optApi ); ?>"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="makale_net_api_key"><?php esc_html_e( 'API Durumu:', 'makale-net' ); ?></label></th>
                        <td><span class="mn-status <?php echo esc_attr( $statusClass ); ?>"><?php echo esc_html( $status ); ?></span></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><?php submit_button( __( 'Değişiklikleri Kaydet', 'makale-net' ), 'button button-primary', 'makale-net' ); ?></td>
                        <td><input type="hidden" name="action" value="settings_change_save"></td>
                    </tr>
                </tbody>
            </table>
        </form>

    <?php
    if( makale_net_is_api( $optUsername, $optApi ) ) {
        $uyelik = makale_net_uyelik_bilgileri();
        if( $uyelik->profilResmi )
            $img = $uyelik->profilResmi;
        else
            $img = plugins_url( 'makale-net' ) . '/assets/admin/img/user.png';
        ?>
		<h1><?php esc_html_e( 'Üyelik Bilgileri', 'makale-net' ); ?></h1>
        
        <hr class="m-b-15 m-t-15">
		
        
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="vertical-align: middle;">
                        <img class="mn-avatar" src="<?php echo esc_url( $img ); ?>" alt="<?php esc_attr_e( 'Profil Avatar', 'makale-net' ); ?>">
                    </th>
                    <td>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Bakiye:' ); ?></th>
                                    <td><?php echo esc_html( $uyelik->bakiye ); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Ad Soyad:' ); ?></th>
                                    <td><?php echo esc_html( $uyelik->isim ); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'E-posta:' ); ?></th>
                                    <td><?php echo esc_html( $uyelik->eposta ); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

    <?php } ?>

    </div>

    <?php

    //print_r( get_current_screen() );
}

function settings_change_save_callback() {
    if( isset( $_POST[ 'makale-net' ] ) ) {
        // $_POST tan gelen veriler
        $username = sanitize_text_field( $_POST[ 'makale_net_username' ] );
        $api      = sanitize_text_field( $_POST[ 'makale_net_api_key' ] );
        $siteUrl  = urlencode( get_home_url() );

        // Api kontrol et
        if( makale_net_is_api( $username, $api ) ) {
            $apiCheck = true;
            $postCheck = 'true';
        } else {
            $apiCheck = false;
            $postCheck = 'false';
        }

        $url        = 'https://www.makale.net/api/wp_eklenti_log?API-KEY=' . $api . '&siteURL=' . $siteUrl . '&status=' . $postCheck;
        $response   = wp_remote_get( $url );
        $result     = wp_remote_retrieve_body( $response );

        if( $apiCheck ) {
            $value = array(
                'username' => $username,
                'api'      => $api,
                'status'   => true,
            );
        } else {
            $value = array(
                'username' => $username,
                'api'      => $api,
                'status'   => false,
            );
        }

        update_option( 'makale_net', $value );
    }

    wp_redirect( get_admin_url() . 'admin.php?page=makale-net_ayarlar' );
    exit;
}

add_action( 'admin_post_settings_change_save', 'settings_change_save_callback' );