<?php

namespace ImageConverterWebP\Tests;

use Mockery;
use WP_Mock\Tools\TestCase;

use ImageConverterWebP\Plugin;
use ImageConverterWebP\Abstracts\Service;

use ImageConverterWebP\Services\Main;
use ImageConverterWebP\Services\Admin;
use ImageConverterWebP\Services\Logger;
use ImageConverterWebP\Services\PageLoad;

/**
 * @covers \ImageConverterWebP\Abstracts\Plugin::__construct
 * @covers \ImageConverterWebP\Abstracts\Plugin::run
 */
class PluginTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();

		$this->instance = Plugin::get_instance();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_run() {
		$this->services = [
			'Admin'    => Admin::get_instance(),
			'Logger'   => Logger::get_instance(),
			'Main'     => Main::get_instance(),
			'PageLoad' => PageLoad::get_instance(),
		];

		\WP_Mock::expectActionAdded(
			'init',
			[
				Service::$services['ImageConverterWebP\Services\Admin'],
				'register_icfw_translation',
			]
		);

		\WP_Mock::expectActionAdded(
			'admin_init',
			[
				Service::$services['ImageConverterWebP\Services\Admin'],
				'register_icfw_settings',
			]
		);

		\WP_Mock::expectActionAdded(
			'admin_menu',
			[
				Service::$services['ImageConverterWebP\Services\Admin'],
				'register_icfw_options_menu',
			]
		);

		\WP_Mock::expectActionAdded(
			'admin_enqueue_scripts',
			[
				Service::$services['ImageConverterWebP\Services\Admin'],
				'register_icfw_styles',
			]
		);

		\WP_Mock::expectActionAdded(
			'icfw_convert',
			[
				Service::$services['ImageConverterWebP\Services\Logger'],
				'add_webp_meta_to_attachment',
			],
			10,
			2
		);

		\WP_Mock::expectActionAdded(
			'add_attachment',
			[
				Service::$services['ImageConverterWebP\Services\Main'],
				'register_webp_img_creation',
			],
			10,
			1
		);

		\WP_Mock::expectFilterAdded(
			'wp_generate_attachment_metadata',
			[
				Service::$services['ImageConverterWebP\Services\Main'],
				'register_webp_img_srcset_creation',
			],
			10,
			3
		);

		\WP_Mock::expectActionAdded(
			'delete_attachment',
			[
				Service::$services['ImageConverterWebP\Services\Main'],
				'register_webp_img_deletion',
			],
			10,
			1
		);

		\WP_Mock::expectFilterAdded(
			'attachment_fields_to_edit',
			[
				Service::$services['ImageConverterWebP\Services\Main'],
				'register_webp_attachment_fields',
			],
			10,
			2
		);

		\WP_Mock::expectFilterAdded(
			'render_block',
			[
				Service::$services['ImageConverterWebP\Services\PageLoad'],
				'register_render_block',
			],
			20,
			2
		);

		\WP_Mock::expectFilterAdded(
			'wp_get_attachment_image',
			[
				Service::$services['ImageConverterWebP\Services\PageLoad'],
				'register_wp_get_attachment_image',
			],
			10,
			5
		);

		\WP_Mock::expectFilterAdded(
			'post_thumbnail_html',
			[
				Service::$services['ImageConverterWebP\Services\PageLoad'],
				'register_post_thumbnail_html',
			],
			10,
			5
		);

		$this->instance->run();

		$this->assertConditionsMet();
	}
}
