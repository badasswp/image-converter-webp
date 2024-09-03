<?php
/**
 * Container class.
 *
 * This class is responsible for registering the
 * plugin services.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Core;

use ImageConverterWebP\Services\Admin;
use ImageConverterWebP\Services\MyAdmin;
use ImageConverterWebP\Services\PageLoad;
use ImageConverterWebP\Services\Main;
use ImageConverterWebP\Services\Logger;

use ImageConverterWebP\Interfaces\Kernel;

class Container implements Kernel {
	/**
	 * Services.
	 *
	 * @since 1.1.0
	 *
	 * @var mixed[]
	 */
	public static array $services = [];

	/**
	 * Prepare Singletons.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		static::$services = [
			Admin::class,
			Logger::class,
			Main::class,
			MyAdmin::class,
			PageLoad::class,
		];
	}

	/**
	 * Register Service.
	 *
	 * Establish singleton version for each Service
	 * concrete class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( static::$services as $service ) {
			( $service::get_instance() )->register();
		}
	}
}
