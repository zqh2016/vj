<?php
/*
Plugin Name: Content links
Description: Content links plugin create linking between content texts of your website
Version: 1.1.3
Text Domain: content-links 
Domain Path: /languages
*/

define('LGP_BASE_DIR', dirname(__FILE__) . '/');

include LGP_BASE_DIR . 'assets/core.php';
include LGP_BASE_DIR . 'assets/api.php';
// session start
if (@session_id() == '') {
    @session_start();
}

lgp_core::initialize();

register_activation_hook( __FILE__, array('lgp_core', 'install' ));
register_deactivation_hook( __FILE__, array('lgp_core', 'deactivate' ));
register_uninstall_hook( __FILE__, array( 'lgp_core', 'uninstall' ) );