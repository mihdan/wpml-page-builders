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
	 */
	public function it_parse_config_data_and_update_option() {
		$node = array(
			'my-node' => array(
				'fields' => array(
					array(
						'value' => 'title',
						'attr'  => array( 'editor-type' => 'LINE', 'label' => 'My Title' )
					)
				),
			),
		);
		$custom_field_factory                                        = $this->get_custom_field_factory();
		$config                                                      = $this->get_config_data();
		$config['wpml-config']['page-builder-custom-field']['nodes'] = $node;
		$subject                                                     = new WPML_PB_Custom_Field_Config_Import( $custom_field_factory );

		\WP_Mock::wpFunction( 'get_option', array(
			'args'   => WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY,
			'return' => array(),
		) );

		$node_name = array_keys( $node )[0];
		$node_obj  = new WPML_PB_Custom_Field_Node( array() );

		$nodes = array(
			$node_obj,
		);

		$node_field = new WPML_PB_Custom_Field_Node_Field( array() );

		$node_config_params = array(
			'custom_field' => $config['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_KEY ]['value'],
			'format'       => $config['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::FORMAT_KEY ]['value'],
			'node_type'    => $config['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::NODE_TYPE_KEY ]['value'],
			'nodes'        => $nodes,
		);

		$data = new WPML_PB_Custom_Field_Config( $node_config_params );

		$custom_field_factory->expects( $this->once() )
		                     ->method( 'create_config' )
		                     ->with( $node_config_params )
		                     ->willReturn( $data );

		$custom_field_factory->expects( $this->once() )
		                     ->method( 'create_node' )
		                     ->willReturn( $node_obj );

		$custom_field_factory->expects( $this->once() )
		                     ->method( 'create_node_field' )
		                     ->with( array(
			                     'name'        => $node[ $node_name ]['fields'][0]['value'],
			                     'editor_type' => $node[ $node_name ]['fields'][0]['attr'][ WPML_PB_Custom_Field_Node_Field::EDITOR_TYPE_KEY ],
			                     'label'       => $node[ $node_name ]['fields'][0]['attr'][ WPML_PB_Custom_Field_Node_Field::LABEL_KEY ],
		                     ) )
		                     ->willReturn( $node_field );

		\WP_Mock::wpFunction( 'update_option', array(
			'args'  => array( WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY, $data ),
			'times' => 1,
		) );

		$this->assertEquals( $config, $subject->parse( $config ) );
	}

	/**
	 * @test
	 */
	public function it_parse_config_data_and_does_not_update_option_because_it_is_up_to_date() {
		$node = array(
			'my-node' => array(
				'fields' => array(
					array(
						'value' => 'title',
						'attr'  => array( 'editor-type' => 'LINE', 'label' => 'My Title' )
					)
				),
			),
		);
		$custom_field_factory                                        = $this->get_custom_field_factory();
		$config                                                      = $this->get_config_data();
		$config['wpml-config']['page-builder-custom-field']['nodes'] = $node;
		$subject                                                     = new WPML_PB_Custom_Field_Config_Import( $custom_field_factory );

		$node_name = array_keys( $node )[0];
		$node_obj  = new WPML_PB_Custom_Field_Node( array() );

		$nodes = array(
			$node_obj,
		);

		$node_field = new WPML_PB_Custom_Field_Node_Field( array() );

		$node_config_params = array(
			'custom_field' => $config['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_KEY ]['value'],
			'format'       => $config['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::FORMAT_KEY ]['value'],
			'node_type'    => $config['wpml-config'][ WPML_PB_Custom_Field_Config::CUSTOM_FIELD_DATA_KEY ][ WPML_PB_Custom_Field_Config::NODE_TYPE_KEY ]['value'],
			'nodes'        => $nodes,
		);

		$data = new WPML_PB_Custom_Field_Config( $node_config_params );

		$custom_field_factory->expects( $this->once() )
		                     ->method( 'create_config' )
		                     ->with( $node_config_params )
		                     ->willReturn( $data );

		$custom_field_factory->expects( $this->once() )
		                     ->method( 'create_node' )
		                     ->willReturn( $node_obj );

		$custom_field_factory->expects( $this->once() )
		                     ->method( 'create_node_field' )
		                     ->with( array(
			                     'name'        => $node[ $node_name ]['fields'][0]['value'],
			                     'editor_type' => $node[ $node_name ]['fields'][0]['attr'][ WPML_PB_Custom_Field_Node_Field::EDITOR_TYPE_KEY ],
			                     'label'       => $node[ $node_name ]['fields'][0]['attr'][ WPML_PB_Custom_Field_Node_Field::LABEL_KEY ],
		                     ) )
		                     ->willReturn( $node_field );

		\WP_Mock::wpFunction( 'get_option', array(
			'args'   => WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY,
			'return' => $data,
		) );

		\WP_Mock::wpFunction( 'update_option', array(
			'times' => 0,
		) );

		$this->assertEquals( $config, $subject->parse( $config ) );
	}

	/**
	 * @test
	 */
	public function it_gets_config() {
		$custom_field_factory = $this->get_custom_field_factory();
		$config_data          = rand_str( 10 );
		$subject              = new WPML_PB_Custom_Field_Config_Import( $custom_field_factory );

		\WP_Mock::wpFunction( 'get_option', array(
			'args'   => WPML_PB_Custom_Field_Config::CONFIG_FIELD_KEY,
			'return' => $config_data,
			'times'  => 1,
		) );


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
			'Node without subitems (not parent)' => array(
				'fields' => array(

				)
			),
			'Node with items (with parent)'      => array(
				'fields' => array(
					'my-parent-node' => array(
						'fields'             => array(
							array(
								'value' => 'title',
								'attr'  => array( 'editor-type' => 'LINE', 'label' => 'My Title' )
							)
						),
						'my-child-node-item' => array(
							'fields' => array(
								array(
									'value' => 'my sub node',
									'attr'  => array( 'editor-type' => 'LINE', 'label' => 'My sub node title' )
								),
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