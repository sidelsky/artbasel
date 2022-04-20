<?php

class Category_Metabox_Enhanced_Settings_Settings {

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * Call $name from public plugin class later.
	 *
	 * @since    0.4.0
	 *
	 * @var      string
	 */
	private $name;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.4.0
	 */
	public function __construct( $name ) {

		$this->name = $name;

		// Add settings page
		add_action( 'admin_init', array( $this, 'admin_init' ) );

	}

	/**
	 * Registering the Sections, Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 *
	 * @since 0.4.0
	 */
	public function admin_init() {

		$taxes = of_cme_supported_taxonomies();

		$defaults = of_cme_get_defaults();

		foreach ( $taxes as $tax ) {
			$taxonomy_object = get_taxonomy( $tax );
			$section = $this->name . '_' . $tax;

			if ( false == get_option( $section ) ) {
				add_option( $section, apply_filters( $section . '_default_settings', $defaults ) );
			}

			$args = array( $section, get_option( $section ) );

			add_settings_section(
				$tax,
				sprintf( __( '%s Metabox', $this->name ), $taxonomy_object->labels->name ),
				'',
				$section
			);

			add_settings_field(
				'type',
				__( 'Option Type', $this->name ),
				array( $this, 'type_callback' ),
				$section,
				$tax,
				$args
			);

			add_settings_field(
				'context',
				__( 'Position (Context)', $this->name ),
				array( $this, 'context_callback' ),
				$section,
				$tax,
				$args
			);

			add_settings_field(
				'priority',
				__( 'Priority', $this->name ),
				array( $this, 'priority_callback' ),
				$section,
				$tax,
				$args
			);

			add_settings_field(
				'metabox_title',
				__( 'Title', $this->name ),
				array( $this, 'title_callback' ),
				$section,
				$tax,
				$args
			);

			add_settings_field(
				'indent',
				__( 'Indent', $this->name ),
				array( $this, 'indent_callback' ),
				$section,
				$tax,
				$args
			);

			add_settings_field(
				'allow_new_terms',
				__( 'Allow new terms', $this->name ),
				array( $this, 'allow_new_terms_callback' ),
				$section,
				$tax,
				$args
			);

			register_setting(
				$section,
				$section,
				array( $this, 'validate_inputs' )
			);
		}

	} // end admin_init

        /**
         * Callback function for type field
         *
         * @since 0.4.0
         * @param  array $args
         * @return string HTML for type field
         */
	public function type_callback( $args ) {

                $types = array(
                        'checkbox', 'radio', 'select'
                );
		$value  = isset( $args[1]['type'] ) ? $args[1]['type'] : 'checkbox';

                $html = '<fieldset>';
                foreach ( $types as $type ) {
                        $html .= '<label title="' . $type . '"><input type="radio" name="' . $args[0] . '[type]" value="' . $type . '" ' . checked( $type, $value, false ) . '> <span>' . ucfirst( $type ) . '</span></label><br>';
                }
                $html .= '</fieldset>';

		$html .= '<p class="description"><strong>' . __( 'Settings below won\'t work if option type is Checkbox.', $this->name ) . '</strong></p>';

		echo $html;

	} // end type_callback

	/**
	* Callback function for context field
	*
	* @since 0.5.0
	* @param  array $args
	* @return string HTML for context field
	*/
	public function context_callback( $args ) {

		$contexts = array(
			'normal', 'advanced', 'side'
		);
		$value  = isset( $args[1]['context'] ) ? $args[1]['context'] : 'side';

		$html = '<fieldset>';
		foreach ( $contexts as $context ) {
			$html .= '<label title="' . $context . '"><input type="radio" name="' . $args[0] . '[context]" value="' . $context . '" ' . checked( $context, $value, false ) . '> <span>' . ucfirst( $context ) . '</span></label><br>';
		}
		$html .= '</fieldset>';

		// $html .= '<p class="description">' . __( 'Select the option type', $this->name ) . '</p>';

		echo $html;

	} // end context_callback

	/**
	* Callback function for context field
	*
	* @since 0.5.0
	* @param  array $args
	* @return string HTML for priority field
	*/
	public function priority_callback( $args ) {

		$prioritys = array(
			'high', 'core', 'default', 'low'
		);
		$value  = isset( $args[1]['priority'] ) ? $args[1]['priority'] : 'default';

		$html = '<fieldset>';
		foreach ( $prioritys as $priority ) {
			$html .= '<label title="' . $priority . '"><input type="radio" name="' . $args[0] . '[priority]" value="' . $priority . '" ' . checked( $priority, $value, false ) . '> <span>' . ucfirst( $priority ) . '</span></label><br>';
		}
		$html .= '</fieldset>';

		// $html .= '<p class="description">' . __( 'Select the option type', $this->name ) . '</p>';

		echo $html;

	} // end priority_callback

	public function title_callback( $args ) {

		$value  = isset( $args[1]['metabox_title'] ) ? $args[1]['metabox_title'] : '';

		$html = '<input type="text" id="metabox_title" name="' . $args[0] . '[metabox_title]" value="' . $value . '" class="regular-text" />';
		// $html .= '<p class="description">' . __( 'Enter your custom title for Featured Image Metabox.', $this->name ) . '</p>';

		echo $html;

	} // end title_callback

	public function indent_callback( $args ) {

		$value  = isset( $args[1]['indented'] ) ? $args[1]['indented'] : 0;

		$html = '<label for="indent"><input type="checkbox" id="indent" name="' . $args[0] . '[indented]" value="1" ' . checked( $value, 1, false ) . ' /> Yes</label>';
		$html .= '<p class="description">' . __( 'Check if child terms should be indent.', $this->name ) . '</p>';

		echo $html;

	} // end indent_callback

	public function allow_new_terms_callback( $args ) {

		$value  = isset( $args[1]['allow_new_terms'] ) ? $args[1]['allow_new_terms'] : 0;

		$html = '<label for="allow_new_terms"><input type="checkbox" id="allow_new_terms" name="' . $args[0] . '[allow_new_terms]" value="1" ' . checked( $value, 1, false ) . ' /> Yes</label>';
		$html .= '<p class="description">' . __( 'Chekc if allows adding of new terms from the metabox.', $this->name ) . '</p>';

		echo $html;

	} // end allow_new_terms_callback

	/**
	 * Validate inputs
	 *
	 * @return array Sanitized data
	 *
	 * @since 0.4.0
	 */
	public function validate_inputs( $inputs ) {

		$outputs = array();
		$defaults = of_cme_get_defaults();

		foreach( $defaults as $key => $v ) {
			$outputs[$key] = ( isset( $inputs[$key] ) ) ? sanitize_text_field( $inputs[$key] ) : 0;
		}

		return apply_filters( 'cme_validate_inputs', $outputs, $inputs );

	} // end validate_inputs
}
