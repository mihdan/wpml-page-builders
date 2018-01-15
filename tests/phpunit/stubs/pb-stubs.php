<?php

interface IWPML_PB_Integration {
	public function add_hooks();
}

interface IWPML_PB_Integration_Factory {
	/**
	 * @param $class_name
	 *
	 * @return IWPML_PB_Integration
	 */
	public function create( $class_name );
}

