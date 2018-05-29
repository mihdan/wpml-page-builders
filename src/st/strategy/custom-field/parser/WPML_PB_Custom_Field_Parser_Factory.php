<?php

class WPML_PB_Custom_Field_Parser_Factory {

	private $config;

	public function create_parser() {
		new WPML_PB_Custom_Field_Parser( new WPML_PB_Custom_Field_JSON_Parser() );

		return $parser;
	}
}