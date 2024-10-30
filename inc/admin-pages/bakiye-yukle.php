<?php

function makale_net_bakiye_yukle_display() {
    makale_net_change_status_if_needed(); ?>
	<div class="mn-container">
		<div class="mn-d-flex">
			<h1><?php esc_html_e( 'Bakiye Yükle', 'makale-net' ); ?></h1>
			<div class="ml-auto m-t-10">
				<img class="mn-logo" src="<?php echo esc_url( plugins_url( 'makale-net' ) . '/assets/admin/img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Logo', 'makale-net' ); ?>">
			</div>
		</div>
	</div>
    <?php
    
}