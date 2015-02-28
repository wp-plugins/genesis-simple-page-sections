<?php
/*
Plugin Name: Genesis Simple Page Sections
Plugin URI: http://efficientwp.com/genesis-simple-page-sections
Description: Easily make full width page sections in Genesis. Must be using the Genesis theme framework.
Version: 1.0
Author: Doug Yuen
Author URI: http://efficientwp.com
License: GPLv2
*/

define( 'GSPS_PLUGIN_PATH_FOR_SUBDIRS', plugins_url( str_replace( dirname( dirname( __FILE__ ) ), '', dirname( __FILE__ ) ) ) );
register_activation_hook( __FILE__, array( $this, 'activation_hook' ) );

add_shortcode( 'genesis-simple-page-section', 'ewp_gsps' );
add_shortcode( 'gsps', 'ewp_gsps' );
/*wp_enqueue_style( 'genesis-simple-page-sections-styles', GSPS_PLUGIN_PATH_FOR_SUBDIRS . '/includes/styles.css'  );/**/
add_action( 'genesis_meta', 'ewp_gsps_css' );
add_filter( 'body_class', 'ewp_gsps_body_class');

function ewp_gsps_check( ) {
	global $post;
	if( has_shortcode( $post->post_content, 'genesis-simple-page-section' ) ) {
		return true;
	} else {
		return false;
	}
}
function ewp_gsps_css( ) {
	if( ewp_gsps_check() ) {
		add_filter( 'genesis_pre_get_option_site_layout', 'full_width_content' );
		$gsps_css = '<style type="text/css">
			body.genesis-simple-page-sections .site-container,
			body.genesis-simple-page-sections #inner,
			body.genesis-simple-page-sections #inner .wrap,
			body.genesis-simple-page-sections #content-sidebar-wrap, 
			body.genesis-simple-page-sections div.content-sidebar-wrap, 
			body.genesis-simple-page-sections #content,
			body.genesis-simple-page-sections main.content, 
			body.genesis-simple-page-sections div.site-inner,
			body.genesis-simple-page-sections div.site-inner article,
			body.genesis-simple-page-sections div.site-inner .wrap {
			  margin-bottom: 0;
			  max-width: none;
			  padding: 0;
			  width: 100%;
			}
			div.gsps-outer {
			  clear: both; 
			  display: block; 
			  overflow: hidden;
			  padding: 0;
			  width: 100%;
			}
			div.gsps-inner {
			  margin: 5rem auto;
			  max-width: 800px;
			  overflow: hidden;
			}
		</style>';
		echo preg_replace( '/\s\s+/', '', $gsps_css );
	}
}
function ewp_gsps_body_class( $classes ) {
	if( ewp_gsps_check() ) {
		$classes[] = 'genesis-simple-page-sections';
	}
	return $classes;
}
function ewp_gsps( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'outer_class' => '',
		'outer_css' => '',
		'inner_class' => '',
		'inner_css' => '',
		'color' => '',
		'width' => ''
		), $atts ) );
	$flat_colors = array( 
		'turquoise', 'green sea', 'emerald', 'nephritis', 
		'peter river', 'belize hole', 'amethyst', 'wisteria', 
		'wet asphalt', 'midnight blue', 'sun flower', 'orange', 
		'carrot', 'pumpkin', 'alizarin', 'pomegranate', 
		'clouds', 'silver', 'concrete', 'asbestos' 
	);
	$flat_color_values = array( 
		'#1abc9c', '#16a085', '#2ecc71', '#27ae60', 
		'#3498db', '#2980b9', '#9b59b6', '#8e44ad', 
		'#34495e', '#2c3e50', '#f1c40f', '#f39c12', 
		'#e67e22', '#d35400', '#e74c3c', '#c0392b', 
		'#ecf0f1', '#bdc3c7', '#95a5a6', '#7f8c8d' 
	);
	$background_color = esc_attr( $color );
	$width = esc_attr( $width );
	$background_color = str_replace( $flat_colors, $flat_color_values, $background_color );
	$output = '';
	$output .= '<div class="gsps-outer';
	if ( esc_attr( $outer_class ) ) {
		$output .= ' ' . esc_attr( $outer_class );
	}
	if ( ( $background_color ) || ( esc_attr( $outer_css ) ) ) {
		$output .= '" style="';
	}
	if ( $background_color ) {
		$output .= 'background-color:';
		if ( ctype_xdigit( $background_color ) ) {
			$output .= '#';
		}
		$output .= $background_color . ';';
	}
	if ( esc_attr( $outer_css ) ) {
		$output .= esc_attr( $outer_css );
	}
	$output .= '">';
	$output .= '<div class="gsps-inner';
	if ( esc_attr( $inner_class ) ) {
		$output .= ' ' . esc_attr( $inner_class );
	}
	if ( ( $width ) || ( esc_attr( $inner_css ) ) ) {
		$output .= '" style="';
	}
	if ( $width ) {
		$output .= 'max-width:' . $width;
		if ( ctype_digit( $width ) ) {
			$output .= 'px';
		}
		$output .= ';';
	}
	if ( esc_attr( $inner_css ) ) {
		$output .= esc_attr( $inner_css );
	}
	$output .= '">'  . do_shortcode($content) . '</div></div>';
	return $output;
}

?>