<?php

/**
 * @group media
 * @group wpmlcore-5765
 */
class Test_WPML_PB_Package_Strings_Resave extends \OTGS\PHPUnit\Tools\TestCase {

	/**
	 * @test
	 */
	public function it_should_not_resave_if_post_element_is_source() {
		$post_element = $this->get_post_element();
		$post_element->method( 'get_source_element' )->willReturn( null );

		$factory = $this->get_string_factory();

		$subject = $this->get_subject( $factory );

		$this->assertEquals(
			array(),
			$subject->from_element( $post_element )
		);
	}

	/**
	 * @test
	 */
	public function it_should_resave() {
		$source_id   = 49;
		$target_lang = 'fr';
		$string_id   = 1025;

		$source_element = $this->get_post_element();
		$source_element->method( 'get_id' )->willReturn( $source_id );

		$post_element = $this->get_post_element();
		$post_element->method( 'get_source_element' )->willReturn( $source_element );
		$post_element->method( 'get_language_code' )->willReturn( $target_lang );

		$raw_string = (object) array(
			'id' => $string_id,
		);

		$package = $this->get_package();
		$package->method( 'get_package_strings' )->willReturn( array( $raw_string ) );

		\WP_Mock::onFilter( 'wpml_st_get_post_string_packages' )
			->with( array(), $source_id )->reply( array( $package ) );

		$string_translation = (object) array(
			'language'            => 'fr',
			'value'               => 'La Traduction',
			'status'              => '5',
			'translator_id'       => '3',
			'translation_service' => '12',
			'batch_id'            => '49',
		);

		$string = $this->get_string();
		$string->method( 'get_translations' )->willReturn( array( $string_translation ) );
		$string->expects( $this->once() )->method( 'set_translation' )
			->with(
				$target_lang,
				$string_translation->value,
				$string_translation->status,
				$string_translation->translator_id,
				$string_translation->translation_service,
				$string_translation->batch_id
			);

		$factory = $this->get_string_factory();
		$factory->method( 'find_by_id' )->with( $string_id )->willReturn( $string );

		\WP_Mock::userFunction( 'wp_list_filter', array(
			'args'   => array( array( $string_translation ), array( 'language' => $target_lang ) ),
			'return' => array( $string_translation )
		));

		$subject = $this->get_subject( $factory );

		$this->assertEquals(
			array( $package ),
			$subject->from_element( $post_element )
		);
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
		return $this->getMockBuilder( 'WPML_ST_String' )
			->setMethods( array( 'get_translations', 'set_translation' ) )
			->disableArgumentCloning()->getMock();
	}

	private function get_string_translation( $lang ) {
		return (object) array(
			'language'            => $lang,
			'value'               => rand_str(),
			'status'              => rand_str(),
			'translator_id'       => rand_str(),
			'translation_service' => rand_str(),
			'batch_id'            => rand_str(),
		);
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
