<?php

class WPML_PB_Custom_Field_Parser_Builder {

	/**
	 * @var string
	 */
	private $format;

	private $parser_factory;

	public function __construct( $format, WPML_PB_Custom_Field_Parser_Factory $parser_factory ) {
		$this->format = $format;
		$this->parser_factory = $parser_factory;
	}

	public function build() {
		$parser = null;

		if ( WPML_PB_Custom_Field_Config::JSON_FORMAT === $this->format ) {
			$parser = $this->parser_factory->create_json_parser();
		}

		return $parser;
	}
}