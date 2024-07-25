<?php

namespace ImageConverterWebP\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Services\Admin;

/**
 * @covers \ImageConverterWebP\Core\Converter::__construct
 * @covers \ImageConverterWebP\Services\Admin::__construct
 * @covers \ImageConverterWebP\Services\Admin::register_icfw_options_menu
 * @covers \ImageConverterWebP\Services\Admin::register_icfw_settings
 */
class AdminTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->admin = new Admin();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register_icfw_options_menu() {
		\WP_Mock::userFunction( '__' )
			->twice()
			->with( 'Image Converter for WebP', 'image-converter-webp' )
			->andReturn( 'Image Converter for WebP' );

		\WP_Mock::userFunction( 'add_submenu_page' )
			->once()
			->with(
				'upload.php',
				'Image Converter for WebP',
				'Image Converter for WebP',
				'manage_options',
				'image-converter-webp',
				[ $this->admin, 'register_icfw_options_page' ]
			)
			->andReturn( null );

		$menu = $this->admin->register_icfw_options_menu();

		$this->assertNull( $menu );
		$this->assertConditionsMet();
	}

	public function test_register_icfw_settings_bails_out_if_any_nonce_settings_is_missing() {
		$_POST = [
			'webp_save_settings' => true,
		];

		$settings = $this->admin->register_icfw_settings();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}

	public function test_register_icfw_settings_bails_out_if_nonce_verification_fails() {
		$_POST = [
			'webp_save_settings'  => true,
			'webp_settings_nonce' => 'a8vbq3cg3sa',
		];

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
			->with( 'a8vbq3cg3sa', 'webp_settings_action' )
			->andReturn( false );

		$settings = $this->admin->register_icfw_settings();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}

	public function test_register_icfw_settings_passes() {
		$_POST = [
			'webp_save_settings'  => true,
			'webp_settings_nonce' => 'a8vbq3cg3sa',
			'quality'             => 75,
			'converter'           => 'gd',
			'upload'              => 1,
			'page_load'           => 1,
		];

		\WP_Mock::userFunction( 'wp_unslash' )
			->times( 5 )
			->with( 'a8vbq3cg3sa' )
			->andReturn( 'a8vbq3cg3sa' );

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 5 )
			->with( 'a8vbq3cg3sa' )
			->andReturn( 'a8vbq3cg3sa' );

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->times( 5 )
			->with( 'a8vbq3cg3sa', 'webp_settings_action' )
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
				]
			)
			->andReturn( null );

		\WP_Mock::userFunction(
			'sanitize_text_field',
			[
				'times'  => 4,
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		$settings = $this->admin->register_icfw_settings();

		$this->assertNull( $settings );
		$this->assertConditionsMet();
	}
}
