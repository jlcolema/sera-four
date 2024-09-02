<?php

// ------------------------------------------------------------------------
// Add Redux Framework & extras
// ------------------------------------------------------------------------

require_once(get_template_directory() . '/core/options-init.php');
$redux_ThemeTek = get_option( 'redux_ThemeTek' );

define( 'KD_THEME_PATH', dirname(dirname(__FILE__)) );
define( 'KD_THEME_PLUGINS_DIR', KD_THEME_PATH . '/core/plugins' );

// ------------------------------------------------------------------------
// Theme includes
// ------------------------------------------------------------------------

// Wordpress Bootstrap Menu
require_once ( get_template_directory() . '/core/assets/extra/wp_bootstrap_navwalker.php');

// ------------------------------------------------------------------------
// Enqueue scripts and styles front and admin
// ------------------------------------------------------------------------

	if( !function_exists('keydesign_enqueue_front') ) {
		function keydesign_enqueue_front() {

			$redux_ThemeTek = get_option( 'redux_ThemeTek' );
			// Bootstrap CSS
			wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/core/assets/css/bootstrap.min.css', '', '' );
			// Theme main style CSS
			wp_enqueue_style( 'keydesign-style', get_stylesheet_uri() );
			// Font Awesome
			wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/core/assets/css/font-awesome.min.css', '', '' );
			// Bootstrap JS
			wp_enqueue_script( 'bootstrapjs', get_template_directory_uri() . '/core/assets/js/bootstrap.min.js', array('jquery'), '', true );
			// Masonry
			if( is_front_page() || is_page_template('portfolio.php') ) {
				wp_enqueue_script( 'masonry' );
			}
			if( is_singular( 'portfolio' ) ) {
				wp_enqueue_style( 'photoswipe', get_template_directory_uri() . '/core/assets/css/photoswipe.css', '', '' );
				wp_enqueue_style( 'photoswipe-skin', get_template_directory_uri() . '/core/assets/css/photoswipe-default-skin.css', '', '' );
				wp_enqueue_script( 'photoswipejs', get_template_directory_uri() . '/core/assets/js/photoswipe.min.js', array('jquery'), '', true );
				wp_enqueue_script( 'photoswipejs-ui', get_template_directory_uri() . '/core/assets/js/photoswipe-ui-default.min.js', array('jquery'), '', true );
			}
			// Theme main scripts
			wp_enqueue_script( 'keydesign-smooth-scroll', get_template_directory_uri() . '/core/assets/js/SmoothScroll.js', array('jquery'), '', true );
			wp_enqueue_script( 'keydesign-scripts', get_template_directory_uri() . '/core/assets/js/scripts.js', array(), '', true );

			// Visual composer - move styles to head
			wp_enqueue_style( 'js_composer_front' );
			wp_enqueue_style( 'js_composer_custom_css' );

		}
	}
	add_action( 'wp_enqueue_scripts', 'keydesign_enqueue_front' );

	if( !function_exists('keydesign_enqueue_admin') ) {
		function keydesign_enqueue_admin() {
					wp_enqueue_style( 'keydesign_wp_admin_css', get_template_directory_uri() . '/core/assets/css/admin-styles.css', '', '' );
	        wp_enqueue_script( 'keydesign_wp_admin_js', get_template_directory_uri() . '/core/assets/js/admin-scripts.js', '', '1.0.0' );
		}
	}
	add_action( 'admin_enqueue_scripts', 'keydesign_enqueue_admin' );

// ------------------------------------------------------------------------
// Theme Setup
// ------------------------------------------------------------------------

	function keydesign_setup(){
		if ( function_exists( 'add_theme_support' ) ) {
			// Add multilanguage support
			load_theme_textdomain( 'incubator', get_template_directory() . '/languages' );
			// Add theme support for feed links
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'custom-header', array() );
			add_theme_support( 'custom-background', array() );
			// Add theme support for menus
			if ( function_exists( 'register_nav_menus' ) ) {
				register_nav_menus(
					array(
					  'keydesign-header-menu' => 'Header Menu',
						'keydesign-footer-menu' => 'Footer Menu'
					)
				);
			}
			// Enable support for Blog Posts Thumbnails
			add_theme_support( 'post-thumbnails' );
			}
	}
	add_action( 'after_setup_theme', 'keydesign_setup' );


// ------------------------------------------------------------------------
// Include plugin check, meta boxes, custom posts
// ------------------------------------------------------------------------

	// Theme activation and plugin check
	include( get_template_directory() . '/core/theme-activation.php' );

	// Register widgetized areas
	include( get_template_directory() . '/core/theme-sidebars.php' );

// ------------------------------------------------------------------------
// Content Width
// ------------------------------------------------------------------------

	if ( ! isset( $content_width ) ) $content_width = 1240;

// ------------------------------------------------------------------------
// Main menu custom child pages attribute
// ------------------------------------------------------------------------

	function keydesign_special_nav_class($classes, $item){
    	$themetek_menu_locations = get_nav_menu_locations();
			$themetek_pageid = get_post_meta( $item->ID, '_menu_item_object_id', true );
      $themetek_parrent_bool = get_page( $themetek_pageid );
      if ( ! empty($themetek_parrent_bool) && is_a($themetek_parrent_bool, 'WP_Post') ) {
				if($themetek_parrent_bool->post_parent) {
					$classes[] = 'one-page-link';
				}
  	 	}

    	return $classes;
	}
	add_filter('nav_menu_css_class' , 'keydesign_special_nav_class' , 10 , 2);

// ------------------------------------------------------------------------
// Blog functionality
// ------------------------------------------------------------------------

	// Custom blog navigation
	function keydesign_link_attributes_1($themetek_output) {
			return str_replace('<a href=', '<a class="next" href=', $themetek_output);
	}
	function keydesign_link_attributes_2($themetek_output) {
			return str_replace('<a href=', '<a class="prev" href=', $themetek_output);
	}

	add_filter('next_post_link', 'keydesign_link_attributes_1');
	add_filter('previous_post_link', 'keydesign_link_attributes_2');

	// Comment reply script enqueued
	function keydesign_enqueue_comments_reply() {
		if( get_option( 'thread_comments' ) )  {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'comment_form_before', 'keydesign_enqueue_comments_reply' );

	// Excerpt length
	function keydesign_excerpt_length($length) {
		return 23;
	}
	add_filter('excerpt_length', 'keydesign_excerpt_length');

	function keydesign_remove_api () {
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	}
	add_action( 'after_setup_theme', 'keydesign_remove_api' );

// ------------------------------------------------------------------------
// Output Visual Composer custom CSS
// ------------------------------------------------------------------------

add_action('wp_head', 'keydesign_vc_custom_css');
function keydesign_vc_custom_css() {
   $keydesign_homePageID=get_the_ID();
   $keydesign_args=array('post_type'=>'page','posts_per_page'=>-1,'post_parent'=>$keydesign_homePageID,'post__not_in'=>array($keydesign_homePageID),'order'=>'ASC','orderby'=>'menu_order');
   $keydesign_parent=new WP_Query($keydesign_args);
   while($keydesign_parent->have_posts()) {
   $keydesign_parent->the_post();
   $current_id = get_the_ID();
   wp_reset_postdata();
        if  ( $current_id ) {
            $shortcodes_custom_css = get_post_meta( $current_id, '_wpb_shortcodes_custom_css', true );
            if ( ! empty( $shortcodes_custom_css ) ) {
                echo '<style type="text/css" data-type="vc_shortcodes-custom-css-'.$current_id.'">';
                echo esc_html($shortcodes_custom_css);
                echo '</style>';
            }
		}
	}
}

// ------------------------------------------------------------------------
// Force Visual Composer to initialize as "built into the theme".
// ------------------------------------------------------------------------

	function keydesign_vcSetAsTheme() {
		vc_set_as_theme($disable_updater = true);
	}
	add_action( 'vc_before_init', 'keydesign_vcSetAsTheme' );

// ------------------------------------------------------------------------
// Load maintenance page template
// ------------------------------------------------------------------------

	add_action( 'template_include', 'keydesign_maintenance_mode', 1 );
	function keydesign_maintenance_mode( $template ) {
		$redux_ThemeTek = get_option( 'redux_ThemeTek' );
		if ( ! class_exists( 'ReduxFramework' ) ) {
			return $template;
		}

		$new_template = locate_template( array( '/core/templates/maintenance-page-template.php' ) );

		if ( isset($redux_ThemeTek['tek-maintenance-mode']) ) {
			if ( $redux_ThemeTek['tek-maintenance-mode'] && !is_user_logged_in() ) {
				return $new_template;
			}
		}

		return $template;
	}

// ------------------------------------------------------------------------
// Output Theme Options custom code
// ------------------------------------------------------------------------

	function keydesign_vc_custom_colors() {
		echo '<style type="text/css">';
		$redux_ThemeTek = get_option( 'redux_ThemeTek' );
		include( get_template_directory() . '/core/dynamic-styles.css.php' );
		echo '</style>';
	}
	add_action('wp_head', 'keydesign_vc_custom_colors');

	function keydesign_custom_theme_styles() {
			$redux_ThemeTek = get_option( 'redux_ThemeTek' );
			if ( isset($redux_ThemeTek['tek-css']) ) {
				wp_add_inline_style( 'keydesign-style', $redux_ThemeTek['tek-css'] );
			}
	}
	add_action( 'wp_enqueue_scripts', 'keydesign_custom_theme_styles' );

	function keydesign_hook_javascript() {
			$redux_ThemeTek = get_option( 'redux_ThemeTek' );
			if( ! empty( $redux_ThemeTek['tek-javascript'] ) || isset( $redux_ThemeTek['tek-javascript'] ) ) {
				wp_add_inline_script( 'keydesign-scripts', $redux_ThemeTek['tek-javascript'] );
			}
	}
	add_action( 'wp_enqueue_scripts', 'keydesign_hook_javascript' );


// ------------------------------------------------------------------------
// WooCommerce
// ------------------------------------------------------------------------

	if( class_exists( 'WooCommerce' )) {
		add_theme_support( 'woocommerce' );
	}
	if( class_exists( 'WooCommerce' )) {
		require_once( get_template_directory() . '/core/theme-woocommerce.php' );
	}
