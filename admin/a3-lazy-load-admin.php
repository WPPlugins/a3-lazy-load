<?php
update_option('a3rev_lazy_load_plugin', 'a3_lazy_load');

function a3_lazy_load_activated(){
	update_option('a3_lazy_load_version', '1.0.2');

	// Set Settings Default from Admin Init
	global $a3_lazy_load_admin_init;
	$a3_lazy_load_admin_init->set_default_settings();

	update_option('a3_lazy_load_just_installed', true);
}

/**
 * Load languages file
 */
function a3_lazy_load_init() {
	global $a3_lazy_load_global_settings;

	if ( get_option( 'a3_lazy_load_just_installed' ) ) {
		delete_option( 'a3_lazy_load_just_installed' );
		wp_redirect( admin_url( 'admin.php?page=a3-lazy-load', 'relative' ) );
		exit;
	}

	load_plugin_textdomain( 'a3_lazy_load', false, A3_LAZY_LOAD_FOLDER.'/languages' );

	update_option('a3_lazy_load_version', '1.0.2');
}

global $a3_lazy_load_admin_init;
$a3_lazy_load_admin_init->init();

// Add language
add_action('init', 'a3_lazy_load_init');

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( 'A3_Lazy_Load_Hook_Filter', 'a3_wp_admin' ) );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array( 'A3_Lazy_Load_Hook_Filter', 'plugin_extra_links'), 10, 2 );

// Add admin sidebar menu css
add_action( 'admin_enqueue_scripts', array( 'A3_Lazy_Load_Hook_Filter', 'admin_sidebar_menu_css' ) );
?>