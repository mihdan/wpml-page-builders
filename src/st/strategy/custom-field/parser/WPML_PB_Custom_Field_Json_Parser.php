<?php

class WPML_PB_Custom_Field_JSON_Parser extends WPML_PB_Custom_Field_Parser implements IWPML_PB_Custom_Field_Parser {

	/**
	 * @param string $data
	 *
	 * @return array
	 */
	public function parse( $data ) {
		if ( is_array( $data ) && array_key_exists( 0, $data ) ) {
			$parsed = $this->build_nodes( json_decode( $data[0], true ) );
		}
	}

	private function build_nodes( &$data ) {
		foreach ( $data as $key => $item ) {
			if ( is_array( $item ) ) {
				$this->build_nodes( $item );
			}

			$node = $this->custom_field_map->get_node( $item );

			if ( $this->config->get_node_type() === $key && $node ) {
				$test = '';
			}
		}
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function implode( $data ) {
		return json_encode( $data );
	}
}