<?php
/**
 * Functions.
 *
 * This class holds reusable utility functions that can be
 * accessed across the plugin.
 *
 * @package ImageConverterWebP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get Plugin Options.
 *
 * As the name implies, this function simply grabs the
 * plugin options.
 *
 * @since 1.1.1
 *
 * @param string $option   Plugin option to be retrieved.
 * @param string $fallback Default return value.
 *
 * @return mixed
 */
function icfw_get_settings( $option, $fallback = '' ) {
	return get_option( 'icfw', [] )[ $option ] ?? $fallback;
}

/**
 * Get WebP absolute image.
 *
 * This function checks to see if a WebP absolute image exists and
 * proceeds to return same.
 *
 * @since 1.2.0
 *
 * @param int $image_id Image ID.
 * @return string
 */
function icfw_get_abs_image( $image_id ): string {
	// Bail out, if it is not an image.
	if ( ! wp_attachment_is_image( (int) $image_id ) ) {
		return '';
	}

	// Get default WP absolute image.
	$wp_abs_image = get_attached_file( $image_id );

	// Get WebP absolute image.
	$webp_abs_image = str_replace(
		sprintf( '.%s', pathinfo( $wp_abs_image, PATHINFO_EXTENSION ) ),
		'.webp',
		$wp_abs_image
	);

	// Bail out, if it does not exist.
	if ( ! file_exists( $webp_abs_image ) ) {
		return '';
	}

	return $webp_abs_image;
}

/**
 * Get all WebP Images.
 *
 * This function grabs all WebP images and associated
 * attachments meta data.
 *
 * @since 1.0.2
 * @since 1.0.5 Optimise query using meta_query.
 * @since 1.1.0 Moved to Functions file.
 * @since 1.1.1 Rename function to use `icfw` prefix.
 *
 * @return mixed[]
 */
function icfw_get_images(): array {
	$posts = get_posts(
		[
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'meta_query'     => [
				[
					'key'     => 'icfw_img',
					'compare' => 'EXISTS',
				],
			],
		]
	);

	if ( ! $posts ) {
		return [];
	}

	$images = array_filter(
		array_map(
			function ( $post ) {
				if ( $post instanceof \WP_Post && wp_attachment_is_image( $post ) ) {
					return [
						'guid' => $post->guid,
						'webp' => (string) ( get_post_meta( (int) $post->ID, 'icfw_img', true ) ?? '' ),
					];
				}
				return null;
			},
			$posts
		),
		function ( $item ) {
			return ! is_null( $item );
		}
	);

	return array_values( $images );
}
