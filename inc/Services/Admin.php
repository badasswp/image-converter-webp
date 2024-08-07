<?php
/**
 * Admin Class.
 *
 * This class holds the logic for registering
 * the plugin's admin page.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Services;

use ImageConverterWebP\Abstracts\Service;
use ImageConverterWebP\Interfaces\Kernel;

class Admin extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_icfw_translation' ] );
		add_action( 'admin_init', [ $this, 'register_icfw_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_icfw_options_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_icfw_styles' ] );
	}

	/**
	 * Register Options Menu.
	 *
	 * This controls the menu display for the plugin.
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Moved to Admin class.
	 *
	 * @return void
	 */
	public function register_icfw_options_menu(): void {
		add_submenu_page(
			'upload.php',
			__( 'Image Converter for WebP', 'image-converter-webp' ),
			__( 'Image Converter for WebP', 'image-converter-webp' ),
			'manage_options',
			'image-converter-webp',
			[ $this, 'register_icfw_options_page' ]
		);
	}

	/**
	 * Register Options Page.
	 *
	 * This controls the display of the menu page.
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Moved to Admin class.
	 *
	 * @return void
	 */
	public function register_icfw_options_page(): void {
		$settings = (string) plugin_dir_path( __FILE__ ) . '../Views/settings.php';

		if ( file_exists( $settings ) ) {
			require_once $settings;
		}
	}

	/**
	 * Register Settings.
	 *
	 * This method handles all save actions for the fields
	 * on the Plugin's settings page.
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Moved to Admin class.
	 *
	 * @return void
	 */
	public function register_icfw_settings(): void {
		if ( ! isset( $_POST['webp_save_settings'] ) || ! isset( $_POST['webp_settings_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['webp_settings_nonce'] ) ), 'webp_settings_action' ) ) {
			return;
		}

		$fields = [ 'quality', 'converter', 'upload', 'page_load' ];

		update_option(
			'icfw',
			array_combine(
				$fields,
				array_map(
					function ( $field ) {
						if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['webp_settings_nonce'] ) ), 'webp_settings_action' ) ) {
							return sanitize_text_field( $_POST[ $field ] ?? '' );
						}
					},
					$fields
				)
			)
		);
	}

	/**
	 * Register Text Domain.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register_icfw_translation(): void {
		load_plugin_textdomain(
			'image-converter-webp',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/../../languages'
		);
	}

	/**
	 * Register Styles.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register_icfw_styles(): void {
		wp_enqueue_style(
			'image-converter-webp',
			plugins_url( 'image-converter-webp/inc/Views/css/styles.css' ),
			[],
			true,
			'all'
		);
	}
}
