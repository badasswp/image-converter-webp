<?php

namespace ImageConverterWebP\Tests\Core;

use Mockery;
use WP_Mock\Tools\TestCase;
use ImageConverterWebP\Core\Container;
use ImageConverterWebP\Services\Admin;
use ImageConverterWebP\Services\PageLoad;
use ImageConverterWebP\Services\Main;
use ImageConverterWebP\Services\Logger;

/**
 * @covers Container
 */
class ContainerTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_container_contains_required_services() {
		$this->container = new Container();

		$this->assertTrue( in_array( Admin::class, Container::$services, true ) );
		$this->assertTrue( in_array( Logger::class, Container::$services, true ) );
		$this->assertTrue( in_array( Main::class, Container::$services, true ) );
		$this->assertTrue( in_array( PageLoad::class, Container::$services, true ) );
	}
}
