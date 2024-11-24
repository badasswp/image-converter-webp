<?php

namespace ImageConverterWebP\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Services\Logger;

/**
 * @covers \ImageConverterWebP\Core\Converter::__construct
 * @covers \ImageConverterWebP\Services\Logger::__construct
 * @covers \ImageConverterWebP\Services\Logger::add_logs_for_webp_conversions
 * @covers icfw_get_settings
 */
class LoggerTest extends TestCase {
	public Logger $logger;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->logger = new Logger();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'icfw_convert', [ $this->logger, 'add_logs_for_webp_conversions' ], 10, 2 );

		$this->logger->register();

		$this->assertConditionsMet();
	}

	public function test_add_logs_for_webp_conversions_does_not_log_error_if_log_option_is_not_enabled() {
		$webp = Mockery::mock( '\WP_Error' )->makePartial();

		$options = [
			'logs' => false,
		];

		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'icfw', [] )
			->andReturn( $options );

		$this->logger->add_logs_for_webp_conversions( $webp, 1 );

		$this->assertConditionsMet();
	}

	public function test_add_logs_for_webp_conversions_logs_error_if_wp_error_is_true() {
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
			->once()
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

		$this->logger->add_logs_for_webp_conversions( $webp, 1 );

		$this->assertConditionsMet();
	}
}
