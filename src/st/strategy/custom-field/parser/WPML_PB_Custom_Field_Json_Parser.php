<?php

class WPML_PB_Custom_Field_JSON_Parser extends WPML_PB_Custom_Field_Parser implements IWPML_PB_Custom_Field_Parser {

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function parse( $data ) {
		$this->nodes = array();
		if ( array_key_exists( 0, $data ) ) {
			$this->build_nodes( json_decode( $data[0], true ) );
		}

		return $this->nodes;
	}
}