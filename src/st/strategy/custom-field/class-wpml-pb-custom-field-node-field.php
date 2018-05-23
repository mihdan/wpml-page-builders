<?php

class WPML_PB_Custom_Field_Node_Field {

	const EDITOR_TYPE_KEY = 'editor-type';
	const LABEL_KEY = 'label';

	/**
	 * @var string
	 */
	private $editor_type;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $label;

	public function __construct( array $params ) {
		foreach ( get_object_vars( $this ) as $property => $value ) {
			if ( array_key_exists( $property, $params ) ) {
				$this->$property = $params[ $property ];
			}
		}
	}

	public function get_editor_type() {
		return $this->editor_type;
	}

	public function get_label() {
		return $this->label;
	}

	public function get_name() {
		return $this->name;
	}
}