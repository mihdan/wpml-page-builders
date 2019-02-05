<?php

class WPML_PB_Reuse_Translations_By_Strategy extends WPML_PB_Reuse_translations {

	/** @var  IWPML_PB_Strategy $strategy */
	private $strategy;

	/** @var  array $original_strings */
	private $original_strings;

	public function __construct( IWPML_PB_Strategy $strategy, WPML_ST_String_Factory $string_factory ) {
		$this->strategy       = $strategy;
		parent::__construct( $string_factory );
	}

	/** @param string[] $strings */
	public function set_original_strings( array $strings ) {
		$this->original_strings = $strings;
	}

	/**
	 * @param int $post_id
	 * @param string[] $leftover_strings
	 */
	public function find_and_reuse( $post_id, array $leftover_strings ) {
		$current_strings = $this->strategy->get_package_strings( $this->strategy->get_package_key( $post_id ) );
		parent::find_and_reuse_translations( $this->original_strings, $current_strings, $leftover_strings );
	}
}