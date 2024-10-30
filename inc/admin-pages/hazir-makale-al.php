<?php

function makale_net_hazir_makale_al_display() {
    // Api değişirse ayarları sıfırla
	makale_net_change_status_if_needed();

    $api = $settings = get_option( 'makale_net', true )[ 'api' ];
    $exclude = array( 'page' );
    $url = 'https://www.makale.net/api/hazirmakale_satis_listesi?API-KEY=' . $api;
    foreach( $_GET as $name => $value ) {
        if( in_array( $name, $exclude ) )
            continue;
        $url = $url . '&' . htmlspecialchars( $name ) . '=' . urlencode( $name == 'sayfa' ? $value - 1 : $value );
    }

    $response   = wp_remote_get( $url );
    $result     = wp_remote_retrieve_body( $response );
    $objects    = json_decode( $result );
    $categories = makale_net_categories();
    ?>
	<div class="mn-container">
		<div class="mn-d-flex">
			<h1><?php esc_html_e( 'Hazır Makale Satın Al', 'makale-net' ); ?></h1>
			<div class="ml-auto m-t-10">
				<img class="mn-logo" src="<?php echo esc_url( plugins_url( 'makale-net' ) . '/assets/admin/img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Logo', 'makale-net' ); ?>">
			</div>
		</div>

		<hr class="m-b-15">

		<div class="ml-auto">
            <?php
            if( !$objects->error )
                printf( __( '<h2 class="guncel-bakiye m-b-10">Bakiyeniz: <span>%s</span></h2>', 'makale-net' ), $objects->musteri->bakiye );
            ?>
		</div>

		<div class="mn-d-flex m-b-10 align-items-end">
			<div class="kategori-wrapper">
				<div class="makale-ara-wrapper">
					<form action="">
                        <?php
                        $exclude = array( 'sayfa', 'kategoriID', 'ara' );
                        foreach( $_GET as $name => $value ) {
                            if( in_array( $name, $exclude ) )
                                continue;

                            echo '<input type="hidden" name="' . htmlspecialchars( $name ) . '" value="' . htmlspecialchars( $value ) . '">';
                        }
                        ?>
						<table class="mn-form-table">
							<tbody>
							<tr>
								<th><label for="kategori"><?php esc_html_e( 'Kategori:', 'makale-net' ); ?></label></th>
								<td>
									<select class="mn-select" name="kategoriID" id="kategori">
										<option value="" selected><?php esc_html_e( '- Tüm Kategoriler -', 'makale-net' ); ?></option>
                                        <?php
                                        foreach( $categories as $key => $category ) { ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php selected( isset( $_GET[ 'kategoriID' ] ) ? $_GET[ 'kategoriID' ] : '', $key ); ?>><?php echo esc_html( $category ); ?></option>
                                            <?php
                                        }
                                        ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><label for="ara"><?php esc_html_e( 'Aranacak Kelime:', 'makale-net' ); ?></label></th>
								<td>
									<input type="text" name="ara" id="ara" placeholder="<?php esc_attr_e( 'Kelime Giriniz', 'makale-net' ); ?>" value="<?php echo isset( $_GET[ 'ara' ] ) ? $_GET[ 'ara' ] : ''; ?>">
								</td>
							</tr>
							</tbody>
						</table>
						<button type="submit" class="btn btn-responsive btn-sm btn-info" data-status="true">
							<i class="fa fa-search m-r-5"></i>
							<?php esc_html_e( 'Arama Yap', 'makale-net' ); ?>
						</button>
                        <?php // submit_button( __( 'Arama Yap', 'makale-net' ), 'button-primary', null ); ?>
					</form>
				</div>
			</div>
		</div>

        <?php if( !$objects->error ): ?>
			<div class="table100 ver3 m-b-15">
				<table class="mn-table" data-vertable="ver3">
					<thead>
					<tr class="row100 head">
						<th class="column100 column1" data-column="column1"><?php esc_html_e( 'Başlık', 'makale-net' ); ?></th>
						<th class="column100 column2" data-column="column2"><?php esc_html_e( 'Kategori', 'makale-net' ); ?></th>
						<th class="column100 column3" data-column="column3"><?php esc_html_e( 'Kelime Sayısı', 'makale-net' ); ?></th>
						<th class="column100 column4" data-column="column4"><?php esc_html_e( 'Fiyat', 'makale-net' ); ?></th>
						<th class="column100 column5" data-column="column5"><?php esc_html_e( 'İşlemler', 'makale-net' ); ?></th>
					</tr>
					</thead>
					<tbody>
                    <?php foreach( $objects->hazirMakaleler as $object ) { ?>
						<tr class="row100" data-id="<?php echo esc_attr( $object->ID ); ?>">
							<td class="column100 column1" data-column="column1"><?php echo esc_html( $object->baslik ); ?></td>
							<td class="column100 column2" data-column="column2"><?php echo esc_html( $object->kategori ); ?></td>
							<td class="column100 column3" data-column="column3"><?php echo esc_html( $object->kelimeSayisi ); ?></td>
							<td class="column100 column4 fiyat" data-column="column4"><?php echo esc_html( $object->fiyat ); ?></td>
							<td class="column100 column5" data-column="column5">
								<button class="mn-action-button btn btn-sm btn-info onizle">
									<i class="far fa-eye m-r-5"></i><?php esc_html_e( 'Görüntüle', 'makale-net' ); ?>
								</button>
								<button class="mn-action-button btn btn-sm btn-success satin-al">
									<i class="fas fa-shopping-bag m-r-5"></i><?php esc_html_e( 'Satın Al', 'makale-net' ); ?>
								</button>

								<div class="mn-gizle preview">
                                    <?php echo $object->onizlemeHTML; ?>
								</div>
							</td>
						</tr>
                        <?php
                    }; ?>
					</tbody>
				</table>
			</div>
			<div class="pagination-holder clearfix">
				<div id="mn-pagination" class="pagination" data-count="<?php echo esc_attr( $objects->toplamSayfa + 1 ); ?>"></div>
			</div>
        <?php else: ?>
			<div class="not-found-wrapper">
				<h2><?php esc_html_e( 'Üzgünüz aradığınız içeriği bulamadık :(', 'makale-net' ); ?></h2>
			</div>
        <?php endif; ?>
	</div>

    <?php
    //print_r( get_current_screen() );
}