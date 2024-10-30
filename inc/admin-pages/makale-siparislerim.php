<?php

function makale_net_makale_siparislerim_display() {
    // Api değişirse ayarları sıfırla
    makale_net_change_status_if_needed();

    // Wordpress veritabanınından api anahtarını getir.
    $api = $settings = get_option( 'makale_net', true )[ 'api' ];

    // GET ten gelen değere göre curl isteği yap. Tüm siparişler veya tekil sipariş gösterimi.
    $slug = isset( $_GET[ 'SMID' ] ) && !empty( $_GET[ 'SMID' ] ) ? 'makalesiparisi_makaleler' : 'makalesiparisi_alinan_listesi';

    // Wordpress page parametresini curl istek linkinden hariç tut. Array içerisine yazılanlar curl parametresinden isteğinden hariç tutulur.
    $exclude = array( 'page' );

    // Var sayılan curl istek linki. API dahil.
    $url = 'https://www.makale.net/api/' . $slug . '?API-KEY=' . $api;

    // GET parametersindeki değerleri curl istek linkine ekle. $exclude olanları eklemez.
    foreach( $_GET as $name => $value ) {
        if( in_array( $name, $exclude ) )
            continue;
        $url = $url . '&' . htmlspecialchars( $name ) . '=' . urlencode( $name == 'sayfa' ? $value - 1 : $value );
    }

    $response   = wp_remote_get( $url );
    $result     = wp_remote_retrieve_body( $response );
    $objects    = json_decode( $result );
    ?>
	<div class="mn-container mn-bootstrap">
		<div class="mn-d-flex">
			<h1><?php esc_html_e( 'Makale Siparişlerim', 'makale-net' ); ?></h1>
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

        <?php
        // GET if başlangıcı
        // Teliş sipariş gösterilecek
        // Tekil sipariş için GET isteği varsa ve boş değilse.
        if( isset( $_GET[ 'SMID' ] ) && !empty( $_GET[ 'SMID' ] ) ): ?>
			<!--Ajax için kategoriler ve yazarlar başlangıç-->
            <?php
            // Kategoriler
            $termArgs = array(
                'taxonomy'   => 'category',
                'hide_empty' => 0
            );
            $terms = get_terms( $termArgs );
            $cats = array();
            foreach( $terms as $term ) {
                $cats[ $term->term_id ] = $term->name;
            }

            // Kullanıcılar
            $usersArgs = array(
                'role__in' => array( 'administrator', 'editor', 'author' ),
            );
            $users = get_users( $usersArgs );
            $usersArray = array();
            foreach( $users as $user ) {
                $usersArray[ $user->ID ] = $user->display_name;
            }
            ?>
			<script>
                var cats = <?php echo json_encode( $cats ); ?>;
                var users = <?php echo json_encode( $usersArray ); ?>;
			</script>
			<!--Ajax için kategoriler ve yazarlar bitiş-->

			<a class="btn btn-sm btn-outline-dark m-b-25" href="<?php echo isset( $_SESSION[ 'back' ] ) ? esc_url( $_SESSION[ 'back' ] ) : ''; ?>"><i class="fas fa-long-arrow-alt-left m-r-5"></i><?php esc_html_e( 'Geri Dön', 'makale-net' ); ?></a>

			<div class="mn-row row-eq-height m-b-30">
				<div class="col-md-3">
					<div class="mn-card siparis-info">
						<p>
							<strong><?php esc_html_e( 'Sipariş No:' ); ?></strong><?php echo esc_html( $objects->siparis->siparisNo ); ?>
						</p>
						<p>
							<strong><?php esc_html_e( 'Proje Adı:' ); ?></strong><?php echo esc_html( $objects->siparis->projeAdi ); ?>
						</p>
						<p>
							<strong><?php esc_html_e( 'Makale Seviyesi:' ); ?></strong><?php echo esc_html( $objects->siparis->makaleSeviyesi ); ?>
						</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="mn-card siparis-info">
						<p>
							<strong><?php esc_html_e( 'Makale Türü:' ); ?></strong><?php echo esc_html( $objects->siparis->makaleTuru ); ?>
						</p>
						<p>
							<strong><?php esc_html_e( 'Makale Kategorisi:' ); ?></strong><?php echo esc_html( $objects->siparis->makaleKategorisi ); ?>
						</p>
						<p>
							<strong><?php esc_html_e( 'Makale Anlatımı:' ); ?></strong><?php echo esc_html( $objects->siparis->makaleAnlatimi ); ?>
						</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="mn-card siparis-info">
						<p>
							<strong><?php esc_html_e( 'Toplam Kelime:' ); ?></strong><?php echo esc_html( $objects->siparis->toplamKelime ); ?>
						</p>
						<p>
							<strong><?php esc_html_e( 'Toplam Makale:' ); ?></strong><?php echo esc_html( $objects->siparis->toplamMakale ); ?>
						</p>
						<p>
							<strong><?php esc_html_e( 'Oluşturulma Tarihi:' ); ?></strong><?php echo esc_html( $objects->siparis->olusumTarihi ); ?>
						</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="mn-card siparis-info">
						<p>
							<strong><?php esc_html_e( 'Teslim Tarihi:' ); ?></strong><?php echo esc_html( $objects->siparis->teslimTarihi ); ?>
						</p>
						<p>
							<strong><?php esc_html_e( 'Proje Durumu:' ); ?></strong><?php echo esc_html( $objects->siparis->kalanSure->text ); ?>
						</p>
					</div>
				</div>
			</div>

        <?php
        // Object kontrol başlangıcı
        if( !$objects->error ): ?>
			<div class="table100 ver3 m-b-15">
				<span class="secilenleri"><?php esc_html_e( 'Seçilenleri:', 'makale-net' ); ?></span>
				<button class="btn btn-responsive btn-sm btn-success bulkPublish" data-type="makalesiparisi" data-status="publish"><?php esc_html_e( 'Hemen Yayımla', 'makale-net' ); ?></button>
				<button class="btn btn-responsive btn-sm btn-secondary bulkPublish" data-type="makalesiparisi" data-status="draft"><?php esc_html_e( 'Taslak Olarak Kaydet', 'makale-net' ); ?></button>
				<button class="btn btn-responsive btn-sm btn-outline-success bulkStatus" data-type="makalesiparisi" data-status="true"><?php esc_html_e( 'Kullanıldı Yap', 'makale-net' ); ?></button>
				<button class="btn btn-responsive btn-sm btn-outline-danger bulkStatus" data-type="makalesiparisi" data-status="false"><?php esc_html_e( 'Kullanılmadı Yap', 'makale-net' ); ?></button>
				<table class="mn-table m-t-5" data-vertable="ver3">
					<thead>
					<tr class="row100 head" >
						<th class="column100 w-1" data-column="column1">
							<input type="checkbox" class="mn-select-all" id="selectAll">
						</th>
						<th class="column100 column2 w-1" data-column="column2"><?php esc_html_e( 'Kullanım', 'makale-net' ); ?></th>
						<th class="column100 column3" data-column="column3"><?php esc_html_e( 'Başlık', 'makale-net' ); ?></th>
						<th class="column100 column3" data-column="column3"><?php esc_html_e( 'Kelime Sayısı', 'makale-net' ); ?></th>
						<th class="column100 column5" data-column="column5"><?php esc_html_e( 'İşlemler', 'makale-net' ); ?></th>
					</tr>
					</thead>
					<tbody>
                    <?php
                    foreach( $objects->makaleler as $object ) {
                        // Makale durumunu kontrol et
                        $durum = $object->durum;
                        $disabled = false;

                        if( $durum != '3' )
                            $disabled = true;
                        ?>
						<tr class="row100" data-id="<?php echo esc_attr( $object->MAKID ); ?>">
							<td class="column100" data-column="column1">
								<input class="makaleSec" type="checkbox" value="<?php echo esc_attr( $object->MAKID ); ?>"<?php echo $disabled ? esc_attr( ' disabled' ) : ''; ?>>
							</td>
							<td class="column100 kullanim" data-column="column2">
								<input class="tgl tgl-light durum-degistir" data-type="makalesiparisi" id="<?php echo esc_attr( $object->MAKID ); ?>" type="checkbox"<?php echo $object->kullanim ? ' checked' : ''; ?><?php echo $disabled ? esc_attr( ' disabled' ) : ''; ?>>
								<label class="tgl-btn" for="<?php echo esc_attr( $object->MAKID ); ?>"></label>
							</td>
							<td class="column100 column1 onizle-baslik" data-column="column3"><?php echo esc_html( $object->baslik ); ?></td>
							<td class="column100 text-center" data-column="column3"><?php echo esc_html( $object->kelimeSayisi ); ?></td>
							<td class="column100 column5<?php echo $disabled ? esc_attr( ' tippy' ) : ''; ?>" data-column="column5"<?php echo $disabled ? ' data-tippy-content="' . esc_attr__( 'Makaleniz henüz hazırlanma aşamasındadır.', 'makale-net' ) . '"' : ''; ?>>
								<button class="mn-action-button btn btn-sm btn-info alinan_hazir_onizle"<?php echo $disabled ? esc_attr( ' disabled' ) : ''; ?> data-type="makalesiparisi">
									<i class="far fa-eye m-r-5"></i><?php esc_html_e( 'Görüntüle', 'makale-net' ); ?>
								</button>
								<div class="mn-d-inline-block<?php echo $object->kullanim ? ' tippy' : ''; ?>"<?php echo $object->kullanim ? ' data-tippy-content="' . esc_attr( 'Bu makale kullanıldı olarak işaretlenmiş. Tekrar yayımlayabilmek için "Kullanılmadı" yapmanız gerekmektedir.', 'makale-net' ) . '"' : ''; ?>>
									<button class="mn-action-button btn btn-sm btn-success alinan_hazir_satin-al"<?php echo $object->kullanim || $disabled ? esc_attr( ' disabled' ) : ''; ?> data-type="makalesiparisi">
										<i class="far fa-paper-plane m-r-5"></i></i><?php esc_html_e( 'Yayımla', 'makale-net' ); ?>
									</button>
								</div>

								<div class="mn-gizle preview">
                                    <?php echo $object->icerik_html; ?>
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
        <?php
        // Tekil Sipariş Object kontrol else
        else: ?>
			<div class="not-found-wrapper">
				<h2><?php esc_html_e( 'Üzgünüz aradığınız içeriği bulamadık :(', 'makale-net' ); ?></h2>
			</div>
        <?php
        // Tekil Sipariş Object kontrol bitiş
        endif; ?>
        <?php
        // GET kontrol else
        else:

        // Geri butonu için session oluştur
        $back = ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $_SESSION[ 'back' ] = $back;
        ?>
			<div class="mn-d-flex m-b-10 align-items-end">
				<div class="kategori-wrapper">
					<div class="makale-ara-wrapper">
						<form action="">
                            <?php
                            $exclude = array( 'sayfa', 'kategoriID', 'ara', 'arsiv' );
                            foreach( $_GET as $name => $value ) {
                                if( in_array( $name, $exclude ) )
                                    continue;

                                echo '<input type="hidden" name="' . htmlspecialchars( $name ) . '" value="' . htmlspecialchars( $value ) . '">';
                            }
                            ?>
							<table class="mn-form-table">
								<tbody>
								<tr>
									<th><label for="ara"><?php esc_html_e( 'Aranacak Kelime:', 'makale-net' ); ?></label>
									</th>
									<td>
										<input type="text" name="ara" id="ara" placeholder="<?php esc_attr_e( 'Sipariş no, proje adı...', 'makale-net' ); ?>" value="<?php echo isset( $_GET[ 'ara' ] ) ? $_GET[ 'ara' ] : ''; ?>">
									</td>
								</tr>
								<tr>
									<th><label for="arsiv"><?php esc_html_e( 'Arşiv Durumu:', 'makale-net' ); ?></label>
									</th>
									<td>
										<select name="arsiv" id="arsiv" class="mn-select" data-minimum-results-for-search="Infinity">
											<option value="" selected><?php esc_html_e( '- Tüm Siparişler -', 'makale-net' ); ?></option>
											<option value="false" <?php selected( isset( $_GET[ 'arsiv' ] ) ? $_GET[ 'arsiv' ] : '', 'false' ); ?>><?php esc_html_e( 'Aktif Siparişler' ); ?></option>
											<option value="true" <?php selected( isset( $_GET[ 'arsiv' ] ) ? $_GET[ 'arsiv' ] : '', 'true' ); ?>><?php esc_html_e( 'Arşivlenen Siparişler' ); ?></option>
										</select>
									</td>
								</tr>
								</tbody>
							</table>
							<button type="submit" class="btn btn-responsive btn-sm btn-info" data-status="true">
								<i class="fa fa-search m-r-5"></i>
								<?php esc_html_e( 'Arama Yap', 'makale-net' ); ?>
							</button>
							<?php // submit_button( __( 'Arama Yap', 'makale-net' ), 'btn btn-responsive btn-sm btn-info', null ); ?>
						</form>
					</div>
				</div>
			</div>

		<hr class="m-b-15 m-t-15">

            <?php // Siparişler Object kontrol başlangıç
        if( !$objects->error ): ?>
			<div class="table100 ver3 m-b-15">
				<div class="m-t-10 m-b-5">
					<span class="secilenleri"><?php esc_html_e( 'Seçilenleri:', 'makale-net' ); ?></span>
					<button class="btn btn-responsive btn-sm btn-success bulkArsiv" data-status="true"><?php esc_html_e( 'Arşive Taşı', 'makale-net' ); ?></button>
					<button class="btn btn-responsive btn-sm btn-secondary bulkArsiv" data-status="false"><?php esc_html_e( 'Arşivden Çıkar', 'makale-net' ); ?></button>
				</div>
				<table class="mn-table" data-vertable="ver3">
					<thead>
					<tr class="row100 head">
						<th class="column100 column1 w-1" data-column="column1">
							<input type="checkbox" class="mn-select-all" id="selectAll">
						</th>
						<th class="column100 column2 w-1" data-column="column2"><?php esc_html_e( 'Arşiv', 'makale-net' ); ?></th>
						<th class="column100 column3" data-column="column3"><?php esc_html_e( 'Sipariş No', 'makale-net' ); ?></th>
						<th class="column100 column4" data-column="column4"><?php esc_html_e( 'Proje Adı', 'makale-net' ); ?></th>
						<th class="column100 column5" data-column="column5"><?php esc_html_e( 'Oluşturulma Tarihi', 'makale-net' ); ?></th>
						<th class="column100 column6 w-1" data-column="column6"><?php esc_html_e( 'Adet', 'makale-net' ); ?></th>
						<th class="column100 column7" data-column="column7"><?php esc_html_e( 'Durum', 'makale-net' ); ?></th>
						<th class="column100 column8" data-column="column8"><?php esc_html_e( 'Kalan Süre', 'makale-net' ); ?></th>
						<th class="column100 column9" data-column="column9"><?php esc_html_e( 'Express', 'makale-net' ); ?></th>
						<th class="column100 column10 w-1" data-column="column10"><?php esc_html_e( 'İncele', 'makale-net' ); ?></th>
					</tr>
					</thead>
					<tbody>
                    <?php foreach( $objects->siparisler as $object ) {
                        // Ödemeyi kontrol et.
                        $odeme = $object->odeme;

                        $disabled = false;
                        if( !$odeme )
                            $disabled = true;
                        ?>
						<tr class="row100" data-id="<?php echo esc_attr( $object->SMID ); ?>">
							<td class="column100" data-column="column1">
								<input class="makaleSec" type="checkbox" value="<?php echo esc_attr( $object->SMID ); ?>">
							</td>
							<td class="column100 kullanim" data-column="column2">
								<input class="tgl tgl-light arsiv-degistir" id="<?php echo esc_attr( $object->SMID ); ?>" type="checkbox"<?php echo $object->arsiv ? ' checked' : ''; ?>>
								<label class="tgl-btn" for="<?php echo esc_attr( $object->SMID ); ?>"></label>
							</td>
							<td class="column100 column3" data-column="column3"><?php echo esc_html( $object->siparisNo ); ?></td>
							<td class="column100 column4" data-column="column4"><?php echo esc_html( $object->projeAdi ); ?></td>
							<td class="column100 column5" data-column="column5"><?php echo esc_html( $object->olusumTarihi ); ?></td>
							<td class="column100 column6" data-column="column6"><?php echo esc_html( $object->makaleAdet ); ?></td>
							<td class="column100 column7" data-column="column7"><?php echo esc_html( '%' . $object->durumYuzde ); ?></td>
							<td class="column100 column8" data-column="column8"><?php echo esc_html( $object->kalanSure->text ); ?></td>
							<td class="column100 column9" data-column="column9"><?php echo $object->express ? '<i class="fas fa-check mn-yesil-text"></i>' : '<i class="fas fa-times mn-kirmizi-text"></i>'; ?></td>
							<td class="column100 column10<?php echo !$odeme ? ' tippy' : ''; ?>"<?php echo !$odeme ? ' data-tippy-content="' . esc_attr__( 'Siparişiniz ödeme bekleniyor aşamasındadır.' ) . '"' : ''; ?> data-column="column10">
								<a href="<?php echo $odeme ? esc_url( admin_url( 'admin.php?page=makale-net_makale_siparislerim&SMID=' . $object->SMID ) ) : 'javascript:void(0)'; ?>" class="btn btn-sm btn-outline-success<?php echo $disabled ? ' a-disabled' : ''; ?>"><i class="far fa-eye"></i></a>
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
        <?php // Siparişler object kontrol else
        else: ?>
			<div class="not-found-wrapper">
				<h2><?php esc_html_e( 'Üzgünüz aradığınız içeriği bulamadık :(', 'makale-net' ); ?></h2>
			</div>
        <?php
            // Siparişler object kontrol bitiş
        endif;

            // GET kontrol bitiş
        endif; ?>
	</div>
    <?php
    //print_r( get_current_screen() );
}