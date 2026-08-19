<?php
/**
 * Plugin Name: Mim Product Categories
 * Description: A customizable responsive WooCommerce product categories widget for Elementor.
 * Version: 1.3.2
 * Author: Mahdi Hassani
 * Text Domain: mim-product-categories
 * Requires Plugins: elementor, woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MIM_PC_VERSION', '1.3.2' );
define( 'MIM_PC_FILE', __FILE__ );
define( 'MIM_PC_URL', plugin_dir_url( __FILE__ ) );

function mim_pc_admin_notice( $message ) {
	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html( $message )
	);
}

function mim_pc_boot() {
	load_plugin_textdomain( 'mim-product-categories', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', function () {
			mim_pc_admin_notice( __( 'Mim Product Categories requires Elementor to be installed and activated.', 'mim-product-categories' ) );
		} );
		return;
	}

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', function () {
			mim_pc_admin_notice( __( 'Mim Product Categories requires WooCommerce to be installed and activated.', 'mim-product-categories' ) );
		} );
		return;
	}

	add_action( 'wp_enqueue_scripts', 'mim_pc_register_assets' );
	add_action( 'elementor/frontend/after_register_styles', 'mim_pc_register_assets' );
	add_action( 'elementor/widgets/register', 'mim_pc_register_widget' );
}
add_action( 'plugins_loaded', 'mim_pc_boot' );

function mim_pc_register_assets() {
	wp_register_style(
		'mim-product-categories',
		MIM_PC_URL . 'assets/css/mim-product-categories.css',
		array(),
		MIM_PC_VERSION
	);

	wp_register_script(
		'mim-product-categories-carousel',
		MIM_PC_URL . 'assets/js/mim-product-categories-carousel.js',
		array( 'elementor-frontend' ),
		MIM_PC_VERSION,
		true
	);
}

function mim_pc_register_widget( $widgets_manager ) {
	require_once __DIR__ . '/includes/class-mim-product-categories-widget.php';
	$widgets_manager->register( new \Mim_Product_Categories_Widget() );
}
