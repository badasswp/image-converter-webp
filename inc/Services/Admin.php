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

use ImageConverterWebP\Admin\Form;
use ImageConverterWebP\Admin\Options;
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
		add_action( 'admin_init', [ $this, 'register_options_init' ] );
		add_action( 'admin_menu', [ $this, 'register_options_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_options_styles' ] );
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
	public function register_options_menu(): void {
		add_submenu_page(
			'upload.php',
			__( 'Image Converter for WebP', 'image-converter-webp' ),
			__( 'Image Converter for WebP', 'image-converter-webp' ),
			'manage_options',
			'image-converter-webp',
			[ $this, 'register_options_page' ],
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
	public function register_options_page(): void {
		vprintf(
			'<section class="wrap">
				<h1>%s</h1>
				<p>%s</p>
				%s
			</section>',
			( new Form( Options::FORM ) )->get_options()
		);
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
	public function register_options_init(): void {
		if ( ! isset( $_POST['icfw_save_settings'] ) || ! isset( $_POST['icfw_settings_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icfw_settings_nonce'] ) ), 'icfw_settings_action' ) ) {
			return;
		}

		$fields = [ 'quality', 'converter', 'upload', 'page_load', 'logs' ];

		update_option(
			'icfw',
			array_combine(
				$fields,
				array_map(
					function ( $field ) {
						if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['icfw_settings_nonce'] ) ), 'icfw_settings_action' ) ) {
							return sanitize_text_field( $_POST[ $field ] ?? '' );
						}
					},
					$fields
				)
			)
		);
	}

	/**
	 * Register Styles.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register_options_styles(): void {
		wp_enqueue_style(
			'image-converter-webp',
			plugins_url( 'image-converter-webp/inc/Views/css/styles.css' ),
			[],
			true,
			'all'
		);
	}
}
