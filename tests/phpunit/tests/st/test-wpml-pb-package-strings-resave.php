<?php

/**
 * @group media
 * @group wpmlcore-5765
 */
class Test_WPML_PB_Package_Strings_Resave extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_should_not_resave_if_post_element_is_source() {
		$post_element = $this->get_post_element();
		$post_element->method( 'get_source_element' )->willReturn( null );

		$factory = $this->get_string_factory();

		$subject = $this->get_subject( $factory );

		$subject->from_element( $post_element );
	}

	private function get_subject( $string_factory ) {
		return new WPML_PB_Package_Strings_Resave( $string_factory );
	}

	private function get_string_factory() {
		return $this->getMockBuilder( 'WPML_ST_String_Factory' )
			->setMethods( array( 'find_by_id' ) )
			->disableArgumentCloning()->getMock();
	}

	private function get_string() {
		return $this->getMockBuilder( 'WPML_ST_String_Factory' )
			->setMethods( array( 'get_translations', 'set_translation' ) )
			->disableArgumentCloning()->getMock();
	}

	private function get_post_element() {
		return $this->getMockBuilder( 'WPML_Post_Element' )
	        ->setMethods( array( 'get_source_element', 'get_language_code', 'get_id' ) )
	        ->disableArgumentCloning()->getMock();
	}

	private function get_package() {
		return $this->getMockBuilder( 'WPML_Package' )
	        ->setMethods( array( 'get_package_strings' ) )
	        ->disableArgumentCloning()->getMock();
	}
}
