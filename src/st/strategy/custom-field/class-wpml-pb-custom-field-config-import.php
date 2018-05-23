<?php

class WPML_PB_Custom_Field_Config_Import {

	private $config;

	public function add_hooks() {
		add_filter( 'wpml_config_array', array( $this, 'save_config' ) );
	}

	/**
	 * @param array $config_data
	 *
	 * @return array
	 */
	public function save_config( $config_data ) {
		$old_config = $this->get();
		$config     = array();

		if ( array_key_exists( WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY, $config_data['wpml-config'] ) ) {
			$params = array(
				'custom_field' => $config_data['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_KEY ]['value'],
				'format'       => $config_data['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::FORMAT_KEY ]['value'],
				'node_type'    => $config_data['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::NODE_TYPE_KEY ]['value'],
				'nodes'        => $this->get_nodes( $config_data['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::NODES_KEY ] ),
			);

			$config = new WPML_PB_Custom_Field_Config( $params );
		}

		if ( $config != $old_config ) {
			$this->update( $config );
		}

		return $config_data;
	}

	/**
	 * @param array $nodes
	 *
	 * @return array
	 */
	private function get_nodes( $nodes ) {
		$nodes_config = array();
		foreach ( $nodes as $node_key => $node ) {
			if ( is_array( $node ) ) {
				foreach ( $node as $field_key => $field ) {
					if ( is_array( $field ) ) {
						$node_config = new WPML_PB_Custom_Field_Node();

						$fields      = array();
						$parent_node = '';

						if ( WPML_PB_Custom_Field_Config::NODE_FIELDS_KEY === $field_key ) {
							$name = $node_key;
							foreach ( $field as $item_field ) {
								$fields[] = $this->get_new_node_field( $item_field );
							}
						} else {
							$name        = $field_key;
							$parent_node = $node_key;
							if ( array_key_exists( WPML_PB_Custom_Field_Config::NODE_FIELDS_KEY, $field ) ) {
								foreach ( $field[ WPML_PB_Custom_Field_Config::NODE_FIELDS_KEY ] as $item ) {
									$fields[] = $this->get_new_node_field( $item );
								}
							}
						}

						$node_config->set_name( $name );
						if ( $parent_node ) {
							$node_config->set_parent_node( $parent_node );
						}
						$node_config->set_fields( $fields );
						$nodes_config[] = $node_config;
					}
				}
			}
		}

		return $nodes_config;
	}

	/**
	 * @param array $field
	 *
	 * @return WPML_PB_Custom_Field_Node_Field
	 */
	private function get_new_node_field( $field ) {
		return new WPML_PB_Custom_Field_Node_Field( array(
			'name'        => $field['value'],
			'editor_type' => $field['attr'][ WPML_PB_Custom_Field_Node_Field::EDITOR_TYPE_KEY ],
			'label'       => $field['attr'][ WPML_PB_Custom_Field_Node_Field::LABEL_KEY ],
		) );
	}

	/**
	 * @return WPML_PB_Custom_Field_Config
	 */
	public function get() {
		if ( ! $this->config ) {
			$this->config = get_option( WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY );
		}

		return $this->config;
	}

	/**
	 * @param WPML_PB_Custom_Field_Config $data
	 */
	private function update( $data ) {
		update_option( WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY, $data );
	}
}