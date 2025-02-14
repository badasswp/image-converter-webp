<?php

namespace ImageConverterWebP\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Services\MetaData;

/**
 * @covers \ImageConverterWebP\Core\Converter::__construct
 * @covers \ImageConverterWebP\Services\MetaData::__construct
 * @covers \ImageConverterWebP\Services\MetaData::register
 * @covers \ImageConverterWebP\Services\MetaData::add_webp_meta_to_attachment
 * @covers \ImageConverterWebP\Services\MetaData::add_webp_for_scaled_image
 * @covers icfw_get_settings
 */
class MetaDataTest extends TestCase {
	public MetaData $metadata;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->metadata = new MetaData();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'icfw_convert', [ $this->metadata, 'add_webp_meta_to_attachment' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'icfw_convert', [ $this->metadata, 'add_webp_for_scaled_image' ], 10, 2 );

		$this->metadata->register();

		$this->assertConditionsMet();
	}

	public function test_add_webp_meta_to_attachment_bails_out_if_is_wp_error() {
		$webp = 'https://example.com/wp-content/uploads/2024/01/sample.webp';

		$options = [
			'logs' => true,
		];

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->with( $webp )
			->andReturn( true );

		$this->metadata->add_webp_meta_to_attachment( $webp, 1 );

		$this->assertConditionsMet();
	}

	public function test_add_webp_meta_to_attachment_updates_post_meta() {
		$webp = 'https://example.com/wp-content/uploads/2024/01/sample.webp';

		$options = [
			'logs' => true,
		];

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
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

		$this->metadata->add_webp_meta_to_attachment( $webp, 1 );

		$this->assertConditionsMet();
	}
}
