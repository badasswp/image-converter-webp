<?php
/**
 * Form Class.
 *
 * This utility class is responsible for generating
 * the Admin page form.
 *
 * @package ImageConverterWebP
 */

namespace ImageConverterWebP\Admin;

class Form {
	/**
	 * Set up Constructor.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $options Admin Options.
	 */
	public function __construct( $options ) {
		$this->options = $options;
	}

	/**
	 * Get Form.
	 *
	 * This method is responsible for getting
	 * the Form.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public function get_form(): string {
		$form = [
			'form_action' => $this->get_form_action(),
			'form_main'   => $this->get_form_main(),
			'form_submit' => $this->get_form_submit(),
		];

		return vsprintf(
			'<form class="badasswp-form" method="POST" action="%s">
				<div class="badasswp-form-main">%s</div>
				<div class="badasswp-form-submit">%s</div>
			</form>',
			$form
		);
	}

	/**
	 * Get Form Action.
	 *
	 * This method is responsible for getting the
	 * Form Action.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public function get_form_action(): string {
		return esc_url(
			sanitize_text_field( $_SERVER['REQUEST_URI'] )
		);
	}

	/**
	 * Get Form Main.
	 *
	 * This method is responsible for obtaining
	 * the complete form.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public function get_form_main(): string {
		$form = '';

		foreach ( $this->options as $option ) {
			$form .= $this->get_form_group( $option );
		}

		return $form;
	}

	/**
	 * Get Form Group.
	 *
	 * This method is responsible for obtaining
	 * a single form group.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $arg Form group array.
	 * @return string
	 */
	public function get_form_group( $arg ): string {
		$form_group = '';

		foreach ( $arg as $key => $value ) {
			switch ( $key ) {
				case 'label':
					$form_group .= sprintf(
						'<p class="badasswp-form-group-block">%s</p>',
						esc_html__(
							$value,
							'image-converter-webp'
						),
					);
					break;

				default:
					foreach ( $value as $name => $control ) {
						$group_block = [
							'label'   => esc_html__(
								$control['label'] ?? '',
								'image-converter-webp'
							),
							'control' => __(
								$this->get_form_control( $control, $name ),
								'image-converter-webp'
							),
							'summary' => esc_html__(
								$control['summary'] ?? '',
								'image-converter-webp'
							),
						];

						$form_group .= vsprintf(
							'<p class="badasswp-form-group-block size-50">
								<label>%1$s</label>
								%2$s
								<em>%3$s</em>
							</p>',
							$group_block,
						);
					}
					break;
			}
		}

		return sprintf( '<div class="badasswp-form-group">%s</div>', $form_group );
	}

	/**
	 * Get Form Control.
	 *
	 * This method is responsible for getting the
	 * form control.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $arg  Form control array.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	public function get_form_control( $arg, $name ): string {
		$control = '';

		switch ( $arg['control'] ?? '' ) {
			case 'text':
				$control = $this->get_text_control( $arg, $name );
				break;

			case 'select':
				$control = $this->get_select_control( $arg, $name );
				break;

			case 'checkbox':
				$control = $this->get_checkbox_control( $arg, $name );
				break;
		}

		return $control;
	}

	/**
	 * Get Text Control.
	 *
	 * This method is responsible for getting
	 * Text controls.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $arg  Text args.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	public function get_text_control( $arg, $name ): string {
		return sprintf(
			'<input type="text" placeholder="%1$s" value="%2$s" name="%3$s"/>',
			$arg['placeholder'] ?? '',
			get_option( 'icfw', [] )[ $name ] ?? '',
			$name,
		);
	}

	/**
	 * Get Select Control.
	 *
	 * This method is responsible for getting
	 * Select controls.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $arg  Select args.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	public function get_select_control( $arg, $name ): string {
		$options = '';

		foreach ( $arg['options'] ?? [] as $key => $value ) {
			$is_selected = ( ( get_option( 'icfw', [] )[ $name ] ?? '' ) === $key )
								? 'selected' : '';

			$options .= sprintf(
				'<option value="%1$s" %2$s>%3$s</option>',
				$key,
				$is_selected,
				$value,
			);
		}

		return sprintf(
			'<select name="%1$s">
				%2$s
			</select>',
			$name,
			$options,
		);
	}

	/**
	 * Get Checkbox Control.
	 *
	 * This method is responsible for getting
	 * Checkbox controls.
	 *
	 * @since 1.1.2
	 *
	 * @param mixed[] $arg  Checkbox args.
	 * @param string  $name Control name.
	 *
	 * @return string
	 */
	public function get_checkbox_control( $arg, $name ): string {
		$is_checked = ! empty( get_option( 'icfw', [] )[ $name ] ?? '' )
						? 'checked' : '';

		return sprintf(
			'<input
				name="%1$s"
				type="checkbox"
				%2$s
			/>',
			$name,
			$is_checked,
		);
	}

	/**
	 * Get Form Submit.
	 *
	 * This method is responsible for getting the
	 * Submit button.
	 *
	 * @since 1.1.2
	 *
	 * @return string
	 */
	public function get_form_submit(): string {
		return sprintf(
			'<div class="badasswp-form-group">
				<p class="badasswp-form-group-block">
					<label>%s</label>
				</p>
				<p class="badasswp-form-group-block">
					<button name="webp_save_settings" type="submit" class="button button-primary">
						<span>%s</span>
					</button>
				</p>
				%s
			</div>',
			esc_html__( 'Actions', 'image-converter-webp' ),
			esc_html__( 'Save Changes', 'image-converter-webp' ),
			wp_nonce_field( 'webp_settings_action', 'webp_settings_nonce' ),
		);
	}
}