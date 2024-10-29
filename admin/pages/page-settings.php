<?php
namespace aThemesAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="athemes-addons-modules-box">

	<?php
	/*
	Admin_Settings::create( array(
		'title'  => __( 'MailChimp API Key', 'athemes-addons-elementor' ),
		'fields' => array(
			array(
				'id'      => 'aafe_mailchimp_api_key',
				'type'    => 'text',
				'title'   => '',
				'default' => '',
			),
		),
	) );
	 */

	Admin_Settings::create( array(
		'title'     => __( 'Duplicator Post Types', 'athemes-addons-elementor' ),
		'subtitle'  => __( 'Select the post types you want enable the duplicator for.', 'athemes-addons-elementor' ),
		'fields'    => array(
			array(
				'id'      => 'aafe_duplicator_post_types',
				'type'    => 'multicheckbox',
				'default' => array( 'all' ),
				'options' => athemes_addons_get_post_types(),
			),
		),
	) );

	Admin_Settings::save_button();
	?>

</div>