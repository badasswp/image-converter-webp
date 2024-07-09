<?php
/**
 * Settings Page.
 *
 * This template is responsible for the Settings
 * page in the plugin.
 *
 * @package ImageConverterWebP
 * @since   1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<section class="wrap">
	<h1>
		<span><?php echo esc_html__( 'Image Converter for WebP', 'image-converter-webp' ); ?></span>
	</h1>

	<p>
		<?php echo esc_html__( 'Convert your WordPress JPG/PNG images to WebP formats during runtime.', 'image-converter-webp' ); ?>
	</p>

	<form class="badasswp-form" method="POST" action="<?php echo esc_url( sanitize_text_field( $_SERVER['REQUEST_URI'] ) ); ?>">
		<!-- Form Notice -->
		<?php if ( isset( $_POST['webp_save_settings'] ) ) : ?>
		<div class="badasswp-form-notice">
			<span><?php echo esc_html__( 'Settings Saved.', 'image-converter-webp' ); ?></span>
		</div>
		<?php endif ?>

		<div class="badasswp-form-main">
			<!-- Form Group -->
			<div class="badasswp-form-group">
				<p class="badasswp-form-group-block">
					<?php echo esc_html__( 'Conversion Options', 'image-converter-webp' ); ?>
				</p>
				<p class="badasswp-form-group-block size-50">
					<label>
						<?php echo esc_html__( 'Conversion Quality', 'image-converter-webp' ); ?> (%)
					</label>
					<input
						type="text"
						name="quality"
						min="0"
						max="100"
						placeholder="20"
						value="<?php echo esc_attr( get_option( 'webp_img_converter', [] )['quality'] ?? '' ); ?>"
					/>
					<em>
						<?php echo esc_html__( 'e.g. 75', 'image-converter-webp' ); ?>
					</em>
				</p>
				<p class="badasswp-form-group-block size-50">
					<label>
						<?php echo esc_html__( 'WebP Engine', 'image-converter-webp' ); ?>
					</label>
					<select name="converter">
					<?php
					$engines = [
						'gd'      => 'GD',
						'cwebp'   => 'CWebP',
						'ffmpeg'  => 'FFMpeg',
						'imagick' => 'Imagick',
						'gmagick' => 'Gmagick',
					];

					$engine = get_option( 'webp_img_converter', [] )['converter'] ?? '';

					foreach ( $engines as $key => $value ) {
						$selected = $engine === $key ? ' selected' : '';
						printf(
							'<option value="%1$s"%3$s>%2$s</option>',
							esc_attr( $key ),
							esc_html( $value ),
							esc_html( $selected ),
						);
					}
					?>
					</select>
					<em>
						<?php echo esc_html__( 'e.g. Imagick', 'image-converter-webp' ); ?>
					</em>
				</p>
			</div>

			<!-- Form Group -->
			<div class="badasswp-form-group">
				<p class="badasswp-form-group-block">
					<?php echo esc_html__( 'Image Options', 'image-converter-webp' ); ?>
				</p>
				<p class="badasswp-form-group-block size-50">
					<label>
						<?php echo esc_html__( 'Convert Images on Upload', 'image-converter-webp' ); ?>
					</label>
					<input
						name="upload"
						<?php esc_attr_e( ! empty( get_option( 'webp_img_converter', [] )['upload'] ?? '' ) ? 'checked' : '' ); ?>
						type="checkbox"
					/>
					<em>
						<?php echo esc_html__( 'This is useful for new images.', 'image-converter-webp' ); ?>
					</em>
				</p>
				<p class="badasswp-form-group-block size-50">
					<label>
						<?php echo esc_html__( 'Convert Images on Page Load', 'image-converter-webp' ); ?>
					</label>
					<input
						name="page_load"
						<?php esc_attr_e( ! empty( get_option( 'webp_img_converter', [] )['page_load'] ?? '' ) ? 'checked' : '' ); ?>
						type="checkbox"
					/>
					<em>
						<?php echo esc_html__( 'This is useful for existing images.', 'image-converter-webp' ); ?>
					</em>
				</p>
			</div>
		</div>

		<div class="badasswp-form-submit">
			<!-- Form Group -->
			<div class="badasswp-form-group">
				<p class="badasswp-form-group-block">
					<label>
						<?php echo esc_html__( 'Actions', 'image-converter-webp' ); ?>
					</label>
				</p>
				<p class="badasswp-form-group-block">
					<button name="webp_save_settings" type="submit" class="button button-primary">
						<span>
							<?php echo esc_html__( 'Save Changes', 'image-converter-webp' ); ?>
						</span>
					</button>
				</p>
				<?php wp_nonce_field( 'webp_settings_action', 'webp_settings_nonce' ); ?>
			</div>
		</div>
	</form>
</section>
