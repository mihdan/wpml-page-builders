<?php

class WPML_PB_Custom_Field_Node_Map {

	private $config;
	private $custom_field_factory;

	public function __construct( WPML_PB_Custom_Field_Config $config, WPML_PB_Custom_Field_Factory $custom_field_factory ) {
		$this->config = $config;
		$this->custom_field_factory = $custom_field_factory;
	}

	public function get_node( $name ) {
		foreach ( $this->config->get_nodes() as $node ) {
			if ( $node->get_name() === $name || $node->get_parent_node() === $name ) {
				$fields = array();
				foreach ( $node->get_fields() as $field ) {
					$fields[] = $this->custom_field_factory->create_node_field( array(
						'name' => $field->get_name(),
						'editor_type' => $field->get_editor_type(),
						'label' => $field->get_label(),
					) );
				}

				return $this->custom_field_factory->create_node( array(
					'name' => $node->get_name(),
					'fields' => $fields,
					'parent_node' => $node->get_parent_node(),
					'fields_key' => $node->get_fields_key(),
					'items_key' => $node->get_items_key(),
				) );
			}
		}

		return null;
	}
}