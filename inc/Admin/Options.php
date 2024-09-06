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
	 * Define custom static method for calling
	 * dynamic methods for e.g. Options::get_page_title().
	 *
	 * @since 1.1.2
	 *
	 * @param string  $method Method name.
	 * @param mixed[] $args   Method args.
	 *
	 * @return string|mixed[]
	 */
	public static function __callStatic( $method, $args ) {
		$keys = substr( $method, strpos( $method, '_' ) + 1 );
		$keys = explode( '_', $keys );

		$value = '';

		foreach ( $keys as $key ) {
			$value = empty( $value ) ? ( self::FORM[ $key ] ?? '' ) : ( $value[ $key ] ?? '' );
		}

		return $value;
	}

	/**
	 * The Form.
	 *
	 * This array defines every single aspect of the
	 * Form displayed on the Admin options page.
	 *
	 * @since 1.1.2
	 */
	public const FORM = [
		'page'   => self::FORM_PAGE,
		'notice' => self::FORM_NOTICE,
		'fields' => self::FORM_FIELDS,
		'submit' => self::FORM_SUBMIT,
	];

	/**
	 * Form Page.
	 *
	 * The Form page items containg the Page title,
	 * summary, slug and option name.
	 *
	 * @since 1.1.2
	 */
	public const FORM_PAGE = [
		'title'   => 'Image Converter for WebP',
		'summary' => 'Convert your WordPress JPG/PNG images to WebP formats during runtime.',
		'slug'    => 'image-converter-webp',
		'option'  => 'icfw',
	];

	/**
	 * Form Submit.
	 *
	 * The Form submit items containing the heading,
	 * button name & label and nonce params.
	 *
	 * @since 1.1.2
	 */
	public const FORM_SUBMIT = [
		'heading' => 'Actions',
		'button'  => [
			'name'  => 'icfw_save_settings',
			'label' => 'Save Changes',
		],
		'nonce'   => [
			'name'   => 'icfw_settings_nonce',
			'action' => 'icfw_settings_action',
		],
	];

	/**
	 * Form Fields.
	 *
	 * The Form field items containing the heading for
	 * each group block and controls.
	 *
	 * @since 1.1.2
	 */
	public const FORM_FIELDS = [
		'icfw_conv_options' => [
			'heading'  => 'Conversion Options',
			'controls' => [
				'quality'   => [
					'control'     => 'text',
					'placeholder' => '50',
					'label'       => 'Conversion Quality',
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
			'heading'  => 'Image Options',
			'controls' => [
				'upload'    => [
					'control' => 'checkbox',
					'label'   => 'Convert Images on Upload',
					'summary' => 'This is useful for new images.',
				],
				'page_load' => [
					'control' => 'checkbox',
					'label'   => 'Convert Images on Page Load',
					'summary' => 'This is useful for existing images.',
				],
			],
		],
		'icfw_log_options'  => [
			'heading'  => 'Log Options',
			'controls' => [
				'logs' => [
					'control' => 'checkbox',
					'label'   => 'Log errors for Failed Conversions',
					'summary' => 'Enable this option to log errors.',
				],
			],
		],
	];

	/**
	 * Form Notice.
	 *
	 * The Form notice containing the notice
	 * text displayed on save.
	 *
	 * @since 1.1.2
	 */
	public const FORM_NOTICE = [
		'label' => 'Settings Saved.',
	];
}
