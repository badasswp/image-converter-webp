<?php
/**
 * Options Class.
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

class MyAdmin extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.1.2
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
	 * @since 1.1.2
	 *
	 * @return void
	 */
	public function register_options_menu(): void {
		add_submenu_page(
			'upload.php',
			__( 'Lorem', 'image-converter-webp' ),
			__( 'Lorem', 'image-converter-webp' ),
			'manage_options',
			'lorem',
			[ $this, 'register_options_page' ],
		);
	}

	/**
	 * Register Options Page.
	 *
	 * This controls the display of the menu page.
	 *
	 * @since 1.1.2
	 *
	 * @return void
	 */
	public function register_options_page(): void {
		$options = [
			'caption' => esc_html__(
				'Image Converter for WebP',
				'image-converter-webp'
			),
			'summary' => esc_html__(
				'Convert your WordPress JPG/PNG images to WebP formats during runtime.',
				'image-converter-webp'
			),
			'form'    => ( new Form( Options::FIELDS ) )->get_form(),
		];

		vprintf(
			'<section class="wrap">
				<h1>%s</h1>
				<p>%s</p>
				%s
			</section>',
			$options
		);
	}

	/**
	 * Register Settings.
	 *
	 * This method handles all save actions for the fields
	 * on the Plugin's settings page.
	 *
	 * @since 1.1.2
	 *
	 * @return void
	 */
	public function register_options_init(): void {
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
	 * Register Styles.
	 *
	 * @since 1.1.2
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
