<?php
/**
 * Plugin Name: Book Author Bio
 * Description: Book author bio Elementor plugin eye-cathing style with 15+ preset design.
 * Plugin URI:  www.bwdplugins.com/author-biography-for-elementor-best-author-bio-plugin-2022/
 * Version:     1.0
 * Author:      Best WP Developer
 * Author URI:  www.bestwpdeveloper.com/
 * Text Domain: book-author-bio
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.0.0
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once ( plugin_dir_path(__FILE__) ) . '/includes/class-tgm-plugin-activation.php';

final class FinalBWDABAuthorBio{

	const VERSION = '1.0';

	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	const MINIMUM_PHP_VERSION = '7.0';

	public function __construct() {
		// Load translation
		add_action( 'bwdab_init', array( $this, 'bwdab_loaded_textdomain' ) );
		// bwdab_init Plugin
		add_action( 'plugins_loaded', array( $this, 'bwdab_init' ) );
	}

	public function bwdab_loaded_textdomain() {
		load_plugin_textdomain( 'book-author-bio' );
	}

	public function bwdab_init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			// For tgm plugin activation
			add_action( 'tgmpa_register', [$this, 'bwdab_author_bio_register_required_plugins'] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'bwdab_admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'bwdab_admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'bwdab-boots.php' );
	}

	function bwdab_author_bio_register_required_plugins() {
		$plugins = array(
			array(
				'name'        => esc_html__('Elementor', 'book-author-bio'),
				'slug'        => 'elementor',
				'is_callable' => 'wpseo_init',
			),
		);

		$config = array(
			'id'           => 'book-author-bio',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'plugins.php',
			'capability'   => 'manage_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		);
	
		tgmpa( $plugins, $config );
	}

	public function bwdab_admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'book-author-bio' ),
			'<strong>' . esc_html__( 'Book Author Bio', 'book-author-bio' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'book-author-bio' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'book-author-bio') . '</p></div>', $message );
	}

	public function bwdab_admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'book-author-bio' ),
			'<strong>' . esc_html__( 'Book Author Bio', 'book-author-bio' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'book-author-bio' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'book-author-bio') . '</p></div>', $message );
	}
}

// Instantiate bwdab-author-bio.
new FinalBWDABAuthorBio();
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );