<?php

class WPML_PB_Custom_Field_Parser {

	protected $config;
	protected $custom_field_map;

	public function __construct( WPML_PB_Custom_Field_Config $config, WPML_PB_Custom_Field_Node_Map $custom_field_map ) {
		$this->config = $config;
		$this->custom_field_map = $custom_field_map;
	}
}