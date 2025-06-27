<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option('simple_wpp_number');
delete_option('simple_wpp_message');
