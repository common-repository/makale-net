<?php

/**
 * Eklenti etkinleştirildiğinde çalışır.
 */
function makale_net_activation() {
}

register_activation_hook( __FILE__, 'makale_net_activation' );

/**
 * Eklenti pasifleştirildiğinde çalışır.
 */
function makale_net_deactivation() {
}

register_deactivation_hook( __FILE__, 'makale_net_deactivation' );

/**
 * Eklenti silindiğinde çalışır.
 */
function makale_net_delete() {
}

register_uninstall_hook( __FILE__, 'makale_net_delete' );