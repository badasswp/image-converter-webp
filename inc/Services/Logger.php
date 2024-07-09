<?php
/**
 * Logger Class.
 *
 * This class handles the logging of failed
 * image conversions.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Services;

use ImageConverterWebP\Abstracts\Service;

class Logger extends Service {
	/**
	 * Bind to WP.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'webp_img_convert', [ $this, 'add_webp_meta_to_attachment' ], 10, 2 );
	}

	/**
	 * Add WebP meta to Attachment.
	 *
	 * This is responsible for creating meta data or logging errors
	 * depending on the conversion result ($webp).
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Refactored to Logger class.
	 *
	 * @param string|\WP_Error $webp          WebP's relative path.
	 * @param int              $attachment_id Image ID.
	 *
	 * @return void
	 */
	public function add_webp_meta_to_attachment( $webp, $attachment_id ): void {
		if ( ! is_wp_error( $webp ) && ! get_post_meta( $attachment_id, 'webp_img', true ) ) {
			update_post_meta( $attachment_id, 'webp_img', $webp );
		}

		if ( is_wp_error( $webp ) ) {
			wp_insert_post(
				[
					'post_type'    => 'webp_error',
					'post_title'   => 'WebP error log, ID - ' . $attachment_id,
					'post_content' => (string) $webp->get_error_message(),
					'post_status'  => 'publish',
				]
			);
		}
	}
}