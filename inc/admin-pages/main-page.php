<?php

function makale_net_main_page_display() { ?>
	<div class="mn-bootstrap mn-container">
		<div class="mn-d-flex">
			<h1><?php esc_html_e( 'Makale.net', 'makale-net' ); ?></h1>
			<div class="ml-auto m-t-10">
				<img class="mn-logo" src="<?php echo esc_url( plugins_url( 'makale-net' ) . '/assets/admin/img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Logo', 'makale-net' ); ?>">
			</div>
		</div>

		<hr class="m-b-15">

        <?php if( is_makale_net_activated() ): ?>
            
            <!--Burası apisi doğrulanmış üyeler içindir.-->

        <?php endif; ?>

	</div>
    <?php
}