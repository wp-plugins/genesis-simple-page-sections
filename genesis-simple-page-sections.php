<?php
/*
Plugin Name: Genesis Simple Page Sections
Plugin URI: http://efficientwp.com/plugins/genesis-simple-page-sections
Description: Easily make full width page sections in Genesis. Must be using the Genesis theme framework.
Version: 1.2
Author: Doug Yuen
Author URI: http://efficientwp.com
License: GPLv2
*/

add_shortcode( 'genesis-simple-page-section', 'ewp_gsps' );
add_shortcode( 'gsps', 'ewp_gsps' );
add_filter( 'body_class', 'ewp_gsps_body_class');
add_action( 'wp_enqueue_scripts', 'ewp_gsps_load_scripts' );

function ewp_gsps_load_scripts() {
	wp_enqueue_style( 'gsps-styles', plugin_dir_url( __FILE__ ) . 'includes/styles.css' );
}
function ewp_gsps_check( ) {
	global $post;
	if( has_shortcode( $post->post_content, 'genesis-simple-page-section' ) || has_shortcode( $post->post_content, 'gsps' ) ) {
		return true;
	} else {
		return false;
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