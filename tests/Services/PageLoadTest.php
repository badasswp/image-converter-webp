<?php

namespace ImageConverterWebP\Tests\Services;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Core\Converter;
use ImageConverterWebP\Services\PageLoad;

/**
 * @covers \ImageConverterWebP\Core\Converter::__construct
 * @covers \ImageConverterWebP\Services\PageLoad::__construct
 * @covers \ImageConverterWebP\Services\PageLoad::register_render_block
 * @covers \ImageConverterWebP\Services\PageLoad::register_wp_get_attachment_image
 * @covers \ImageConverterWebP\Services\PageLoad::register_post_thumbnail_html
 * @covers \ImageConverterWebP\Services\PageLoad::get_webp_image_html
 * @covers \ImageConverterWebP\Services\PageLoad::_get_webp_html
 */
class PageLoadTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->page_load = new PageLoad();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register_render_block_returns_empty_string() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$image = $page_load->register_render_block( '', [] );

		$this->assertSame( '', $image );
		$this->assertConditionsMet();
	}

	public function test_register_render_block_returns_img_html() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$page_load->shouldReceive( 'get_webp_image_html' )
			->once()
			->with( '<img src="sample.jpeg"/>' )
			->andReturn( '<img src="sample.webp"/>' );

		$image = $page_load->register_render_block( '<img src="sample.jpeg"/>', [] );

		$this->assertSame( '<img src="sample.webp"/>', $image );
		$this->assertConditionsMet();
	}

	public function test_register_wp_get_attachment_image_fails_and_returns_empty_string() {
		$image = $this->page_load->register_wp_get_attachment_image( '', 1, [], true, [] );

		$this->assertSame( '', $image );
		$this->assertConditionsMet();
	}

	public function test_register_wp_get_attachment_image_returns_img_html() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$page_load->shouldReceive( 'get_webp_image_html' )
			->once()
			->with( '<img src="sample.jpeg"/>', 1 )
			->andReturn( '<img src="sample.webp"/>' );

		\WP_Mock::onFilter( 'icfw_attachment_html' )
			->with(
				'<img src="sample.webp"/>',
				1
			)
			->reply(
				'<img src="sample.webp"/>'
			);

		$image = $page_load->register_wp_get_attachment_image( '<img src="sample.jpeg"/>', 1, [], true, [] );

		$this->assertSame( '<img src="sample.webp"/>', $image );
		$this->assertConditionsMet();
	}

	public function test_register_post_thumbnail_html_fails_and_returns_empty_string() {
		$image = $this->page_load->register_post_thumbnail_html( '', 1, [], true, [] );

		$this->assertSame( '', $image );
		$this->assertConditionsMet();
	}

	public function test_register_post_thumbnail_html_returns_img_html() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$page_load->shouldReceive( 'get_webp_image_html' )
			->once()
			->with( '<img src="sample.jpeg"/>', 2 )
			->andReturn( '<img src="sample.webp"/>' );

		\WP_Mock::onFilter( 'icfw_thumbnail_html' )
			->with(
				'<img src="sample.webp"/>',
				2
			)
			->reply(
				'<img src="sample.webp"/>'
			);

		$image = $page_load->register_post_thumbnail_html( '<img src="sample.jpeg"/>', 1, 2, [], [] );

		$this->assertSame( '<img src="sample.webp"/>', $image );
		$this->assertConditionsMet();
	}

	public function test_get_webp_image_html_returns_emtpy_image_if_empty() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$image = $page_load->get_webp_image_html( '' );

		$this->assertSame( '', $image );
		$this->assertConditionsMet();
	}

	public function test_get_webp_image_html_returns_html_if_no_image_in_html() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$image = $page_load->get_webp_image_html( '<div></div>' );

		$this->assertSame( '<div></div>', $image );
		$this->assertConditionsMet();
	}

	public function test_get_webp_image_html_returns_original_html_if_no_image_src_is_in_html() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$image = $page_load->get_webp_image_html( '<figure><img src=""/></figure>' );

		$this->assertSame( '<figure><img src=""/></figure>', $image );
		$this->assertConditionsMet();
	}

	public function test_get_webp_html_bails_out_and_returns_same_image_html() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$page_load->converter = Mockery::mock( Converter::class )->makePartial();
		$page_load->converter->shouldAllowMockingProtectedMethods();

		$error = Mockery::mock( \WP_Error::class )->makePartial();

		$page_load->converter->shouldReceive( 'convert' )
			->once()->with()
			->andReturn( $error );

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->with( $error )
			->andReturn( true );

		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'icfw', [] )
			->andReturn(
				[
					'page_load' => true,
				]
			);

		$img_html = $page_load->_get_webp_html( 'https://example.com/wp-content/uploads/2024/01/sample.pdf', '<img src="https://example.com/wp-content/uploads/2024/01/sample.pdf"/>', 1 );

		$this->assertSame( $img_html, '<img src="https://example.com/wp-content/uploads/2024/01/sample.pdf"/>' );
		$this->assertConditionsMet();
	}

	public function test_get_webp_html_returns_new_image_html() {
		$page_load = Mockery::mock( PageLoad::class )->makePartial();
		$page_load->shouldAllowMockingProtectedMethods();

		$page_load->converter = Mockery::mock( Converter::class )->makePartial();
		$page_load->converter->shouldAllowMockingProtectedMethods();

		$this->create_mock_image( __DIR__ . '/sample.webp' );
		$page_load->converter->abs_dest = __DIR__ . '/sample.webp';

		$error = Mockery::mock( \WP_Error::class )->makePartial();

		$this->source['url'] = 'https://example.com/wp-content/uploads/2024/01/sample.jpeg';

		$page_load->converter->shouldReceive( 'convert' )
			->once()->with()
			->andReturn( 'https://example.com/wp-content/uploads/2024/01/sample.webp' );

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->with( 'https://example.com/wp-content/uploads/2024/01/sample.webp' )
			->andReturn( false );

		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'icfw', [] )
			->andReturn(
				[
					'page_load' => true,
				]
			);

		$img_html = $page_load->_get_webp_html( 'https://example.com/wp-content/uploads/2024/01/sample.jpeg', '<img src="https://example.com/wp-content/uploads/2024/01/sample.jpeg"/>', 1 );

		$this->assertSame( $img_html, '<img src="https://example.com/wp-content/uploads/2024/01/sample.webp"/>' );
		$this->assertConditionsMet();

		$this->destroy_mock_image( __DIR__ . '/sample.webp' );
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
