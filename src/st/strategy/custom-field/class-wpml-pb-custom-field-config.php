<?php

class WPML_PB_Custom_Field_Config {

	const CUSTOM_FIELD_DATA_KEY = 'page-builder-custom-field';
	const CUSTOM_FIELD_KEY = 'custom-field';
	const FORMAT_KEY = 'format';
	const NODE_TYPE_KEY = 'node-type';
	const NODES_KEY = 'nodes';
	const NODE_FIELDS_KEY = 'fields';
	const CONFIG_FIELD_KEY = '_wpml_pb_custom_field_schema';
	const JSON_FORMAT = 'json';
	const SERIALIZED_FORMAT = 'serialize';

	/**
	 * @var string
	 */
	private $custom_field;

	/**
	 * @var string
	 */
	private $format;

	/**
	 * @var string
	 */
	private $node_type;

	/**
	 * @var array
	 */
	private $nodes;

	public function __construct( array $params ) {
		foreach ( get_object_vars( $this ) as $property => $value ) {
			if ( array_key_exists( $property, $params ) ) {
				$this->$property = $params[ $property ];
			}
		}
	}

	public function get_custom_field() {
		return $this->custom_field;
	}

	public function get_format() {
		return $this->format;
	}

	public function get_node_type() {
		return $this->node_type;
	}

	public function get_nodes() {
		return $this->nodes;
	}
}