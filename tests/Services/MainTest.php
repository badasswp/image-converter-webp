<?php

namespace ImageConverterWebP\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Services\Main;
use ImageConverterWebP\Core\Converter;

/**
 * @covers \ImageConverterWebP\Core\Converter::__construct
 * @covers \ImageConverterWebP\Services\Main::__construct
 * @covers \ImageConverterWebP\Services\Main::generate_webp_image
 * @covers \ImageConverterWebP\Services\Main::generate_webp_srcset_images
 */
class MainTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->main = new Main();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_generate_webp_image_satisfies_conditions() {
		$converter = Mockery::mock( Converter::class )->makePartial();
		$converter->shouldAllowMockingProtectedMethods();

		$main = Mockery::mock( Main::class )->makePartial();
		$main->shouldAllowMockingProtectedMethods();
		$main->converter = $converter;

		$main->source = [
			'id'  => 1,
			'url' => 'https://example.com/wp-content/uploads/2024/01/sample.jpeg',
		];

		\WP_Mock::userFunction( 'wp_get_attachment_url' )
			->once()
			->with( 1 )
			->andReturn( 'https://example.com/wp-content/uploads/2024/01/sample.jpeg' );

		$main->converter->shouldReceive( 'convert' )
			->once()
			->andReturn( 'https://example.com/wp-content/uploads/2024/01/sample.webp' );

		$main->generate_webp_image( 1 );

		$this->assertConditionsMet();
	}

	public function test_generate_webp_srcset_images() {
		$main = Mockery::mock( Main::class )->makePartial();
		$main->shouldAllowMockingProtectedMethods();

		$main->converter = Mockery::mock( Converter::class )->makePartial();
		$main->converter->shouldAllowMockingProtectedMethods();

		$data = [
			'sizes' => [
				[
					'file' => 'sample1.jpeg',
				],
				[
					'file' => 'sample2.jpeg',
				],
				[
					'file' => 'sample3.jpeg',
				],
			],
		];

		\WP_Mock::userFunction( 'wp_get_attachment_image_url' )
			->once()
			->with( 1 )
			->andReturn( 'https://example.com/wp-content/uploads/2024/01/sample.jpeg' );

		\WP_Mock::userFunction( 'trailingslashit' )
			->times( 3 )
			->with( 'https://example.com/wp-content/uploads/2024/01' )
			->andReturn( 'https://example.com/wp-content/uploads/2024/01/' );

		$main->converter->shouldReceive( 'convert' )
			->times( 3 );

		$srcset = $main->generate_webp_srcset_images( $data, 1, 'create' );

		$this->assertConditionsMet();
	}

	public function test_delete_webp_images_fails_if_not_image() {
		$main = Mockery::mock( Main::class )->makePartial();
		$main->shouldAllowMockingProtectedMethods();

		\WP_Mock::userFunction( 'wp_attachment_is_image' )
			->once()
			->with( 1 )
			->andReturn( false );

		$image = $main->delete_webp_images( 1 );

		$this->assertConditionsMet();
	}

	public function test_delete_webp_images_bails_if_no_parent_image_abs_path_or_metadata_is_found() {
		$main = Mockery::mock( Main::class )->makePartial();
		$main->shouldAllowMockingProtectedMethods();

		\WP_Mock::userFunction( 'wp_attachment_is_image' )
			->once()
			->with( 1 )
			->andReturn( true );

		\WP_Mock::userFunction( 'get_attached_file' )
			->once()
			->with( 1 )
			->andReturn( '' );

		\WP_Mock::userFunction( 'wp_get_attachment_metadata' )
			->once()
			->with( 1 )
			->andReturn( [] );

		$image = $main->delete_webp_images( 1 );

		$this->assertConditionsMet();
	}

	public function test_delete_webp_images_removes_parent_webp_image() {
		$main = Mockery::mock( Main::class )->makePartial();
		$main->shouldAllowMockingProtectedMethods();

		\WP_Mock::userFunction( 'wp_attachment_is_image' )
			->once()
			->with( 1 )
			->andReturn( true );

		\WP_Mock::userFunction( 'get_attached_file' )
			->once()
			->with( 1 )
			->andReturn( __DIR__ . '/sample.jpeg' );

		\WP_Mock::expectAction( 'webp_img_delete', __DIR__ . '/sample.webp', 1 );

		\WP_Mock::userFunction( 'wp_get_attachment_metadata' )
			->once()
			->with( 1 )
			->andReturn( [] );

		// Create Mock Images.
		$this->create_mock_image( __DIR__ . '/sample.webp' );

		$image = $main->delete_webp_images( 1 );

		$this->assertConditionsMet();
	}

	public function test_delete_webp_images_removes_webp_metadata_image() {
		$main = Mockery::mock( Main::class )->makePartial();
		$main->shouldAllowMockingProtectedMethods();

		\WP_Mock::userFunction( 'wp_attachment_is_image' )
			->once()
			->with( 1 )
			->andReturn( true );

		\WP_Mock::userFunction( 'get_attached_file' )
			->once()
			->with( 1 )
			->andReturn( __DIR__ . '/sample.jpeg' );

		\WP_Mock::expectAction( 'webp_img_delete', __DIR__ . '/sample.webp', 1 );

		\WP_Mock::userFunction(
			'trailingslashit',
			[
				'times'  => 3,
				'return' => function ( $text ) {
					return $text . '/';
				},
			]
		);

		\WP_Mock::userFunction( 'wp_get_attachment_metadata' )
			->once()
			->with( 1 )
			->andReturn(
				[
					'sizes' => [
						[
							'file' => 'sample1.jpeg',
						],
						[
							'file' => 'sample2.jpeg',
						],
						[
							'file' => 'sample3.jpeg',
						],
					],
				]
			);

		\WP_Mock::expectAction( 'webp_img_metadata_delete', __DIR__ . '/sample1.webp', 1 );
		\WP_Mock::expectAction( 'webp_img_metadata_delete', __DIR__ . '/sample2.webp', 1 );
		\WP_Mock::expectAction( 'webp_img_metadata_delete', __DIR__ . '/sample3.webp', 1 );

		// Create Mock Images.
		$this->create_mock_image( __DIR__ . '/sample.webp' );
		$this->create_mock_image( __DIR__ . '/sample1.webp' );
		$this->create_mock_image( __DIR__ . '/sample2.webp' );
		$this->create_mock_image( __DIR__ . '/sample3.webp' );

		$image = $main->delete_webp_images( 1 );

		$this->assertConditionsMet();
	}

	public function create_mock_image( $image_file_name ) {
		// Create a blank image.
		$width  = 400;
		$height = 200;
		$image  = imagecreatetruecolor( $width, $height );

		// Set background color.
		$bg_color = imagecolorallocate( $image, 255, 255, 255 );
		imagefill( $image, 0, 0, $bg_color );
		imagejpeg( $image, $image_file_name );
	}

	public function destroy_mock_image( $image_file_name ) {
		if ( file_exists( $image_file_name ) ) {
			unlink( $image_file_name );
		}
	}
}
