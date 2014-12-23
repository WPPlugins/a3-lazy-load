<?php
class A3_Lazy_Load
{
	const version = A3_LAZY_VERSION;
	protected $_placeholder_url;
	protected $_skip_classes;
	protected static $_instance;

	function __construct() {
		global $a3_lazy_load_global_settings;

		// Disable for Dashboard
		if ( is_admin() ) {
			return;
		}

		// Disable when not allow from global settings
		if ( $a3_lazy_load_global_settings['a3l_apply_to_loadimages'] == false ) {
			return;
		}

		// Disable when viewing printable page from WP-Print
		if ( intval( get_query_var( 'print' ) ) == 1 || intval( get_query_var( 'printpage' ) ) == 1 ) {
			return;
		}

		// Disable on Opera Mini
		if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Opera Mini' ) !== false ) {
			return;
		}

		if ( true == $a3_lazy_load_global_settings['a3l_load_disable_on_wptouch'] && A3_Lazy_Load::is_wptouch() ) {
			return;
		}

		if ( true == $a3_lazy_load_global_settings['a3l_load_disable_on_mobilepress'] && A3_Lazy_Load::is_mobilepress() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'a3_lazy_load_html', array( $this, 'filter' ), 10, 1 );
		add_filter( 'a3_lazy_load_images', array( $this, 'filter' ), 10, 1 );

		$skip_classes = $a3_lazy_load_global_settings['a3l_skip_image_with_class'];
		if ( strlen( trim( $skip_classes ) ) ) {
			$this->_skip_classes = array_map( 'trim', explode( ',', $a3_lazy_load_global_settings['a3l_skip_image_with_class'] ) );
		}

		$this->_placeholder_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

		if ( $a3_lazy_load_global_settings['a3l_apply_to_content'] == true ) {
			add_filter( 'the_content', array( $this, 'filter' ), 200 );
		}
		if ( $a3_lazy_load_global_settings['a3l_apply_to_textwidget'] == true ) {
			add_filter( 'widget_text', array( $this, 'filter' ), 200 );
		}
		if ( $a3_lazy_load_global_settings['a3l_apply_to_postthumbnails'] == true ) {
			add_filter( 'post_thumbnail_html', array( $this, 'filter' ), 200 );
		}
		if ( $a3_lazy_load_global_settings['a3l_apply_to_gravatars'] == true ) {
			add_filter( 'get_avatar', array( $this, 'filter' ), 200 );
		}
	}

	static function _instance() {
		if ( ! isset( self::$_instance ) ) {
			$className = __CLASS__;
			self::$_instance = new $className;
		}
		return self::$_instance;
	}

	static function enqueue_scripts() {

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		global $a3_lazy_load_global_settings;

		$a3l_effect = $a3_lazy_load_global_settings['a3l_effect'];
		$effect = 'fadein';
		if ( $a3l_effect != '' ) {
			$effect = $a3l_effect;
		}
		$effect = apply_filters( 'a3_lazy_load_effect' , $effect );

		do_action('before_a3_lazy_load_xt_style');

		wp_enqueue_style( 'jquery-lazyloadxt-css', apply_filters( 'a3_lazy_load_effect_css', A3_LAZY_LOAD_CSS_URL.'/jquery.lazyloadxt.'.$effect.'.css' ) );

		do_action('after_a3_lazy_load_xt_style');

		$in_footer = true;
		$theme_loader_function = $a3_lazy_load_global_settings['a3l_theme_loader'];

		if ( $theme_loader_function == 'wp_head' ) {
			$in_footer = false;
		}

		do_action('before_a3_lazy_load_xt_script');
		wp_enqueue_script( 'jquery-lazyloadxt', apply_filters( 'a3_lazy_load_main_script', A3_LAZY_LOAD_JS_URL.'/jquery.lazyloadxt'.$suffix.'.js' ), array( 'jquery' ), self::version, $in_footer );
		do_action('after_a3_lazy_load_xt_script');
	}

	static function is_wptouch() {
		if ( function_exists( 'bnc_wptouch_is_mobile' ) && bnc_wptouch_is_mobile() ) {
			return true;
		}

		global $wptouch_pro;

		if ( defined( 'WPTOUCH_VERSION' ) || is_object( $wptouch_pro ) ) {

			if ( $wptouch_pro->showing_mobile_theme ) {
				return true;
			}
		}

		return false;
	}

	static function has_wptouch() {
		if ( function_exists( 'bnc_wptouch_is_mobile' ) || defined( 'WPTOUCH_VERSION' ) ) {
			return true;
		}
		return false;
	}

	static function is_mobilepress() {

		if ( function_exists( 'mopr_get_option' ) && WP_CONTENT_DIR . mopr_get_option( 'mobile_theme_root', 1 ) == get_theme_root() ) {
			return true;
		}

		return false;
	}

	static function has_mobilepress() {
		if ( class_exists( 'Mobilepress_core' ) ) {
			return true;
		}

		return false;
	}

	static function filter( $content ) {
		if( is_admin() ){
			return $content;
		}
		$run_filter = true;
		$run_filter = apply_filters( 'a3_lazy_load_run_filter', $run_filter );

		if ( ! $run_filter ) {
			return $content;
		}

		global $a3_lazy_load_global_settings;

		$A3_Lazy_Load = A3_Lazy_Load::_instance();

		$content = apply_filters( 'a3_lazy_load_images_before', $content );

		$content = $A3_Lazy_Load->_filter_images( $content );

		$content = apply_filters( 'a3_lazy_load_images_after', $content );

		return $content;
	}

	protected function _filter_images_single( $content ) {
		// Don't lazyload for feeds, previews, mobile
		if( is_feed() || is_preview() || ( function_exists( 'is_mobile' ) && is_mobile() ) )
			return $content;

		// Don't lazy-load if the content has already been run through previously
		if ( false !== strpos( $content, 'data-src' ) )
			return $content;

		// In case you want to change the placeholder image
		$placeholder_image = $this->_placeholder_url;

		// This is a pretty simple regex, but it works
		$content = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf( '<img${1}src="%s" data-src="${2}"${3}><noscript><img${1}src="${2}"${3}></noscript>', $placeholder_image ), $content );

		return $content;
	}

	protected function _filter_images( $content ) {

		$matches = array();
		preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );

		$search = array();
		$replace = array();

		if ( is_array( $this->_skip_classes ) ) {
			$skip_images_preg_quoted = array_map( 'preg_quote', $this->_skip_classes );
			$skip_images_regex = sprintf( '/class=".*(%s).*"/s', implode( '|', $skip_images_preg_quoted ) );
		}

		$i = 0;
		foreach ( $matches[0] as $imgHTML ) {

			// don't to the replacement if a skip class is provided and the image has the class, or if the image is a data-uri
			if ( ! ( is_array( $this->_skip_classes ) && preg_match( $skip_images_regex, $imgHTML ) ) && ! preg_match( "/src=['\"]data:image/is", $imgHTML ) ) {
				$i++;
				// replace the src and add the data-src attribute
				$replaceHTML = '';
				$replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . $this->_placeholder_url . '" data-lazy-type="image" data-src=', $imgHTML );

				// add the lazy class to the img element
				if ( preg_match( '/class=["\']/i', $replaceHTML ) ) {
					$replaceHTML = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1lazy lazy-hidden $2$1', $replaceHTML );
				} else {
					$replaceHTML = preg_replace( '/<img/is', '<img class="lazy lazy-hidden"', $replaceHTML );
				}

				$replaceHTML .= '<noscript>' . $imgHTML . '</noscript>';

				array_push( $search, $imgHTML );
				array_push( $replace, $replaceHTML );
			}
		}

		$search = array_unique( $search );
		$replace = array_unique( $replace );

		$content = str_replace( $search, $replace, $content );


		return $content;
	}

	protected function _filter_iframes( $content ) {

		$matches = array();
		preg_match_all( '/<iframe\s+.*?>/', $content, $matches );

		$search = array();
		$replace = array();

		foreach ( $matches[0] as $iframeHTML ) {

			// Don't mess with the Gravity Forms ajax iframe
			if ( strpos( $iframeHTML, 'gform_ajax_frame' ) ) {
				continue;
			}

			$replaceHTML = '<img src="' . $this->_placeholder_url . '"  class="lazy lazy-hidden" data-lazy-type="iframe" data-src="' . base64_encode($iframeHTML) . '" alt="">';

			$replaceHTML .= '<noscript>' . $iframeHTML . '</noscript>';

			array_push( $search, $iframeHTML );
			array_push( $replace, $replaceHTML );
		}

		$content = str_replace( $search, $replace, $content );

		return $content;
	}
}

add_action( 'wp', create_function('', 'if ( ! is_feed() ) { A3_Lazy_Load::_instance(); }'), 10, 0 );
?>