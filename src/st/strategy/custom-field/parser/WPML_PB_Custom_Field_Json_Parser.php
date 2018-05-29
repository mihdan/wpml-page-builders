<?php

class WPML_PB_Custom_Field_JSON_Parser extends WPML_PB_Custom_Field_Parser implements IWPML_PB_Custom_Field_Parser {

	private $nodes;

	/**
	 * @param mixed $data
	 *
	 * @return array
	 */
	public function parse( $data ) {
		$this->nodes = array();
		if ( is_array( $data ) && array_key_exists( 0, $data ) ) {
			$this->build_nodes( json_decode( $data[0], true ) );
		}

		return $this->nodes;
	}

	private function build_nodes( $data ) {
		foreach ( $data as $key => $item ) {
			if ( is_array( $item ) ) {
				$this->build_nodes( $item );
			}

			$node = $this->custom_field_map->get_node( $item );

			if ( $key === $this->config->get_node_type() && $node ) {
				$fields = array();
				foreach ( $node->get_fields() as $field ) {
					if ( array_key_exists( $field->get_name(), $data ) ) {
						$field->set_value( $data[ $field->get_name() ] );
					}

					foreach ( $data as $data_key => $data_item ) {
						if ( is_array( $data_item ) ) {
							$item_value = $this->build_items( $data_item, $field );
							if ( $item_value ) {
								$field->set_value( $item_value );
							}
						}
					}
					$fields[] = $field;
				}

				$node->set_fields( $fields );
				$this->nodes[] = $node;
			}
		}
	}

	private function build_items( $data, $field ) {
		foreach ( $data as $item ) {
			if ( is_array( $item ) ) {
				return $this->build_items( $item, $field );
			}

			if ( array_key_exists( $field->get_name(), $data ) ) {
				return $item;
			}
		}
	}
}