<?php
/**
 * Main Plugin class.
 *
 * This class represents the core of the plugin.
 * It initializes the plugin, manages the singleton instance.
 *
 * @package ImageConverterWebP
 */

namespace WebPImageConverter;

use DOMDocument;
use WebPImageConverter\WebPImageConverter;

class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Plugin
	 */
	protected static $instance;

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
	 * Plugin File.
	 *
	 * @since 1.0.2
	 *
	 * @var string
	 */
	public static $file = __FILE__;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->converter = new WebPImageConverter();
	}

	/**
	 * Get Instance.
	 *
	 * Return singeleton instance for Plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function get_instance(): Plugin {
		if ( null === static::$instance ) {
			static::$instance = new self();
		}

		return static::$instance;
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run(): void {
		add_filter( 'attachment_fields_to_edit', [ $this, 'add_webp_attachment_fields' ], 10, 2 );
	}

	/**
	 * Get all Images and associated WebPs.
	 *
	 * This function grabs all Image attachments and
	 * associated WebP versions, if any.
	 *
	 * @since 1.0.2
	 * @since 1.0.5 Optimise query using meta_query.
	 *
	 * @return mixed[]
	 */
	protected function get_webp_images(): array {
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
}
