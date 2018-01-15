<?php

/**
 * @group page-builders
 * @group page-builders-shortcodes
 */
class Test_WPML_PB_Shortcode_Encoding extends WPML_PB_TestCase {

	public function test_enfold_manually_decoding() {

		$url = 'http://some-url.com';
		$link = 'manually,' . $url;

		$subject = new WPML_PB_Shortcode_Encoding();

		$this->assertSame( $url,
		                   $subject->decode( $link, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_ENFOLD_LINK ) );

	}

	public function test_enfold_post_decoding() {

		$post_id  = mt_rand( 1, 100 );
		$link     = 'post,' . $post_id;
		$expected = 'http://my_site.com/something';

		WP_Mock::wpFunction( 'post_type_exists',
			array(
				'args' => 'post',
				'times' => 1,
				'return' => true,
			)
		);
		WP_Mock::wpFunction( 'get_permalink',
			array(
				'args' => $post_id,
				'times' => 1,
				'return' => $expected,
			)
		);
		$subject = new WPML_PB_Shortcode_Encoding();
		$this->assertSame( $expected, $subject->decode( $link, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_ENFOLD_LINK ) );

	}

	public function test_enfold_taxonomy_decoding() {

		$tax_id   = mt_rand( 1, 100 );
		$link     = 'tax,' . $tax_id;
		$expected = 'http://my_site.com/something';

		WP_Mock::wpFunction( 'post_type_exists',
			array(
				'args' => 'tax',
				'times' => 1,
				'return' => false,
			)
		);
		WP_Mock::wpFunction( 'taxonomy_exists',
			array(
				'args' => 'tax',
				'times' => 1,
				'return' => true,
			)
		);
		WP_Mock::wpFunction( 'get_term',
			array(
				'args' => array( $tax_id, 'tax' ),
				'times' => 1,
				'return' => $expected,
			)
		);
		WP_Mock::wpPassthruFunction( 'get_term_link' );

		WP_Mock::wpFunction( 'is_wp_error',
			array(
				'args' => $expected,
				'times' => 1,
				'return' => false,
			)
		);

		$subject = new WPML_PB_Shortcode_Encoding();
		$this->assertSame( $expected, $subject->decode( $link, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_ENFOLD_LINK ) );

	}

	public function test_enfold_lightbox_decoding_is_passed_through() {
		$link = 'lightbox,something goes here';

		WP_Mock::wpFunction( 'post_type_exists',
			array(
				'args' => 'lightbox',
				'times' => 1,
				'return' => false,
			)
		);
		WP_Mock::wpFunction( 'taxonomy_exists',
			array(
				'args' => 'lightbox',
				'times' => 1,
				'return' => false,
			)
		);

		$subject = new WPML_PB_Shortcode_Encoding();
		$this->assertSame( $link, $subject->decode( $link, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_ENFOLD_LINK ) );

	}

	public function test_enfold_encoding() {
		$link = 'http://my_site.com/something';

		$subject = new WPML_PB_Shortcode_Encoding();

		$this->assertSame( 'manually,' . $link,
		                   $subject->encode( $link, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_ENFOLD_LINK ) );
	}

	public function test_enfold_lightbox_encoding_is_passed_through() {
		$link = 'lightbox,something goes here';

		$subject = new WPML_PB_Shortcode_Encoding();
		$this->assertSame( $link, $subject->encode( $link, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_ENFOLD_LINK ) );
	}

	/**
	 * @test
	 */
	public function it_filters_the_base64_decoded_string() {
		$string         = 'some-string';
		$encoded_string = base64_encode( $string );

		$subject = new WPML_PB_Shortcode_Encoding();

		WP_Mock::onFilter( 'wpml_pb_shortcode_decode' )
		       ->with( $string, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_BASE64, $encoded_string )
		       ->reply( 'filtered: ' . $string );

		$this->assertSame( 'filtered: ' . $string,
		                   $subject->decode( $encoded_string, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_BASE64 ) );
	}

	/**
	 * @test
	 */
	public function it_filters_the_base64_encoded_string() {
		$string         = 'some-string';
		$encoded_string = base64_encode( $string );

		$subject = new WPML_PB_Shortcode_Encoding();

		WP_Mock::onFilter( 'wpml_pb_shortcode_encode' )
		       ->with( $encoded_string, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_BASE64, $string )
		       ->reply( 'filtered: ' . $string );

		$this->assertSame( 'filtered: ' . $string ,
		                   $subject->encode( $string, WPML_PB_Shortcode_Encoding::ENCODE_TYPES_BASE64 ) );
	}
}
