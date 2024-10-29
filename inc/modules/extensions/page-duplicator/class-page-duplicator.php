<?php

namespace aThemes_Addons\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Page_Duplicator {

	/**
	 * Instance
	 */     
	private static $instance;

	/**
	 * Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}   

    public final function __construct() {
		add_action( 'admin_action_aafe_duplicate', array( $this, 'duplicate' ) );
		add_filter( 'page_row_actions', array( $this, 'row_actions' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
	}

	/**
	 * Add Page Duplicator Button
	 *
	 * @param array $actions
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public function row_actions( $actions, $post ) {

		global $pagenow;
		global $post;
		
		$enabled_on = get_option( 'athemes-addons-settings' )['aafe_duplicator_post_types'];

		$enabled_on = explode( ',', $enabled_on );

		if ( current_user_can( 'edit_others_posts' ) && ( in_array( 'all', $enabled_on, true ) || in_array( $post->post_type, $enabled_on, true ) ) ) {
			$duplicate_url            = admin_url( 'admin.php?action=aafe_duplicate&post=' . $post->ID );
			$duplicate_url            = wp_nonce_url( $duplicate_url, 'aafe_duplicator' );
			$actions['aafe_duplicate'] = sprintf( '<a href="%s" title="%s">%s</a>', $duplicate_url, __( 'Duplicate ' . esc_attr( $post->post_title ), 'athemes-addons-elementor' ), __( 'aThemes Duplicator', 'athemes-addons-elementor' ) );
		}

		return $actions;
	}

	/**
	 * Duplicate Page
	 */
	public function duplicate() {
		
		if ( ! isset( $_GET['post'] ) ) {
			return;
		}

		$post_id = absint( $_GET['post'] );

		if ( ! current_user_can( 'edit_others_posts' ) || ! wp_verify_nonce( sanitize_text_field( $_GET['_wpnonce'] ), 'aafe_duplicator' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			return;
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			return;
		}

		$new_post_id = wp_insert_post( array(
			'post_title'    => $post->post_title . ' - ' . __( 'Copy', 'athemes-addons-elementor' ),
			'post_content'  => $post->post_content,
			'post_status'   => 'draft',
			'post_type'     => $post->post_type,
			'post_excerpt'  => $post->post_excerpt,
			'post_parent'   => $post->post_parent,
			'post_password' => $post->post_password,
			'post_author'   => $current_user->ID,
		) );

		$redirect_url = admin_url( 'edit.php?post_type=' . $post->post_type );

		if ( $new_post_id ) {
			$taxonomies = get_object_taxonomies( $post->post_type );

			foreach ( $taxonomies as $taxonomy ) {
				$terms = wp_get_object_terms( $post_id, $taxonomy );

				foreach ( $terms as $term ) {
					wp_set_object_terms( $new_post_id, $term->term_id, $taxonomy, true );
				}
			}

			global $wpdb;
			$post_meta = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d", $post_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

			if ( ! empty( $post_meta ) && is_array( $post_meta ) ) {

				$new_query = "INSERT INTO $wpdb->postmeta ( post_id, meta_key, meta_value ) VALUES ";
				$insert = '';

				foreach ( $post_meta as $meta_info ) {

					$meta_key   = sanitize_text_field( $meta_info->meta_key );
					$meta_value =  $meta_info->meta_value;
					
					$exclude_meta_keys = [ '_wc_average_rating', '_wc_review_count', '_wc_rating_count' ];
					
					if ( in_array($meta_key, $exclude_meta_keys, true ) ){
						continue;
					}

					if ( $meta_key === '_elementor_template_type' ) {
						delete_post_meta( $new_post_id, '_elementor_template_type' );
					}

					if ( ! empty( $insert ) ) {
						$insert .= ', ';
					}

					$insert .= $wpdb->prepare( '(%d, %s, %s)', $new_post_id, $meta_key, $meta_value );
				}

				$wpdb->query( $new_query . $insert ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared
			}

			wp_safe_redirect( $redirect_url );
		}
	}
}

Page_Duplicator::get_instance();