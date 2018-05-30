<?php

class WPML_PB_Custom_Field_Parser {

	protected $config;
	protected $custom_field_map;
	protected $nodes;

	public function __construct( WPML_PB_Custom_Field_Config $config, WPML_PB_Custom_Field_Node_Map $custom_field_map ) {
		$this->config = $config;
		$this->custom_field_map = $custom_field_map;
	}

	/**
	 * @param array $data
	 */
	public function build_nodes( $data ) {
		foreach ( $data as $key => $item ) {
			if ( is_array( $item ) ) {
				$this->build_nodes( $item );
			}

			$node = $this->custom_field_map->get_node( $item );

			if ( $key === $this->config->get_node_type() && $node ) {

				if ( $node->get_parent_node() ) {
					$data = $node->get_items_key() ? $data[ $node->get_items_key() ] : $data;
					$this->build_child_nodes( $data, $item );
				} else {
					$fields = array();
					foreach ( $node->get_fields() as $field ) {
						$data = $node->get_fields_key() ? $data[ $node->get_fields_key() ] : $data;
						if ( array_key_exists( $field->get_name(), $data ) ) {
							$field->set_value( $data[ $field->get_name() ] );
						}

						$fields[] = $field;
					}

					$node->set_fields( $fields );
					$this->nodes[] = $node;
				}
			}
		}
	}

	private function build_child_nodes( $data, $node_name ) {
		$node = $this->custom_field_map->get_node( $node_name );
		$fields = array();
		foreach ( $data as $key => $item ) {
			if ( is_array( $item ) ) {
				$this->build_child_nodes( $item, $node_name );
			} else {
				foreach ( $node->get_fields() as $field ) {
					if ( $field->get_name() === $key ) {
						$field->set_value( $data[ $field->get_name() ] );
						$fields[] = $field;
					}
				}
			}
		}

		if ( $fields ) {
			$node->set_fields( $fields );
			$this->nodes[] = $node;
		}
	}
}