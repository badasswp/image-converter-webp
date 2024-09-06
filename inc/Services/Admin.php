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
			__( Options::get_page_title(), 'image-converter-webp' ),
			__( Options::get_page_title(), 'image-converter-webp' ),
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
		$form_fields          = [];
		$form_button_name     = Options::get_submit_button_name();
		$form_settings_nonce  = Options::get_submit_nonce_name();
		$form_settings_action = Options::get_submit_nonce_action();

		if ( ! isset( $_POST[ $form_button_name ] ) || ! isset( $_POST[ $form_settings_nonce ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $form_settings_nonce ] ) ), $form_settings_action ) ) {
			return;
		}

		foreach ( Options::get_fields() as $field ) {
			$form_fields = wp_parse_args(
				array_keys( $field['controls'] ?? [] ),
				$form_fields
			);
		}

		$options = array_combine(
			$form_fields,
			array_map(
				function ( $field ) {
					return sanitize_text_field( $_POST[ $field ] ?? '' );
				},
				$form_fields
			)
		);

		update_option( 'icfw', $options );
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
			plugins_url( 'image-converter-webp/styles.css' ),
			[],
			true,
			'all'
		);
	}
}
