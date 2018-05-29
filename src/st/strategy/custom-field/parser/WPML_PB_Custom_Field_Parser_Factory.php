<?php

class WPML_PB_Custom_Field_Parser_Factory {

	public function create_json_parser() {
		new WPML_PB_Custom_Field_JSON_Parser();
	}
}