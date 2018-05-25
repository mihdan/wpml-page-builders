<?php

class WPML_PB_Custom_Field_Parser {

	private $parser;
	
	public function __construct( IWPML_PB_Custom_Field_Parser $parser ) {
		$this->parser = $parser;
	}

	/**
	 * @param string $data
	 *
	 * @return array
	 */
	public function parse( $data ) {
		return $this->parser->parse( $data );
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function implode( $data ) {
		return $this->parser->implode( $data );
	}
}