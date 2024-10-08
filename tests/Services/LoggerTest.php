<?php

namespace ImageConverterWebP\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Services\Logger;

/**
 * @covers \ImageConverterWebP\Core\Converter::__construct
 * @covers \ImageConverterWebP\Services\Logger::__construct
 * @covers \ImageConverterWebP\Services\Logger::add_webp_meta_to_attachment
 * @covers icfw_get_settings
 */
class LoggerTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->logger = new Logger();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_add_webp_meta_to_attachment_does_not_log_error_if_log_option_is_not_enabled() {
		$webp = Mockery::mock( '\WP_Error' )->makePartial();

		$options = [
			'logs' => false,
		];

		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'icfw', [] )
			->andReturn( $options );

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->with( $webp )
			->andReturn( true );

		$this->logger->add_webp_meta_to_attachment( $webp, 1 );

		$this->assertConditionsMet();
	}

	public function test_add_webp_meta_to_attachment_logs_error_if_wp_error_is_true() {
		$webp = Mockery::mock( '\WP_Error' )->makePartial();

		$webp->shouldReceive( 'get_error_message' )
			->once()
			->andReturn( 'Fatal Error: sample.pdf is not an image...' );

		$options = [
			'logs' => true,
		];

		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'icfw', [] )
			->andReturn( $options );

		\WP_Mock::userFunction( 'is_wp_error' )
			->twice()
			->with( $webp )
			->andReturn( true );

		\WP_Mock::userFunction( 'wp_insert_post' )
			->once()
			->with(
				[
					'post_type'    => 'icfw_error',
					'post_title'   => 'WebP error log, ID - 1',
					'post_content' => 'Fatal Error: sample.pdf is not an image...',
					'post_status'  => 'publish',
				]
			)
			->andReturn( 100 );

		$this->logger->add_webp_meta_to_attachment( $webp, 1 );

		$this->assertConditionsMet();
	}

	public function test_add_webp_meta_to_attachment_updates_post_meta() {
		$webp = 'https://example.com/wp-content/uploads/2024/01/sample.webp';

		$options = [
			'logs' => true,
		];

		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'icfw', [] )
			->andReturn( $options );

		\WP_Mock::userFunction( 'is_wp_error' )
			->twice()
			->with( $webp )
			->andReturn( false );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'icfw_img', true )
			->andReturn( '' );

		\WP_Mock::userFunction( 'update_post_meta' )
			->once()
			->with( 1, 'icfw_img', 'https://example.com/wp-content/uploads/2024/01/sample.webp' )
			->andReturn( null );

		$this->logger->add_webp_meta_to_attachment( $webp, 1 );

		$this->assertConditionsMet();
	}
}
