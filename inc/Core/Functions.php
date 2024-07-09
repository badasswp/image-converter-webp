<?php
/**
 * Utility Functions.
 *
 * This class holds the logic for registering
 * the plugin's admin page.
 *
 * @package ImageConverterWebP
 */

/**
 * Get all Images and associated WebPs.
 *
 * This function grabs all Image attachments and
 * associated WebP versions, if any.
 *
 * @since 1.0.2
 * @since 1.0.5 Optimise query using meta_query.
 * @since 1.1.0 Abstracted to Functions.
 *
 * @return mixed[]
 */
function get_webp_images(): array {
	$posts = get_posts(
		[
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'meta_query'     => [
				[
					'key'     => 'webp_img',
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
						'webp' => (string) ( get_post_meta( (int) $post->ID, 'webp_img', true ) ?? '' ),
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
