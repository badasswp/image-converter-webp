<?php

namespace ImageConverterWebP\Tests\Admin;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Admin\Options;

/**
 * @covers \ImageConverterWebP\Admin\Options::get_form_page
 * @covers \ImageConverterWebP\Admin\Options::get_form_submit
 * @covers \ImageConverterWebP\Admin\Options::get_form_notice
 * @covers \ImageConverterWebP\Admin\Options::get_form_fields
 */
class OptionsTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_form_page() {
		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 2,
				'return' => function ( $text, $domain = 'image-converter-webp' ) {
					return $text;
				},
			]
		);

		$form_page = Options::get_form_page();

		$this->assertSame(
			$form_page,
			[
				'title'   => 'Image Converter for WebP',
				'summary' => 'Convert your WordPress JPG/PNG images to WebP formats during runtime.',
				'slug'    => 'image-converter-webp',
				'option'  => 'icfw',
			]
		);
	}

	public function test_get_form_submit() {
		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 2,
				'return' => function ( $text, $domain = 'image-converter-webp' ) {
					return $text;
				},
			]
		);

		$form_submit = Options::get_form_submit();

		$this->assertSame(
			$form_submit,
			[
				'heading' => 'Actions',
				'button'  => [
					'name'  => 'icfw_save_settings',
					'label' => 'Save Changes',
				],
				'nonce'   => [
					'name'   => 'icfw_settings_nonce',
					'action' => 'icfw_settings_action',
				],
			]
		);
	}

	public function test_get_form_fields() {
		\WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $text, $domain = 'image-converter-webp' ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text, $domain = 'image-converter-webp' ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction(
			'esc_attr__',
			[
				'return' => function ( $text, $domain = 'image-converter-webp' ) {
					return $text;
				},
			]
		);

		$form_fields = Options::get_form_fields();

		$this->assertSame(
			$form_fields,
			[
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
			]
		);
	}

	public function test_get_form_notice() {
		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 1,
				'return' => function ( $text, $domain = 'image-converter-webp' ) {
					return $text;
				},
			]
		);

		$form_notice = Options::get_form_notice();

		$this->assertSame(
			$form_notice,
			[
				'label' => 'Settings Saved.',
			]
		);
	}
}
