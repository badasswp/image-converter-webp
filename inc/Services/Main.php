<?php
/**
 * Main Class.
 *
 * This class is responsible for the generation of
 * WebP images during upload.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Services;

use ImageConverterWebP\Abstracts\Service;
use ImageConverterWebP\Interfaces\Kernel;

class Main extends Service implements Kernel {
	/**
	 * Bind to WP.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'add_attachment', [ $this, 'register_webp_img_creation' ], 10, 1 );
		add_filter( 'wp_generate_attachment_metadata', [ $this, 'register_webp_img_srcset_creation' ], 10, 3 );
		add_action( 'delete_attachment', [ $this, 'register_webp_img_deletion' ], 10, 1 );
		add_filter( 'attachment_fields_to_edit', [ $this, 'register_webp_attachment_fields' ], 10, 2 );
		add_filter( 'wp_prepare_attachment_for_js', [ $this, 'show_webp_images_on_wp_media_modal' ], 10, 3 );
	}

	/**
	 * Generate WebP on add_attachment.
	 *
	 * This generates WebP images when users add new images
	 * to the WP media.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Moved to Main class.
	 *
	 * @param  int $attachment_id Image ID.
	 * @return void
	 */
	public function register_webp_img_creation( $attachment_id ): void {
		// Get source props.
		$this->source = [
			'id'  => (int) $attachment_id,
			'url' => (string) wp_get_attachment_url( $attachment_id ),
		];

		// Ensure this is allowed.
		if ( icfw_get_settings( 'upload' ) ) {
			$webp = $this->converter->convert();
		}
	}

	/**
	 * Generate WebP images for metadata.
	 *
	 * Get WebP images for the various sizes generated by WP
	 * when the user adds a new image to the WP media.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Moved to Main class.
	 *
	 * @param mixed[] $metadata      An array of attachment meta data.
	 * @param int     $attachment_id Attachment ID.
	 * @param string  $context       Additional context. Can be 'create' or 'update'.
	 *
	 * @return mixed[]
	 */
	public function register_webp_img_srcset_creation( $metadata, $attachment_id, $context ): array {
		// Get parent image URL.
		$img_url = (string) wp_get_attachment_image_url( $attachment_id );

		// Get image path prefix.
		$img_url_prefix = substr( $img_url, 0, (int) strrpos( $img_url, '/' ) );

		// Convert srcset images.
		foreach ( ( $metadata['sizes'] ?? [] ) as $img ) {
			$this->source = [
				'id'  => (int) $attachment_id,
				'url' => trailingslashit( $img_url_prefix ) . $img['file'],
			];

			// Ensure this is allowed.
			if ( icfw_get_settings( 'upload' ) ) {
				$this->converter->convert();
			}
		}

		return $metadata;
	}

	/**
	 * Remove WebP images.
	 *
	 * This method removes dynamically generated
	 * WebP image versions when the main image is deleted.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Moved to Main class.
	 *
	 * @param int $attachment_id Attachment ID.
	 * @return void
	 */
	public function register_webp_img_deletion( $attachment_id ): void {
		if ( ! wp_attachment_is_image( $attachment_id ) ) {
			return;
		}

		// Get absolute path for main image.
		$main_image = (string) get_attached_file( $attachment_id );

		// Ensure image exists before proceeding.
		if ( $main_image ) {
			$extension  = '.' . pathinfo( $main_image, PATHINFO_EXTENSION );
			$webp_image = str_replace( $extension, '.webp', $main_image );

			if ( file_exists( $webp_image ) ) {
				unlink( $webp_image );

				/**
				 * Fires after WebP Image has been deleted.
				 *
				 * @since 1.0.2
				 * @since 1.1.1 Rename hook to use `icfw` prefix.
				 *
				 * @param string $webp_image    Absolute path to WebP image.
				 * @param int    $attachment_id Image ID.
				 *
				 * @return void
				 */
				do_action( 'icfw_delete', $webp_image, $attachment_id );
			}
		}

		// Get attachment metadata.
		$metadata = wp_get_attachment_metadata( $attachment_id );

		// Remove metadata using main image absolute path.
		foreach ( $metadata['sizes'] ?? [] as $img ) {
			// Get absolute path of metadata image.
			$img_url_prefix = substr( $main_image, 0, (int) strrpos( $main_image, '/' ) );
			$metadata_image = trailingslashit( $img_url_prefix ) . $img['file'];

			// Ensure image exists before proceeding.
			if ( $metadata_image ) {
				// Get WebP version of metadata image.
				$metadata_extension  = '.' . pathinfo( $metadata_image, PATHINFO_EXTENSION );
				$webp_metadata_image = str_replace( $metadata_extension, '.webp', $metadata_image );

				if ( file_exists( $webp_metadata_image ) ) {
					unlink( $webp_metadata_image );

					/**
					 * Fires after WebP Metadata Image has been deleted.
					 *
					 * @since 1.0.2
					 * @since 1.1.1 Rename hook to use `icfw` prefix.
					 *
					 * @param string $webp_metadata_image Absolute path to WebP image.
					 * @param int    $attachment_id       Image ID.
					 *
					 * @return void
					 */
					do_action( 'icfw_metadata_delete', $webp_metadata_image, $attachment_id );
				}
			}
		}
	}

	/**
	 * Add attachment fields for WebP image.
	 *
	 * As the name implies, this logic creates a WebP field label
	 * in the WP attachment modal so users can see the path of the image's
	 * generated WebP version.
	 *
	 * @since 1.0.2
	 * @since 1.1.0 Moved to Main class.
	 *
	 * @param mixed[]  $fields Fields Array.
	 * @param \WP_Post $post   WP Post.
	 *
	 * @return mixed[]
	 */
	public function register_webp_attachment_fields( $fields, $post ): array {
		$webp_img = get_post_meta( $post->ID, 'icfw_img', true ) ?? '';

		$fields['icfw_img'] = [
			'input' => 'text',
			'value' => (string) ( is_array( $webp_img ) ? '' : $webp_img ),
			'label' => __( 'WebP Image', 'image-converter-webp' ),
			'helps' => __( 'WebP Image generated by Image Converter for WebP.', 'image-converter-webp' ),
		];

		return $fields;
	}

	/**
	 * Show WebP Images.
	 *
	 * This displays the WebP converted images on the WP
	 * Medial modal window.
	 *
	 * @since 1.2.0
	 *
	 * @param mixed[]       $metadata   Image Attachment data to be sent to JS.
	 * @param \WP_Post      $attachment Attachment ID or object.
	 * @param mixed[]|false $meta       Array of attachment meta data, or false if there is none.
	 *
	 * @return mixed[]
	 */
	public function show_webp_images_on_wp_media_modal( $metadata, $attachment, $meta ): array {
		$webp_image = get_post_meta( $attachment->ID, 'icfw_img', true );

		// Bail out, if it is not an image.
		if ( ! wp_attachment_is_image( $attachment->ID ) ) {
			return $metadata;
		}

		// Bail out, if WebP image does NOT exist.
		if ( ! file_exists( icfw_get_abs_image( $attachment->ID ) ) ) {
			return $metadata;
		}

		$metadata['sizes']['full']['url'] = $webp_image;

		return $this->get_webp_metadata( $metadata );
	}

	/**
	 * Get WebP Metadata.
	 *
	 * Mutate Meta data array and get the WebP Images
	 * for all Image meta data.
	 *
	 * @since 1.2.0
	 *
	 * @param mixed[] $metadata Meta data array.
	 * @return mixed[]
	 */
	protected function get_webp_metadata( $metadata ): array {
		$types = [ 'thumbnail', 'medium', 'large' ];

		foreach ( $types as $type ) {
			$metadata['sizes'][ $type ]['url'] = icfw_get_equivalent( $metadata['sizes'][ $type ]['url'] );
		}

		return $metadata;
	}
}
