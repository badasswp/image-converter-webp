<?php
/**
 * Functions.
 *
 * This class holds reusable utility functions that can be
 * accessed across the plugin.
 *
 * @package ImageConverterWebP
 */

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
