<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

do_action( 'athemes_addons_do_content' );

get_footer( 'shop' );