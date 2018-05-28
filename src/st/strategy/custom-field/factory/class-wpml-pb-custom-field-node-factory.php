<?php

class WPML_PB_Custom_Field_Factory {

	/**
	 * @param array $params
	 *
	 * @return WPML_PB_Custom_Field_Node
	 */
	public function create_node( $params = array() ) {
		return new WPML_PB_Custom_Field_Node( $params );
	}

	/**
	 * @param array $params
	 *
	 * @return WPML_PB_Custom_Field_Node_Field
	 */
	public function create_node_field( $params = array() ) {
		return new WPML_PB_Custom_Field_Node_Field( $params );
	}

	/**
	 * @param array $params
	 *
	 * @return WPML_PB_Custom_Field_Config
	 */
	public function create_config( $params = array() ) {
		return new WPML_PB_Custom_Field_Config( $params );
	}
}