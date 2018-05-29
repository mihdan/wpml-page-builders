<?php

interface IWPML_PB_Custom_Field_Parser {

	/**
	 * @param mixed $data
	 *
	 * @return array
	 */
	public function parse( $data );
}