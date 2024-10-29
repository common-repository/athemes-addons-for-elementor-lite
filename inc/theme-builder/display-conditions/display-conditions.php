<?php
/**
 * Display conditions functions
 *
 * @package aThemes Addons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Display Conditions for the templates
 */
function athemes_addons_templates_display_conditions( $template_type ) {
	
	$templates = get_posts( array(
		'post_type'     => 'aafe_templates',
		'numberposts'   => -1,
	) );

	// If no templates, return
	if ( empty( $templates ) ) {
		return;
	}

	$results = array();

	foreach ( $templates as $template ) {
		$conditions     = get_post_meta( $template->ID, '_ahf_template_conditions', true );
		$type           = get_post_meta( $template->ID, '_ahf_template_type', true );
		$header_type    = get_post_meta( $template->ID, '_ahf_header_type', true );

		if ( athemes_addons_get_display_conditions( $conditions, false ) ) {
			if ( 'sticky-header' === $template_type && 'sticky' === $header_type ) {
				$results[] = $template->ID;
			} elseif ( $template_type === $type && 'sticky' !== $header_type ) {
				$results[] = $template->ID;
			}
		} elseif ( 'singular' === $template_type && 'error404' === $type && is_404() ) {
			$results[] = $template->ID;
		}
	}

	$results = end( $results );

	return $results;
}

/**
 * Display Conditions
 */
function athemes_addons_get_display_conditions( $rules, $default = false, $mod_default = '[]' ) {

	if ( empty( $rules ) ) {
		return $default;
	}

	$rules  = json_decode( $rules, true );
	
	// Default
	$result = '';

	if ( ! empty( $rules ) ) {

		foreach ( $rules as $rule ) {

			$object_id = ( ! empty( $rule['id'] ) ) ? intval( $rule['id'] ) : 0;
			$condition = ( ! empty( $rule['condition'] ) ) ? $rule['condition'] : '';
			$boolean   = ( ! empty( $rule['type'] ) && $rule['type'] === 'include' ) ? true : false;

			// Entrie Site
			if ( $condition === 'all' ) {
				$result = $boolean;
			}

			// Basic
			if ( $condition === 'singular' && is_singular() ) {
				$result = $boolean;
			}

			if ( $condition === 'archive' && is_archive() ) {
				$result = $boolean;
			}

			// Posts
			if ( $condition === 'single-post' && is_singular( 'post' ) ) {
				$result = $boolean;
			}

			if ( $condition === 'post-archives' && is_archive() ) {
				$result = $boolean;
			}

			if ( $condition === 'post-categories' && is_category() ) {
				$result = $boolean;
			}

			if ( $condition === 'post-tags' && is_tag() ) {
				$result = $boolean;
			}

			if ( $condition === 'cpt-post-id' && get_queried_object_id() === $object_id ) {
				$result = $boolean;
			}

			if ( $condition === 'cpt-term-id' && get_queried_object_id() === $object_id ) {
				$result = $boolean;
			}

			if ( $condition === 'cpt-taxonomy-id' && is_tax( $object_id ) ) {
				$result = $boolean;
			}

			// Pages
			if ( $condition === 'single-page' && is_page() ) {
				$result = $boolean;
			}

			// Custom Post Types
			$available_post_types = get_post_types( array( 'show_in_nav_menus' => true ), 'objects' );
			$available_post_types = array_diff( array_keys( $available_post_types ), array( 'post', 'page', 'product', 'e-landing-page' ) );
			
			if ( ! empty( $available_post_types ) ) {

				foreach ( $available_post_types as $post_type ) {
					
					if ( $condition === 'cpt-' . $post_type . '-archives' && is_post_type_archive( $post_type ) ) {
						$result = $boolean;
					}

					if ( $condition === 'cpt-' . $post_type . '-single' && is_singular( $post_type ) ) {
						$result = $boolean;
					}
				}

			}

			// WooCommerce
			if ( class_exists( 'WooCommerce' ) ) {
				
				if ( $condition === 'shop' && is_shop() ) {
					$result = $boolean;
				}
	
				if ( $condition === 'single-product' && is_singular( 'product' ) ) {
					$result = $boolean;
				}
	
				if ( $condition === 'product-archives' && ( is_shop() || is_product_tag() || is_product_category() ) ) {
					$result = $boolean;
				}
	
				if ( $condition === 'product-categories' && is_product_category() ) {
					$result = $boolean;
				}
	
				if ( $condition === 'product-tags' && is_product_tag() ) {
					$result = $boolean;
				}

				if ( $condition === 'product-id' && get_queried_object_id() === $object_id ) {
					$result = $boolean;
				}

				if ( $condition === 'cart' && is_cart() ) {
					$result = $boolean;
				}

				if ( $condition === 'checkout' && is_checkout() ) {
					$result = $boolean;
				}

			}

			// Specific
			if ( $condition === 'post-id' && get_queried_object_id() === $object_id ) {
				$result = $boolean;
			}

			if ( $condition === 'page-id' && get_queried_object_id() === $object_id ) {
				$result = $boolean;
			}

			if ( $condition === 'category-id' && is_category() && get_queried_object_id() === $object_id ) {
				$result = $boolean;
			}

			if ( $condition === 'tag-id' && is_tag() && get_queried_object_id() === $object_id ) {
				$result = $boolean;
			}

			if ( $condition === 'author-id' && get_the_author_meta( 'ID' ) === $object_id ) {
				$result = $boolean;
			}

			// User Auth
			if ( $condition === 'logged-in' && is_user_logged_in() ) {
				$result = $boolean;
			}

			if ( $condition === 'logged-out' && ! is_user_logged_in() ) {
				$result = $boolean;
			}

			// User Roles
			if ( substr( $condition, 0, 10 ) === 'user_role_' && is_user_logged_in() ) {

				$user_role  = str_replace( 'user_role_', '', $condition );
				$user_id    = get_current_user_id();
				$user_roles = get_userdata( $user_id )->roles;

				if ( in_array( $user_role, $user_roles, true ) ) {
					$result = $boolean;
				}

			}

			// Others
			if ( $condition === 'front-page' && is_front_page() ) {
				$result = $boolean;
			}

			if ( $condition === 'blog' && is_home() ) {
				$result = $boolean;
			}

			if ( $condition === '404' && is_404() ) {
				$result = $boolean;
			}

			if ( $condition === 'search' && is_search() ) {
				$result = $boolean;
			}

			if ( $condition === 'author' && is_author() ) {
				$result = $boolean;
			}

			if ( $condition === 'privacy-policy-page' && is_page() ) {

				$post_id    = get_the_ID();
				$privacy_id = get_option( 'wp_page_for_privacy_policy' );

				if ( intval( $post_id ) === intval( $privacy_id ) ) {
					$result = $boolean;
				}

			}

		}

	}

	if ( $result ) {
		$result = apply_filters( 'athemes_addons_display_conditions_result', $result, $rules );
	}

	return $result;
}


