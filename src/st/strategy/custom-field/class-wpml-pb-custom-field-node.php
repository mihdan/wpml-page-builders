<?php

class WPML_PB_Custom_Field_Node {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	private $fields;

	/**
	 * @var string
	 */
	private $parent_node;

	public function __construct( array $params = array() ) {
		foreach ( get_object_vars( $this ) as $property => $value ) {
			if ( array_key_exists( $property, $params ) ) {
				$this->$property = $params[ $property ];
			}
		}
	}

	public function get_name() {
		return $this->name;
	}

	public function get_fields() {
		return $this->fields;
	}

	public function get_parent_node() {
		return $this->parent_node;
	}

	public function set_fields( $fields ) {
		$this->fields = $fields;
	}

	public function set_name( $name ) {
		$this->name = $name;
	}

	public function set_parent_node( $node ) {
		$this->parent_node = $node;
	}
}