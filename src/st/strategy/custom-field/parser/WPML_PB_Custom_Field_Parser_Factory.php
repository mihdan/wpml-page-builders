<?php

class WPML_PB_Custom_Field_Parser_Factory {

	private $config;

	public function __construct( WPML_PB_Custom_Field_Config $config ) {
		$this->config = $config;
	}

	public function create_parser() {
		$parser = null;

		if ( 'json' === $this->config->get_format() ) {
			$parser = new WPML_PB_Custom_Field_Parser( new WPML_PB_Custom_Field_JSON_Parser() );
		}

		return $parser;
	}
}