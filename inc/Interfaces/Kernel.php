<?php
/**
 * Kernel Interface
 *
 * Establish base methods for Concrete classes
 * used across plugin.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Interfaces;

interface Kernel {
	/**
	 * Register logic.
	 *
	 * @since 1.1.1
	 *
	 * @return void
	 */
	public function register(): void;
}
