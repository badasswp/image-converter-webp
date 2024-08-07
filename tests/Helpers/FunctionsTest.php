<?php

namespace ImageConverterWebP\Tests\Helpers;

use WP_Mock\Tools\TestCase;

require_once __DIR__ . '/../../inc/Helpers/functions.php';

/**
 * @covers icfw_get_settings
 */
class FunctionsTest extends TestCase {
	public function test_icfw_get_settings() {
		\WP_Mock::userFunction( 'get_option' )
			->once()
			->with( 'icfw', [] )
			->andReturn(
				[
					'upload' => true,
				]
			);

		$is_image_converted_on_upload = icfw_get_settings( 'upload', [] );

		$this->assertTrue( $is_image_converted_on_upload );
	}
}
