<?php
/**
 * Service Abstraction.
 *
 * This abstraction defines the base logic from which all
 * Service classes are derived.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Abstracts;

abstract class Service {
	/**
	 * Service classes.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	private static array $services;

	/**
	 * Register Singleton.
	 *
	 * This defines the generic method used by
	 * Service classes.
	 *
	 * @since 1.0.0
	 *
	 * @return static
	 */
	public static function get_instance() {
		$class = get_called_class();

		if ( ! isset( static::$services[ $class ] ) ) {
			static::$services[ $class ] = new static();
		}

		return static::$services[ $class ];
	}

	abstract public function register();
}
