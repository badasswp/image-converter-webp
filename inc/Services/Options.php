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

use ImageConverterWebP\Abstracts\Service;
use ImageConverterWebP\Interfaces\Kernel;

class Options extends Service implements Kernel {
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_page(): void {
		vprintf(
			'<section class="wrap">
				<h1>%s</h1>
				<p>%s</p>
				<form class="badasswp-form">
					<div class="badasswp-form-main">%s</div>
					<div class="badasswp-form-submit"></div>
				</form>
			</section>',
			[
				'caption' => esc_html__( 'Image Converter for WebP', 'image-converter-webp' ),
				'summary' => esc_html__( 'Convert your WordPress JPG/PNG images to WebP formats during runtime.', 'image-converter-webp' ),
			]
		);
	}

	/**
	 * Get Form Group.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public function get_form_group(): string {
		$all_controls = '';

		$form_groups = [
			'icfw_conv_options' => [
				'label'    => 'Conversion Options',
				'controls' => [
					'quality' => [
						'control'     => 'text',
						'placeholder' => '',
						'label'       => 'Conversion Quality %',
						'summary'     => 'e.g. 75',
					],
					'engine'  => [
						'control'     => 'text',
						'placeholder' => '',
						'label'       => 'WebP Engine',
						'summary'     => 'e.g. Imagick',
					],
				],
			],
		];

		foreach ( $form_groups as $form_group ) {
			foreach ( $form_group as $key => $label ) {
				switch ( $key ) {
					case 'label':
						$all_controls .= sprintf(
							'<p class="form-group-block">%s</p>',
							$label
						);
						break;

					default:
						foreach ( $label as $name => $control ) {
							$all_controls .= sprintf(
								'<p>
									<label></label>
									<input type="text"/>
									<em></em>
								</p>'
							);
						}
						break;
				}
			}
		}

		return sprintf(
			'<div class="form-group">
			</div>',
			$all_controls
		);
	}

	/**
	 * Register Settings.
	 *
	 * This method handles all save actions for the fields
	 * on the Plugin's settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_options_init(): void {
		if ( ! isset( $_POST['options_save'] ) || ! isset( $_POST['options_nonce'] ) ) {
			return;
		}
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