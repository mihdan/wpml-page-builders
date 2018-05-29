<?php

class WPML_PB_Custom_Field_Parser_Factory {

	private $config;
	private $custom_field_map;

	public function create_json_parser() {
		return new WPML_PB_Custom_Field_JSON_Parser( $this->get_config(), $this->get_custom_field_map() );
	}

	private function get_config() {
		if ( ! $this->config ) {
			$importer = new WPML_PB_Custom_Field_Config_Import();
			$this->config = $importer->get();
		}

		return $this->config;
	}

	private function get_custom_field_map() {
		if ( ! $this->custom_field_map ) {
			$this->custom_field_map = new WPML_PB_Custom_Field_Node_Map( $this->get_config() );
		}

		return $this->custom_field_map;
	}
}