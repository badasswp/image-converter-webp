<?php
/**
 * Options Class.
 *
 * This class is responsible for holding the Admin
 * page options.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Admin;

class Options {
	/**
	 * The Form.
	 *
	 * This array defines every single aspect of the
	 * Form displayed on the Admin options page.
	 *
	 * @since 1.2.0
	 *
	 * @var mixed[]
	 */
	public static array $form;

	/**
	 * Define custom static method for calling
	 * dynamic methods for e.g. Options::get_page_title().
	 *
	 * @since 1.1.2
	 * @since 1.2.0 Invoke init() call here.
	 *
	 * @param string  $method Method name.
	 * @param mixed[] $args   Method args.
	 *
	 * @return string|mixed[]
	 */
	public static function __callStatic( $method, $args ) {
		static::init();

		$keys = substr( $method, strpos( $method, '_' ) + 1 );
		$keys = explode( '_', $keys );

		$value = '';

		foreach ( $keys as $key ) {
			$value = empty( $value ) ? ( static::$form[ $key ] ?? '' ) : ( $value[ $key ] ?? '' );
		}

		return $value;
	}

	/**
	 * Set up Form.
	 *
	 * @since 1.1.2
	 * @since 1.2.0 Set up form using static methods.
	 *
	 * @return void
	 */
	public static function init(): void {
		static::$form = [
			'page'   => static::get_form_page(),
			'notice' => static::get_form_notice(),
			'fields' => static::get_form_fields(),
			'submit' => static::get_form_submit(),
		];
	}

	/**
	 * Form Page.
	 *
	 * The Form page items containg the Page title,
	 * summary, slug and option name.
	 *
	 * @since 1.1.2
	 * @since 1.2.0 Make strings translatable.
	 *
	 * @return mixed[]
	 */
	public static function get_form_page(): array {
		return [
			'title'   => esc_html__(
				'Image Converter for WebP',
				'image-converter-webp'
			),
			'summary' => esc_html__(
				'Convert your WordPress JPG/PNG images to WebP formats during runtime.',
				'image-converter-webp'
			),
			'slug'    => 'image-converter-webp',
			'option'  => 'icfw',
		];
	}

	/**
	 * Form Submit.
	 *
	 * The Form submit items containing the heading,
	 * button name & label and nonce params.
	 *
	 * @since 1.1.2
	 * @since 1.2.0 Make strings translatable.
	 *
	 * @return mixed[]
	 */
	public static function get_form_submit(): array {
		return [
			'heading' => esc_html__( 'Actions', 'image-converter-webp' ),
			'button'  => [
				'name'  => 'icfw_save_settings',
				'label' => esc_html__( 'Save Changes', 'image-converter-webp' ),
			],
			'nonce'   => [
				'name'   => 'icfw_settings_nonce',
				'action' => 'icfw_settings_action',
			],
		];
	}

	/**
	 * Form Fields.
	 *
	 * The Form field items containing the heading for
	 * each group block and controls.
	 *
	 * @since 1.1.2
	 * @since 1.2.0 Make strings translatable.
	 *
	 * @return mixed[]
	 */
	public static function get_form_fields() {
		return [
			'icfw_conv_options' => [
				'heading'  => esc_html__( 'Conversion Options', 'image-converter-webp' ),
				'controls' => [
					'quality'   => [
						'control'     => esc_attr( 'text' ),
						'placeholder' => esc_attr__( '50', 'image-converter-webp' ),
						'label'       => esc_html__( 'Conversion Quality', 'image-converter-webp' ),
						'summary'     => esc_html__( 'e.g. 75', 'image-converter-webp' ),
					],
					'converter' => [
						'control' => esc_attr( 'select' ),
						'label'   => esc_attr__( 'WebP Engine', 'image-converter-webp' ),
						'summary' => esc_html__( 'e.g. Imagick', 'image-converter-webp' ),
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
				'heading'  => esc_html__( 'Image Options', 'image-converter-webp' ),
				'controls' => [
					'upload'    => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Convert Images on Upload', 'image-converter-webp' ),
						'summary' => esc_html__( 'This is useful for new images.', 'image-converter-webp' ),
					],
					'page_load' => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Convert Images on Page Load', 'image-converter-webp' ),
						'summary' => esc_html__( 'This is useful for existing images.', 'image-converter-webp' ),
					],
				],
			],
			'icfw_log_options'  => [
				'heading'  => 'Log Options',
				'controls' => [
					'logs' => [
						'control' => esc_attr( 'checkbox' ),
						'label'   => esc_html__( 'Log errors for Failed Conversions', 'image-converter-webp' ),
						'summary' => esc_html__( 'Enable this option to log errors.', 'image-converter-webp' ),
					],
				],
			],
		];
	}

	/**
	 * Form Notice.
	 *
	 * The Form notice containing the notice
	 * text displayed on save.
	 *
	 * @since 1.1.2
	 * @since 1.2.0 Make strings translatable.
	 *
	 * @return mixed[]
	 */
	public static function get_form_notice(): array {
		return [
			'label' => esc_html__( 'Settings Saved.', 'image-converter-webp' ),
		];
	}
}
