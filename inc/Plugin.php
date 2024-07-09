<?php
/**
 * Main Plugin class.
 *
 * This class represents the core of the plugin.
 * It initializes the plugin, manages the singleton instance.
 *
 * @package ImageConverterWebP
 */

namespace WebPImageConverter;

use ImageConverterWebP\Core\Container;

class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Plugin
	 */
	protected static $instance;

	/**
	 * Plugin File.
	 *
	 * @since 1.0.2
	 *
	 * @var string
	 */
	public static $file = __FILE__;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->converter = new WebPImageConverter();
	}

	/**
	 * Get Instance.
	 *
	 * Return singeleton instance for Plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function get_instance(): Plugin {
		if ( null === static::$instance ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run(): void {
		( new Container() )->register();
	}
}
