<?php
/**
 * MetaData Class.
 *
 * This class handles the creation of meta data
 * for WebP converted images.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Services;

use ImageConverterWebP\Abstracts\Service;
use ImageConverterWebP\Interfaces\Kernel;

class MetaData extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'icfw_convert', [ $this, 'add_webp_meta_to_attachment' ], 10, 2 );
	}

	/**
	 * Add WebP meta to Attachment.
	 *
	 * This is responsible for creating meta data or logging errors
	 * depending on the conversion result ($webp).
	 *
	 * @since 1.2.0
	 *
	 * @param string|\WP_Error $webp          WebP's relative path.
	 * @param int              $attachment_id Image ID.
	 *
	 * @return void
	 */
	public function add_webp_meta_to_attachment( $webp, $attachment_id ): void {
		// Bail out early, if \WP_Error.
		if ( is_wp_error( $webp ) ) {
			return;
		}

		// Save only if WebP image doesn't exist.
		if ( empty( get_post_meta( $attachment_id, 'icfw_img', true ) ) ) {
			update_post_meta( $attachment_id, 'icfw_img', $webp );
		}
	}
}