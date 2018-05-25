<?php

/**
 * Class Test_WPML_PB_Custom_Field_Converter
 *
 * @group wpmlcore-5379
 */
class Test_WPML_PB_Custom_Field_Converter extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_parses_json_custom_field_data() {
		$config = $this->getMockBuilder( 'WPML_PB_Custom_Field_Config' )
			->disableOriginalConstructor()
			->getMock();
		
		$config->method( 'get_format' )
			->willReturn( 'json' );

		$custom_field = '_cornerstone_data';

		$post = $this->getMockBuilder( 'WP_Post' )
			->disableOriginalConstructor()
			->getMock();

		$post->ID = mt_rand( 1, 10 );

		$expected = array(
			'key' => 'value'
		);

		$json_encoded_string = json_encode( $expected );

		\WP_Mock::wpFunction( 'get_post_meta', array(
			'args' => array( $post->ID, $custom_field ),
			'return' => $json_encoded_string,
		));
		
		$config->method( 'get_custom_field' )
			->willReturn( $custom_field );
		
		$subject = new WPML_PB_Custom_Field_Converter( $config );
		$this->assertEquals( $expected, $subject->parse( $post ) );
	}

	public function it_implodes_into_json_encoded_string_custom_field_data() {
	}
}