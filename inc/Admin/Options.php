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
	 * Form Fields.
	 *
	 * Store all the form values needed to generate
	 * the form successfully.
	 *
	 * @since 1.1.2
	 */
	public const FORM = [
		'submit' => [],
		'notice' => [],
		'fields' => [
			'icfw_conv_options' => [
				'label'    => 'Conversion Options',
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
				'label'    => 'Image Options',
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
				'label'    => 'Log Options',
				'controls' => [
					'logs' => [
						'control' => 'checkbox',
						'label'   => 'Log errors for Failed Conversions',
						'summary' => 'Enable this option to log errors.',
					],
				],
			],
		]
	];
}
