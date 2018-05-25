<?php

class WPML_PB_Custom_Field_JSON_Parser implements IWPML_PB_Custom_Field_Parser {

	/**
	 * @param string $data
	 *
	 * @return array
	 */
	public function parse( $data ) {
		return json_decode( $data, true );
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