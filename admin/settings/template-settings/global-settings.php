<?php
/* "Copyright 2012 a3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php
/*-----------------------------------------------------------------------------------
a3 LazyLoad General Settings

TABLE OF CONTENTS

- var parent_tab
- var subtab_data
- var option_name
- var form_key
- var position
- var form_fields
- var form_messages

- __construct()
- subtab_init()
- set_default_settings()
- get_settings()
- subtab_data()
- add_subtab()
- settings_form()
- init_form_fields()

-----------------------------------------------------------------------------------*/

class A3_Lazy_Load_Global_Settings extends A3_Lazy_Load_Admin_UI
{

	/**
	 * @var string
	 */
	private $parent_tab = 'a3-lazy-load';

	/**
	 * @var array
	 */
	private $subtab_data;

	/**
	 * @var string
	 * You must change to correct option name that you are working
	 */
	public $option_name = 'a3_lazy_load_global_settings';

	/**
	 * @var string
	 * You must change to correct form key that you are working
	 */
	public $form_key = 'a3_lazy_load_global_settings';

	/**
	 * @var string
	 * You can change the order show of this sub tab in list sub tabs
	 */
	private $position = 1;

	/**
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * @var array
	 */
	public $form_messages = array();

	/*-----------------------------------------------------------------------------------*/
	/* __construct() */
	/* Settings Constructor */
	/*-----------------------------------------------------------------------------------*/
	public function __construct() {
		$this->init_form_fields();
		$this->subtab_init();

		$this->form_messages = array(
				'success_message'	=> __( 'Settings successfully saved.', 'a3_lazy_load' ),
				'error_message'		=> __( 'Error: Settings can not save.', 'a3_lazy_load' ),
				'reset_message'		=> __( 'Settings successfully reseted.', 'a3_lazy_load' ),
			);

		add_action( $this->plugin_name . '-' . $this->form_key . '_settings_end', array( $this, 'include_script' ) );

		add_action( $this->plugin_name . '_set_default_settings' , array( $this, 'set_default_settings' ) );

		add_action( $this->plugin_name . '_get_all_settings' , array( $this, 'get_settings' ) );

	}

	/*-----------------------------------------------------------------------------------*/
	/* subtab_init() */
	/* Sub Tab Init */
	/*-----------------------------------------------------------------------------------*/
	public function subtab_init() {

		add_filter( $this->plugin_name . '-' . $this->parent_tab . '_settings_subtabs_array', array( $this, 'add_subtab' ), $this->position );

	}

	/*-----------------------------------------------------------------------------------*/
	/* set_default_settings()
	/* Set default settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function set_default_settings() {
		global $a3_lazy_load_admin_interface;

		$a3_lazy_load_admin_interface->reset_settings( $this->form_fields, $this->option_name, false );
	}

	/*-----------------------------------------------------------------------------------*/
	/* get_settings()
	/* Get settings with function called from Admin Interface */
	/*-----------------------------------------------------------------------------------*/
	public function get_settings() {
		global $a3_lazy_load_admin_interface;

		$a3_lazy_load_admin_interface->get_settings( $this->form_fields, $this->option_name );
	}

	/**
	 * subtab_data()
	 * Get SubTab Data
	 * =============================================
	 * array (
	 *		'name'				=> 'my_subtab_name'				: (required) Enter your subtab name that you want to set for this subtab
	 *		'label'				=> 'My SubTab Name'				: (required) Enter the subtab label
	 * 		'callback_function'	=> 'my_callback_function'		: (required) The callback function is called to show content of this subtab
	 * )
	 *
	 */
	public function subtab_data() {

		$subtab_data = array(
			'name'				=> 'global-settings',
			'label'				=> __( 'Global Settings', 'a3_lazy_load' ),
			'callback_function'	=> 'a3_lazy_load_global_settings_form',
		);

		if ( $this->subtab_data ) return $this->subtab_data;
		return $this->subtab_data = $subtab_data;

	}

	/*-----------------------------------------------------------------------------------*/
	/* add_subtab() */
	/* Add Subtab to Admin Init
	/*-----------------------------------------------------------------------------------*/
	public function add_subtab( $subtabs_array ) {

		if ( ! is_array( $subtabs_array ) ) $subtabs_array = array();
		$subtabs_array[] = $this->subtab_data();

		return $subtabs_array;
	}

	/*-----------------------------------------------------------------------------------*/
	/* settings_form() */
	/* Call the form from Admin Interface
	/*-----------------------------------------------------------------------------------*/
	public function settings_form() {
		global $a3_lazy_load_admin_interface;

		$output = '';
		$output .= $a3_lazy_load_admin_interface->admin_forms( $this->form_fields, $this->form_key, $this->option_name, $this->form_messages );

		return $output;
	}

	/*-----------------------------------------------------------------------------------*/
	/* init_form_fields() */
	/* Init all fields of this form */
	/*-----------------------------------------------------------------------------------*/
	public function init_form_fields() {

  		// Define settings
     	$this->form_fields = apply_filters( $this->option_name . '_settings_fields', array(
     		array(
                'type' 		=> 'heading',
           	),

           	array(
				'name' 		=> __( 'Enable Lazy Load', 'a3_lazy_load' ),
                'id' 		=> 'a3l_apply_to_loadimages',
                'class'		=> 'a3l_apply_to_loadimages',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_lazy_load' ),
			),

			array(
				'name'		=> __( 'Apply For Objects', 'a3_lazy_load' ),
                'type' 		=> 'heading',
                'class'		=> 'a3l_apply_to_loadimages_container'
           	),

			array(
				'name' 		=> __( 'The Content', 'a3_lazy_load' ),
                'id' 		=> 'a3l_apply_to_content',
				'type' 		=> 'onoff_checkbox',
				'default'	=> false,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_lazy_load' ),
			),

			array(
				'name' 		=> __( 'Widgets', 'a3_lazy_load' ),
                'id' 		=> 'a3l_apply_to_textwidget',
				'type' 		=> 'onoff_checkbox',
				'default'	=> false,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_lazy_load' ),
			),

			array(
				'name' 		=> __( 'Post Thumbnails', 'a3_lazy_load' ),
                'id' 		=> 'a3l_apply_to_postthumbnails',
				'type' 		=> 'onoff_checkbox',
				'default'	=> false,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_lazy_load' ),
			),

			array(
				'name' 		=> __( 'Gravatars', 'a3_lazy_load' ),
                'id' 		=> 'a3l_apply_to_gravatars',
				'type' 		=> 'onoff_checkbox',
				'default'	=> false,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_lazy_load' ),
			),

			array(
				'name' 		=> __( 'Theme Loader Function', 'a3_lazy_load' ),
				'id' 		=> 'a3l_theme_loader',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'wp_footer',
				'checked_value'		=> 'wp_head',
				'unchecked_value'	=> 'wp_footer',
				'checked_label'		=> __( 'HEADER', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'FOOTER', 'a3_lazy_load' ),
			),

			array(
				'name' => __( 'Skip Images Classes', 'a3_lazy_load' ),
				'id' 		=> 'a3l_skip_image_with_class',
				'desc' 		=>  __('Comma separated. Example: "no-lazy, lazy-ignore, image-235"', 'a3_lazy_load'),
				'type' 		=> 'text',
				'default'	=> ""
			),

			array(
				'name' 		=> __( 'Disable On WPTouch', 'a3_lazy_load' ),
				'desc' 		=>  __("Disables a3 Lazy Load when the WPTouch mobile theme is used", 'a3_lazy_load'),
                'id' 		=> 'a3l_load_disable_on_wptouch',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_lazy_load' ),
			),

			array(
				'name' 		=> __( 'Disable On MobilePress', 'a3_lazy_load' ),
				'desc' 		=>  __("Disables a3 Lazy Load when the MobilePress mobile theme is used", 'a3_lazy_load'),
                'id' 		=> 'a3l_load_disable_on_mobilepress',
				'type' 		=> 'onoff_checkbox',
				'default'	=> true,
				'checked_value'		=> true,
				'unchecked_value'	=> false,
				'checked_label'		=> __( 'ON', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'OFF', 'a3_lazy_load' ),
			),

			array(
				'name' 		=> __( 'Effect & Style', 'a3_lazy_load' ),
				'id' 		=> "a3l_settings_style",
				'class'		=> 'a3l_apply_to_loadimages_container',
                'type' 		=> 'heading',
           	),

			array(
				'name' 		=> __( 'Effect', 'a3_lazy_load' ),
				'id' 		=> 'a3l_effect',
				'type' 		=> 'switcher_checkbox',
				'default'	=> 'fadein',
				'checked_value'		=> 'fadein',
				'unchecked_value'	=> 'spinner',
				'checked_label'		=> __( 'FADEIN', 'a3_lazy_load' ),
				'unchecked_label' 	=> __( 'SPINNER', 'a3_lazy_load' ),
			),

			array(
				'name' 		=> __( 'Background Blank Image Colour', 'a3_lazy_load' ),
				'id' 		=> 'a3l_effect_background',
				'type' 		=> 'color',
				'default'	=> '#ffffff'
			),

     	));
	}

	public function include_script() {
?>
<script>
(function($) {
	$(document).ready(function() {

		if ( $("input.a3l_apply_to_loadimages:checked").val() == '1') {
			$(".a3l_apply_to_loadimages_container").css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
		} else {
			$(".a3l_apply_to_loadimages_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden'} );
		}

		$(document).on( "a3rev-ui-onoff_checkbox-switch", '.a3l_apply_to_loadimages', function( event, value, status ) {
			$(".a3l_apply_to_loadimages_container").hide().css( {'visibility': 'visible', 'height' : 'auto', 'overflow' : 'inherit'} );
			if ( status == 'true' ) {
				$(".a3l_apply_to_loadimages_container").slideDown();
			} else {
				$(".a3l_apply_to_loadimages_container").slideUp();
			}
		});
	});
})(jQuery);
</script>
<?php
	}
}

global $a3_lazy_load_global_settings_panel;
$a3_lazy_load_global_settings_panel = new A3_Lazy_Load_Global_Settings();

/**
 * a3_lazy_load_cards_settings_form()
 * Define the callback function to show subtab content
 */
function a3_lazy_load_global_settings_form() {
	global $a3_lazy_load_global_settings_panel;
	$a3_lazy_load_global_settings_panel->settings_form();
}

?>
