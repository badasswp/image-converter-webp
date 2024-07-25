<?php
/**
 * Plugin Name: Image Converter for WebP
 * Plugin URI:  https://github.com/badasswp/image-converter-webp
 * Description: Convert your WordPress JPG/PNG images to WebP formats during runtime.
 * Version:     1.1.0
 * Author:      badasswp
 * Author URI:  https://github.com/badasswp
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: image-converter-webp
 * Domain Path: /languages
 *
 * @package ImageConverterWebP
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ICFW_AUTOLOAD', __DIR__ . '/vendor/autoload.php' );

// Bail out, if Composer is NOT installed.
if ( ! file_exists( ICFW_AUTOLOAD ) ) {
	add_action(
		'admin_notices',
		function () {
			printf(
				/* translators: Autoload file path. */
				esc_html__( 'Fatal Error: %s file does not exist, please check if Composer is installed!', 'image-converter-webp' ),
				esc_html( ICFW_AUTOLOAD )
			);
		}
	);

	return;
}

// Autoload classes.
require_once ICFW_AUTOLOAD;

// Get instance and Run plugin.
( \ImageConverterWebP\Plugin::get_instance() )->run();
