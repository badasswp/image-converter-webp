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
	 * @since 1.1.2
	 */
	public const FORM_PAGE = [
		'title'   => 'Image Converter for WebP',
		'summary' => 'Convert your WordPress JPG/PNG images to WebP formats during runtime.',
	];

	/**
	 * Form Submit.
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
	 * @since 1.1.2
	 */
	public const FORM_NOTICE = [
		'label' => 'Settings Saved.',
	];
}
