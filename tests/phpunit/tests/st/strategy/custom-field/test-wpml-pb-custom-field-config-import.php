<?php

/**
 * Class Test_WPML_PB_Custom_Field_Config_Import
 *
 * @group custom-field
 * @group wpmlcore-5378
 */
class Test_WPML_PB_Custom_Field_Config_Import extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_adds_hooks() {
		$subject = new WPML_PB_Custom_Field_Config_Import( $this->get_custom_field_factory() );
		\WP_Mock::expectFilterAdded( 'wpml_config_array', array( $subject, 'parse' ) );
		$subject->add_hooks();
	}

	/**
	 * @test
	 * @dataProvider dp_nodes
	 */
	public function it_parse_config_data( $node ) {
		$custom_field_factory = $this->get_custom_field_factory();
		$config = $this->get_config_data();
		$config['wpml-config']['page-builder-custom-field']['nodes'] = $node;
		$subject = new WPML_PB_Custom_Field_Config_Import( $custom_field_factory );

		\WP_Mock::wpFunction( 'get_option', array(
			'args' => WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY,
			'return' => array(),
		));

		$custom_field = '_cornerstone_data';
		$format = 'json';
		$node_type = '_type';
		$node_name = array_keys( $node )[0];

		$field = new WPML_PB_Custom_Field_Node_Field( array(
			'name' => $node[ $node_name ]['fields'][0]['value'],
			'editor_type' => $node[ $node_name ]['fields'][0]['attr']['editor-type'],
			'label' => $node[ $node_name ]['fields'][0]['attr']['label'],
		) );

		$node = new WPML_PB_Custom_Field_Node( array(
			'name' => $node_name,
			'fields' => array( $field ),
		) );

		$data = new WPML_PB_Custom_Field_Config( array(
			'custom_field' => $custom_field,
			'format' => $format,
			'node_type' => $node_type,
			'nodes' => array( $node ),
		) );

		\WP_Mock::wpFunction( 'update_option', array(
			'args' => array( WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY, \WP_Mock\Functions::type( 'WPML_PB_Custom_Field_Config' ) ),
			'times' => 1,
		));

		$this->assertEquals( $config, $subject->parse( $config ) );
	}

	/**
	 * @test
	 */
	public function it_gets_config() {
		$custom_field_factory = $this->get_custom_field_factory();
		$config_data = rand_str( 10 );
		$subject     = new WPML_PB_Custom_Field_Config_Import( $custom_field_factory );

		\WP_Mock::wpFunction( 'get_option', array(
			'args' => WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY,
			'return' => $config_data,
			'times' => 1,
		));


		$this->assertEquals( $config_data, $subject->get() );
	}

	private function get_config_data() {
		return array(
			'wpml-config' => array(
				'page-builder-custom-field' =>
					array(
						'value'        => '',
						'custom-field' =>
							array(
								'value' => '_cornerstone_data',
							),
						'format'       =>
							array(
								'value' => 'json',
							),
						'node-type'    =>
							array(
								'value' => '_type',
							),
					)
			)
		);
	}

	public function dp_nodes() {
		return array(
			'Node without subitems' => array(
				'fields' => array(
					'my-node' => array(
						'fields' => array(
							array( 'value' => 'title', 'attr' => array( 'editor-type' => 'LINE', 'label' => 'My Title' ) )
						),
					),
				)
			),
			'Node with items' => array(
				'fields' => array(
					'my-node' => array(
						'fields' => array(
							array( 'value' => 'title', 'attr' => array( 'editor-type' => 'LINE', 'label' => 'My Title' ) )
						),
						'node-item' => array(
							'fields' => array(
								array( 'value' => 'my sub node', 'attr' => array( 'editor-type' => 'LINE', 'label' => 'My sub node title' ) ),
							)
						)
					),
				)
			)
		);
	}

	private function get_custom_field_factory() {
		return $this->getMockBuilder( 'WPML_PB_Custom_Field_Factory' )
			->disableOriginalConstructor()
			->getMock();
	}
}