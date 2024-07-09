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

use WebPImageConverter\WebPImageConverter;

abstract class Service {
	/**
	 * Service classes.
	 *
	 * @since 1.1.0
	 *
	 * @var mixed[]
	 */
	private static array $services;

	/**
	 * Converter Instance.
	 *
	 * @since 1.0.0
	 *
	 * @var WebPImageConverter
	 */
	public WebPImageConverter $converter;

	/**
	 * Source Props.
	 *
	 * @since 1.0.0
	 *
	 * @var mixed[]
	 */
	public static $source;

	/**
	 * Register Singleton.
	 *
	 * This defines the generic method used by
	 * Service classes.
	 *
	 * @since 1.1.0
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
