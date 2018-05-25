<?php

interface IWPML_PB_Custom_Field_Parser {

	/**
	 * @param string $data
	 *
	 * @return array
	 */
	public function parse( $data );

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public function implode( $data );
}