<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Eklenti verilerini WP_Options tablosundan sil
delete_option('makale_net');