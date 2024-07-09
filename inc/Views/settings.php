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

	<style type="text/css">
		.badasswp-form {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
		}

		.badasswp-form-main {
			width: calc(70% - 10px);
			display: flex;
			flex-wrap: wrap;
			flex-direction: column;
			gap: 20px;
		}

		.badasswp-form-submit {
			width: calc(30% - 10px);
		}

		.badasswp-form-group {
			background: #FFF;
			border: 1px solid #c3c4c7;
			display: flex;
			flex-wrap: wrap;
		}

		.badasswp-form-group-block {
			margin: 0;
			padding: 12.5px;
			box-sizing: border-box;
			border-bottom: 1px solid #e2e4e7;
			width: 100%;
			display: flex;
			flex-direction: column;
			gap: 6.5px;
		}

		.badasswp-form-group-block label {
			font-weight: bold;
			display: block;
		}

		.badasswp-form-group-block em {
			display: inline-block;
			margin-top: 3.5px;
			color: #888;
		}

		.badasswp-form-group-block select {
			max-width: 100%;
		}

		.badasswp-form-group-block input[type=checkbox] {
			width: 60px;
			height: 30px;
			border-radius: 100px;
			display: inline-block;
			border: 1px solid #8c8f94;
			position: relative;
			box-shadow: none;
		}

		.badasswp-form-group-block input[type=checkbox]::before {
			width: 20px;
			height: 20px;
			background: #D0D1D7;
			border-radius: 100%;
			display: block;
			content: '';
			position: absolute;
			top: 4px;
			left: 4px;
			margin: 0;
			transition: all 0.3s;
		}

		.badasswp-form-group-block input[type=checkbox]:checked {
			background: #005AE0;
			border: 1px solid #005AE0;
		}

		.badasswp-form-group-block input[type=checkbox]:checked::before {
			background: #FFF;
			border-radius: 100%;
			display: block;
			content: '';
			position: absolute;
			top: 4px;
			left: 34px;
			margin: 0;
		}

		.badasswp-form-notice {
			width: 100%;
			border: 1px solid #c3c4c7;
			border-left: 4px solid #00a32a;
			background: #FFF;
			padding: 12.5px;
			font-weight: bold;
			box-sizing: border-box;
		}

		.size-50 {
			width: calc(50% - 1px);
			border-right: 1px solid #e2e4e7;
		}

		.size-50 + .size-50 {
			border-right: none;
		}

		@media only screen and (max-width: 786px) {
			.badasswp-form {
				gap: 15px;
			}

			.badasswp-form-main,
			.badasswp-form-submit {
				width: 100%;
			}

			.badasswp-form-group-block {
				width: 100%;
				border-right: none;
			}
		}
	</style>

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
						name="delete"
						<?php esc_attr_e( ! empty( get_option( 'webp_img_converter', [] )['quality'] ?? '' ) ? 'checked' : '' ); ?>
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
						name="delete"
						<?php esc_attr_e( ! empty( get_option( 'webp_img_converter', [] )['quality'] ?? '' ) ? 'checked' : '' ); ?>
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
