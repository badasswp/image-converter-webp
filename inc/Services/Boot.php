<?php
/**
 * Boot Service.
 *
 * Handle all setup logic before plugin is
 * fully capable.
 *
 * @package ImageConverterWebP
 */

 namespace ImageConverterWebP\Services;

use ImageConverterWebP\Abstracts\Service;
use ImageConverterWebP\Interfaces\Kernel;

class Boot extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.1.2
	 *
	 * @return void
	 */
	public function register(): void {
	}
}
