<?php

namespace ImageConverterWebP\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Services\Admin;

/**
 * @covers \ImageConverterWebP\Core\Converter::__construct
 * @covers \ImageConverterWebP\Services\Admin::__construct
 * @covers \ImageConverterWebP\Services\Admin::register_options_menu
 * @covers \ImageConverterWebP\Services\Admin::register_options_init
 * @covers \ImageConverterWebP\Admin\Options::__callStatic
 */
class AdminTest extends TestCase {
	public Admin $admin;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->admin = new Admin();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register_options_menu() {
		\WP_Mock::userFunction(
			'esc_html__',
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

		\WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction( 'add_submenu_page' )
			->once()
			->with(
				'upload.php',
				'Image Converter for WebP',
				'Image Converter for WebP',
				'manage_options',
				'image-converter-webp',
				[ $this->admin, 'register_options_page' ]
			)
			->andReturn( null );

		$menu = $this->admin->register_options_menu();

		$this->assertNull( $menu );
		$this->assertConditionsMet();
	}

	public function test_register_options_init_bails_out_if_any_nonce_settings_is_missing() {
		\WP_Mock::userFunction(
			'esc_html__',
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

		\WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		$_POST = [
			'icfw_save_settings' => true,
		];

		$settings = $this->admin->register_options_init();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}

	public function test_register_options_init_bails_out_if_nonce_verification_fails() {
		$_POST = [
			'icfw_save_settings'  => true,
			'icfw_settings_nonce' => 'a8vbq3cg3sa',
		];

		\WP_Mock::userFunction(
			'esc_html__',
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

		\WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction( 'wp_unslash' )
			->times( 1 )
			->with( 'a8vbq3cg3sa' )
			->andReturn( 'a8vbq3cg3sa' );

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 1 )
			->with( 'a8vbq3cg3sa' )
			->andReturn( 'a8vbq3cg3sa' );

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->once()
			->with( 'a8vbq3cg3sa', 'icfw_settings_action' )
			->andReturn( false );

		$settings = $this->admin->register_options_init();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}

	public function test_register_options_init_passes() {
		\WP_Mock::userFunction(
			'esc_html__',
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

		\WP_Mock::userFunction(
			'esc_attr',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		$_POST = [
			'icfw_save_settings'  => true,
			'icfw_settings_nonce' => 'a8vbq3cg3sa',
			'quality'             => 75,
			'converter'           => 'gd',
			'upload'              => 1,
			'page_load'           => 1,
			'logs'                => 1,
		];

		\WP_Mock::userFunction(
			'wp_unslash',
			[
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 1 )
			->with( 'a8vbq3cg3sa' )
			->andReturn( 'a8vbq3cg3sa' );

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->times( 1 )
			->with( 'a8vbq3cg3sa', 'icfw_settings_action' )
			->andReturn( true );

		\WP_Mock::userFunction( 'update_option' )
			->once()
			->with(
				'icfw',
				[
					'quality'   => 75,
					'converter' => 'gd',
					'upload'    => 1,
					'page_load' => 1,
					'logs'      => 1,
				]
			)
			->andReturn( null );

		\WP_Mock::userFunction(
			'sanitize_text_field',
			[
				'times'  => 5,
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		$settings = $this->admin->register_options_init();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}
}
