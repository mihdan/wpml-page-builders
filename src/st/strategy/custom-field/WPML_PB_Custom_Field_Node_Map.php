<?php

class WPML_PB_Custom_Field_Node_Map {

	private $config;

	public function __construct( WPML_PB_Custom_Field_Config $config ) {
		$this->config = $config;
	}

	public function get_node( $name ) {
		foreach ( $this->config->get_nodes() as $node ) {
			if ( $node->get_name() === $name ) {
				return $node;
			}
		}

		return null;
	}
}