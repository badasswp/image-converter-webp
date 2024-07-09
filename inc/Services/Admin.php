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

class Admin extends Service {
	/**
	 * Bind to WP.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'add_webp_translation' ] );
		add_action( 'admin_init', [ $this, 'add_webp_settings' ] );
		add_action( 'admin_menu', [ $this, 'add_webp_image_menu' ] );
	}

	/**
	 * Menu Service.
	 *
	 * This controls the menu display for the plugin.
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Moved to Admin class.
	 *
	 * @return void
	 */
	public function add_webp_image_menu(): void {
		add_submenu_page(
			'upload.php',
			__( 'Image Converter for WebP', 'image-converter-webp' ),
			__( 'Image Converter for WebP', 'image-converter-webp' ),
			'manage_options',
			'image-converter-webp',
			[ $this, 'webp_image_menu_page' ]
		);
	}

	/**
	 * Menu Callback.
	 *
	 * This controls the display of the menu page.
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Moved to Admin class.
	 *
	 * @return void
	 */
	public function webp_image_menu_page(): void {
		$settings = (string) plugin_dir_path( __FILE__ ) . '../Views/settings.php';

		if ( file_exists( $settings ) ) {
			require_once $settings;
		}
	}

	/**
	 * Save Plugin settings.
	 *
	 * This method handles all save actions for the fields
	 * on the Plugin's settings page.
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Moved to Admin class.
	 *
	 * @return void
	 */
	public function add_webp_settings(): void {
		if ( ! isset( $_POST['webp_save_settings'] ) || ! isset( $_POST['webp_settings_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['webp_settings_nonce'] ) ), 'webp_settings_action' ) ) {
			return;
		}

		$fields = [ 'quality', 'converter' ];

		update_option(
			'webp_img_converter',
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
	 * Add Plugin's Text Domain.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function add_webp_translation(): void {
		load_plugin_textdomain(
			'image-converter-webp',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/../../languages'
		);
	}
}
