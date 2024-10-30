<?php

function makale_net_siparis_ver_display() {
    // Api değişirse ayarları sıfırla
    makale_net_change_status_if_needed();

    $settings = get_option( 'makale_net', true );
    $api      = $settings[ 'api' ];
    $url      = 'https://www.makale.net/api/makalesiparisi_form_data?API-KEY=' . $api;
    $response = wp_remote_get( $url );
    $result   = wp_remote_retrieve_body( $response );
    $objects  = json_decode( $result );
    ?>
	<div class="mn-container">
		<div class="mn-d-flex">
			<h1><?php esc_html_e( 'Sipariş Ver', 'makale-net' ); ?></h1>
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
		
		<form class="siparisForm" method="POST">
			<input type="hidden" name="page" value="makale-net_siparis-ver">
			<table class="mn-form-table extend">
				<tbody>
				<tr>
					<th><label for="siparisTuru"><?php esc_html_e( 'Express Sipariş:', 'makale-net' ); ?></label></th>
					<td>
						<select name="express" id="siparisTuru" class="mn-select" data-minimum-results-for-search="Infinity">
							<option value="0"><?php esc_html_e( 'Hayır, istemiyorum', 'makale-net' ); ?></option>
							<option value="1"><?php esc_html_e( 'Evet, istiyorum', 'makale-net' ); ?></option>
						</select>
						<div class="mn-alert-warning"></div>
						<div class="mn-inline-info siparisTuru">
							<h3><?php esc_html_e( 'Express Makale Nedir?', 'makale-net' ); ?></h3>
							<p><?php esc_html_e( 'Express makale, üretim hızında uzmanlığı, redaksiyonu ve teknolojiyi bir araya getirerek "En iyiyi, en hızlı sürede" mottosu ile sunulan bir hizmettir. Express makale siparişleri 1-24 saat içerisinde teslim edilmektedir. Express Makale stoğu hafta içi her gün 00:00\'da sıfırlanmaktadır.', 'makale-net' ); ?></p>
							<p class="mn-express-stok">
								<span><?php esc_html_e( 'Bugün Kalan Stok:' ); ?></span><?php echo esc_html( $objects->formData->expressStok ); ?>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="proje"><?php esc_html_e( 'Proje Adı:' ); ?></label></th>
					<td>
						<input type="text" name="projeAdi" id="proje" class="form" placeholder="<?php esc_attr_e( 'Proje Adı Giriniz', 'makale-net' ); ?>">
						<div class="mn-alert-warning"></div>
					</td>
				</tr>
				<tr>
					<th><label for="seviye"><?php esc_html_e( 'Makale Seviyesi:', 'makale-net' ); ?></label></th>
					<td>
						<select name="MSID" id="seviye" class="mn-select" data-minimum-results-for-search="Infinity">
                            <?php foreach( $objects->formData->seviyeler as $seviye ) { ?>
								<option value="<?php echo esc_attr( $seviye->MSID ); ?>"><?php echo esc_html( $seviye->seviye ); ?></option>
                                <?php
                            }; ?>
						</select>
						<div class="mn-alert-warning"></div>
					</td>
				</tr>
				<tr>
					<th><label for="tur"><?php esc_html_e( 'Makale Türü:', 'makale-net' ); ?></label></th>
					<td>
						<select name="MTUID" id="tur" class="mn-select" data-minimum-results-for-search="Infinity">
                            <?php foreach( $objects->formData->turler as $tur ) { ?>
								<option value="<?php echo esc_attr( $tur->MTUID ); ?>"><?php echo esc_html( $tur->tur ); ?></option>
                                <?php
                            }; ?>
						</select>
						<div class="mn-alert-warning"></div>
					</td>
				</tr>
				<tr>
					<th><label for="kategori"><?php esc_html_e( 'Kategori', 'makale-net' ); ?></label></th>
					<td>
						<select class="mn-select" name="MKID" id="kategori">
							<option value="" selected disabled><?php esc_html_e( 'Kategori Seçiniz', 'makale-net' ); ?></option>
                            <?php foreach( $objects->formData->kategoriler as $kategori ) { ?>
								<option value="<?php echo esc_attr( $kategori->MKID ); ?>"><?php echo esc_html( $kategori->kategori ); ?></option>
                                <?php
                            }; ?>
						</select>
						<div class="mn-alert-warning"></div>
					</td>
				</tr>
				<tr>
					<th><label for="anlatim"><?php esc_html_e( 'Anlatım Türü:', 'makale-net' ); ?></label></th>
					<td>
						<select name="MAID" id="anlatim" class="mn-select" data-minimum-results-for-search="Infinity">
                            <?php foreach( $objects->formData->anlatimlar as $anlatim ) { ?>
								<option value="<?php echo esc_attr( $anlatim->MAID ); ?>"><?php echo esc_html( $anlatim->anlatim ); ?></option>
                                <?php
                            }; ?>
						</select>
						<div class="mn-alert-warning"></div>
					</td>
				</tr>
				<tr>
					<th><label for="not"><?php esc_html_e( 'Sipariş Notu:', 'makale-net' ); ?></label></th>
					<td>
						<textarea name="siparisNotu" id="not" cols="100" rows="6" placeholder="<?php esc_attr_e( 'Makale yazımı için dikkat etmemizi istediğiniz noktaları ve ekstra isteklerinizi detaylı olarak belirtiniz.', 'makale-net' ); ?>"></textarea>
						<div class="mn-alert-warning"></div>
					</td>
				</tr>
				</tbody>
			</table>

			<div class="table100 not-center ver3 m-t-15">
				<table class="mn-table repeater" data-vertable="ver3">
					<thead>
					<tr class="row100 head">
						<th class="column100 column1" data-column="column1">
							<div class="mn-d-flex align-items-center">
								<i data-tippy-content="<?php esc_attr_e( 'Makalenizin başlığını yazınız', 'makale-net' ); ?>" class="tippy m-r-5 fas fa-info-circle"></i><?php esc_html_e( 'Başlık', 'makale-net' ); ?>
								<button type="button" class="bulk-baslik ml-auto btn-basic tippy" data-tippy-content="<?php esc_attr_e( 'Toplu olarak aynı başlığı kullanmanızı sağlar.', 'makale-net' ); ?>">
									<i class="far fa-clone text-white"></i></button>
							</div>
						</th>
						<th class="column100 column2" data-column="column2">
							<div class="mn-d-flex align-items-center">
                                <?php esc_html_e( 'Kelime Sayısı', 'makale-net' ); ?>
								<button type="button" class="bulk-kelime-sayisi ml-auto btn-basic tippy" data-tippy-content="<?php esc_attr_e( 'Toplu olarak aynı kelime sayısını seçmenizi sağlar.', 'makale-net' ); ?>">
									<i class="far fa-clone text-white"></i></button>
							</div>
						</th>
						<th class="column100 column3" data-column="column3">
							<div class="mn-d-flex align-items-center">
								<i data-tippy-content="<?php esc_attr_e( 'Anahtar kelimelerinizi aralarına virgül (,) koyarak yazınız.', 'makale-net' ); ?>" class="tippy m-r-5 fas fa-info-circle"></i><?php esc_html_e( 'Anahtar Kelime(ler)', 'makale-net' ); ?>
								<button type="button" class="bulk-anahtar-kelime ml-auto btn-basic tippy" data-tippy-content="<?php esc_attr_e( 'Toplu olarak aynı anahtar kelimeleri kullanmanızı sağlar.', 'makale-net' ); ?>">
									<i class="far fa-clone text-white"></i></button>
							</div>
						</th>
						<th class="column100 column4" data-column="column4">
							<div class="mn-d-flex align-items-center">
								<i data-tippy-content="<?php esc_attr_e( 'Anahtar kelimelerinizin makale içerisinde kaç defa geçeceğini seçiniz.', 'makale-net' ); ?>" class="tippy m-r-5 fas fa-info-circle"></i><?php esc_html_e( 'Kullanım', 'makale-net' ); ?>
								<button type="button" class="bulk-kullanim ml-auto btn-basic tippy" data-tippy-content="<?php esc_attr_e( 'Toplu olarak aynı kullanımı seçmenizi sağlar.', 'makale-net' ); ?>">
									<i class="far fa-clone text-white"></i></button>
							</div>
						</th>
						<th class="column100 column5 text-center" data-column="column5">
                            <?php esc_html_e( 'Ekle/Sil', 'makale-net' ); ?>
						</th>
					</tr>
					</thead>
					<tbody class="repeater-container" data-repeater-list="makaleler">
					<tr class="row100" data-repeater-item>
						<td class="column100" data-column="column1">
							<input class="w-100" type="text" name="baslik" id="baslik" placeholder="<?php esc_attr_e( 'Boş bırakılırsa yazar belirler.', 'makale-net' ); ?>">
						</td>
						<td class="column100" data-column="column2">
							<select name="kelimeSayisi" id="kelime">
                                <?php foreach( $objects->formData->kelimeSayilari as $key => $ks ) { ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, 200 ); ?>><?php echo esc_html( $ks ); ?></option>
                                    <?php
                                }; ?>
							</select>
						</td>
						<td class="column100" data-column="column3">
							<input name="linkler" id="linkler" type="hidden">
							<div class="mn-d-flex align-items-center justify-content-start mn-button-group">
								<input type="text" class="tag-link-input" id="anahtarKelime" name="anahtarKelime" placeholder="<?php esc_attr_e( 'kelime 1, kelime 2, kelime 3' ); ?>">
								<button type="button" class="btn btn-sm btn-warning tag-link tippy" data-tippy-content="<?php esc_attr_e( 'Anahtar kelimeleri linklendirin' ); ?>">
									<i class="fas fa-link"></i></button>
							</div>
						</td>
						<td class="column100" data-column="column4">
							<select name="kullanim" id="kullanim">
                                <?php foreach( $objects->formData->kullanimlar as $key => $kullanim ) { ?>
									<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $kullanim ); ?></option>
                                    <?php
                                }; ?>
							</select>
						</td>
						<td class="column100 text-center" data-column="column5">
							<button type="button" class="btn btn-sm btn-danger fake-delete">
								<i class="far fa-trash-alt"></i></button>
							<button type="button" class="btn btn-sm btn-danger" data-repeater-delete>
								<i class="far fa-trash-alt"></i></button>
						</td>
					</tr>
					</tbody>
					<tfoot>
					<tr class="row100">
						<td class="column100"></td>
						<td class="column100"></td>
						<td class="column100"></td>
						<td class="column100"></td>
						<td class="column100 text-center">
							<button type="button" class="btn btn-sm btn-success" data-repeater-create>
								<i class="fas fa-plus"></i></button>
						</td>
					</tr>
					</tfoot>
				</table>
			</div>

            <?php // submit_button( 'Gönder', 'button-primary' ); ?>
			<button type="submit" class="btn btn-responsive btn-sm btn-success m-t-10" data-status="true"><?php esc_html_e( 'Devam Et', 'makale-net' ); ?> <i class="fa fa-arrow-right"></i></button>
		</form>

	</div>
    <?php
    //print_r( get_current_screen() );
}