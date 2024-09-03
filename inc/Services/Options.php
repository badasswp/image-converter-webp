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
		$options = [
			'caption' => esc_html__(
				'Image Converter for WebP',
				'image-converter-webp'
			),
			'summary' => esc_html__(
				'Convert your WordPress JPG/PNG images to WebP formats during runtime.',
				'image-converter-webp'
			),
			'getForm' => $this->get_form(),
		];

		vprintf(
			'<section class="wrap">
				<h1>%s</h1>
				<p>%s</p>
				<form class="badasswp-form">
					<div class="badasswp-form-main">%s</div>
					<div class="badasswp-form-submit"></div>
				</form>
			</section>',
			$options
		);
	}

	/**
	 * Get Form.
	 *
	 * This method is responsible for obtaining
	 * the complete form.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public function get_form(): string {
		$form = '';

		foreach ( $this->get_form_groups() as $form_group ) {
			$form .= $this->get_form_group( $form_group );
		}

		return $form;
	}

	/**
	 * Get Form Group.
	 *
	 * This method is responsible for obtaining
	 * a single form group.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $arg Form group array.
	 * @return string
	 */
	public function get_form_group( $arg ): string {
		$form_group = '';

		foreach ( $arg as $key => $value ) {
			switch ( $key ) {
				case 'label':
					$form_group .= sprintf(
						'<p class="badasswp-form-group-block">%s</p>',
						$value
					);
					break;

				default:
					foreach ( $value as $name => $control ) {
						$group_block = [
							'label'   => $control['label'],
							'control' => $this->get_form_control( $control, $name ),
							'summary' => $control['summary'],
						];

						$form_group .= vsprintf(
							'<p class="badasswp-form-group-block size-50">
								<label>%1$s</label>
								%2$s
								<em>%3$s</em>
							</p>',
							$group_block,
						);
					}
					break;
			}
		}

		return sprintf( '<div class="badasswp-form-group">%s</div>', $form_group );
	}

	/**
	 * Get Form Control.
	 *
	 * This method is responsible for getting the
	 * form control.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $arg  Form control array.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	public function get_form_control( $arg, $name ): string {
		$control = '';

		switch ( $arg['control'] ) {
			case 'text':
				$control = sprintf(
					'<input type="text" placeholder="%1$s" value="%2$s" name="%3$s"/>',
					$arg['placeholder'],
					get_option( 'icfw', [] )[ $name ] ?? '',
					$name,
				);
				break;

			case 'select':
				foreach ( $arg['options'] as $key => $value ) {
					$is_selected = ( ( get_option( 'icfw', [] )[ $name ] ?? '' ) === $key )
										? 'selected' : '';

					$options .= sprintf(
						'<option value="%1$s" %2$s>%3$s</option>',
						$key,
						$is_selected,
						$value,
					);
				}

				$control = sprintf(
					'<select name="%1$s">
						%2$s
					</select>',
					$name,
					$options,
				);
				break;

			case 'checkbox':
				$is_checked = ! empty( get_option( 'icfw', [] )[ $name ] ?? '' )
									? 'checked' : '';

				$control = sprintf(
					'<input
						name="%1$s"
						type="checkbox"
						%2$s
					/>',
					$name,
					$is_checked,
				);
				break;
		}

		return $control;
	}

	/**
	 * Get Form Groups.
	 *
	 * This method is responsible for obtaining
	 * all the form groups.
	 *
	 * @since 1.1.2
	 *
	 * @return mixed[]
	 */
	public function get_form_groups(): array {
		return [
			'icfw_conv_options' => [
				'label'    => 'Conversion Options',
				'controls' => [
					'quality'   => [
						'control'     => 'text',
						'placeholder' => '',
						'label'       => 'Conversion Quality %',
						'summary'     => 'e.g. 75',
					],
					'converter' => [
						'control' => 'select',
						'label'   => 'WebP Engine',
						'summary' => 'e.g. Imagick',
						'options' => [
							'gd'      => 'GD',
							'cwebp'   => 'CWebP',
							'ffmpeg'  => 'FFMPeg',
							'imagick' => 'Imagick',
							'gmagick' => 'Gmagick',
						],
					],
				],
			],
			'icfw_img_options'  => [
				'label'    => 'Image Options',
				'controls' => [
					'upload'    => [
						'control'     => 'checkbox',
						'placeholder' => '',
						'label'       => 'Convert Images on Upload',
						'summary'     => 'This is useful for new images.',
					],
					'page_load' => [
						'control'     => 'checkbox',
						'placeholder' => '',
						'label'       => 'Convert Images on Page Load',
						'summary'     => 'This is useful for existing images.',
					],
				],
			],
		];
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
