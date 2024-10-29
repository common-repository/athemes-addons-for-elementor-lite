<?php
/**
 * aThemes_Addons_Ajax_Callbacks Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'aThemes_Addons_Ajax_Callbacks' ) ) {

	class aThemes_Addons_Ajax_Callbacks {

		/**
		 * Constructor.
		 * 
		 */
		public function __construct() {

			// Mailchimp subscribe.
			add_action( 'wp_ajax_aafe_mailchimp_subscribe', array( $this, 'mailchimp_subscribe' ) );
			add_action( 'wp_ajax_nopriv_aafe_mailchimp_subscribe', array( $this, 'mailchimp_subscribe' ) );

			//Product filter.
			add_action( 'wp_ajax_aafe_product_filter', array( $this, 'product_filter' ) );
			add_action( 'wp_ajax_nopriv_aafe_product_filter', array( $this, 'product_filter' ) );

			//Select 2.
			add_action( 'wp_ajax_aafe_posts_filter_autocomplete', [ $this, 'posts_filter_autocomplete' ] );
			add_action( 'wp_ajax_aafe_get_posts_value_titles', [ $this, 'get_posts_value_titles' ] );
		}

		/**
		 * Mailchimp subscribe.
		 */
		public function mailchimp_subscribe() {
			$api_key = get_option( 'athemes-addons-settings' )['aafe_mailchimp_api_key'];

			if ( empty( $api_key ) ) {
				wp_send_json_error( __( 'Mailchimp API key is not set', 'athemes-addons-elementor' ) );
			}

			check_ajax_referer( 'aafe-mailchimp-nonce', 'nonce' );

			if ( !isset($_POST['fields'] ) ) {
				return;
			}
		
			$list_id    = ( isset( $_POST['listId'] ) ) ? sanitize_text_field( wp_unslash( $_POST['listId'] ) ) : '';
			$fields     = isset( $_POST['fields'] ) ? urldecode( wp_unslash($_POST['fields'])  ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- fields sanitized below

			parse_str( $fields, $settings);
			
			$merge_fields = array(
				'FNAME' => !empty($settings['aafe-mailchimp-fname']) ? sanitize_text_field($settings['aafe-mailchimp-fname']) : '',
				'LNAME' => !empty($settings['aafe-mailchimp-lname']) ? sanitize_text_field($settings['aafe-mailchimp-lname']) : '',
			);
		
			$body_params = array(
				'email_address' => $settings['aafe-mailchimp-email'],
				'status'        => 'subscribed',
				'merge_fields'  => $merge_fields,
			);
		
			$response = wp_remote_post(
				'https://' . substr($api_key, strpos(
					$api_key,
					'-'
				) + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($settings['aafe-mailchimp-email'])),
				[
					'method' => 'PUT',
					'headers' => [
						'Content-Type' => 'application/json',
						'Authorization' => 'Basic ' . base64_encode('user:' . $api_key), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					],
					'body' => wp_json_encode( $body_params ),
				]
			);
		
			if (!is_wp_error($response)) {
				$response = json_decode(wp_remote_retrieve_body($response));
		
				if (!empty($response)) {
					if ( $response->status === 'subscribed' ) {
						wp_send_json([
							'status' => 'subscribed',
						]);
					} else {
						wp_send_json([
							'status' => $response->title,
						]);
					}
				}
			}
		}

		/**
		 * Product filter.
		 */
		public function product_filter() {
			check_ajax_referer( 'aafe-product-filter-nonce', 'nonce' );

			$categories         = ( isset( $_POST['categories'] ) ) ? sanitize_text_field( wp_unslash( $_POST['categories'] ) ) : array();
			$display_mode       = ( isset( $_POST['display_mode'] ) ) ? sanitize_text_field( wp_unslash( $_POST['display_mode'] ) ) : '';
			$filter_term        = ( isset( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
			$posts_per_page     = ( isset( $_POST['posts_per_page'] ) ) ? sanitize_text_field( wp_unslash( $_POST['posts_per_page'] ) ) : 3;
			$offset             = ( isset( $_POST['offset'] ) ) ? sanitize_text_field( wp_unslash( $_POST['offset'] ) ) : 0;
			$orderby            = ( isset( $_POST['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'date';
			$order              = ( isset( $_POST['order'] ) ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : 'desc';
			$load_more          = ( isset( $_POST['load_more'] ) ) ? sanitize_text_field( wp_unslash( $_POST['load_more'] ) ) : false;
			$max_pages          = ( isset( $_POST['max_pages'] ) ) ? sanitize_text_field( wp_unslash( $_POST['max_pages'] ) ) : 1;
			$current_page       = ( isset( $_POST['current_page'] ) ) ? sanitize_text_field( wp_unslash( $_POST['current_page'] ) ) : 1;
			$widget_id          = ( isset( $_POST['widget_id'] ) ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
			$page_id            = ( isset( $_POST['page_id'] ) ) ? sanitize_text_field( wp_unslash( $_POST['page_id'] ) ) : '';

			if ( empty( $widget_id ) || empty( $page_id ) ) {
				wp_send_json_error( __( 'Invalid widget ID or page ID', 'athemes-addons-elementor' ) );
			}

			// Get widget settings.
			$settings = aThemes_Addons_Helper::get_widget_settings( $page_id, $widget_id );

			if ( empty( $settings ) ) {
				wp_send_json_error( __( 'Invalid widget settings', 'athemes-addons-elementor' ) );
			}
			
			$query_args = array(
				'post_type'         => 'product',
				'posts_per_page'    => $posts_per_page,
				'offset'            => $offset,
				'orderby'           => $orderby,
				'order'             => $order,
			);

			if ( 'all' === $filter_term ) {
				$terms = explode( ',', $categories );
			} else {
				$terms = $filter_term;
			}

			if ( '' !== $categories ) {
				$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $terms,
					),
				);
			}

			switch ( $display_mode ) {
				case 'featured':
					$query_args['meta_query'] = WC()->query->get_meta_query(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					$query_args['post__in'] = wc_get_featured_product_ids();
					break;
				case 'sale':
					$query_args['meta_query'] = WC()->query->get_meta_query(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					$query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
					break;
			}

			if ( true == $load_more ) { // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual
				$query_args['paged'] = $current_page + 1;

				unset( $query_args['offset'] );

				if ( $current_page > $max_pages ) {
					wp_send_json_error( __( 'No more products to load', 'athemes-addons-elementor' ) );
				}
			}

			$products = new WP_Query( $query_args );

			$max_pages = $products->max_num_pages;


			$content = '';

			if ( $products->have_posts() ) {
				while ( $products->have_posts() ) {

					$products->the_post();
				
					ob_start();
					include ATHEMES_AFE_DIR . 'inc/modules/widgets/woo-product-grid/templates/product-template-' . $settings['product_template'] . '.php';
					$content .= ob_get_clean();

					$content .= '<span class="maxpages" data-maxpages="' . esc_attr( $max_pages ) . '"></span>';

				}
			}
			
			printf( '%s', $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			wp_reset_postdata();

			wp_die();
		}

		public function posts_filter_autocomplete() {

			$post_type   = 'post';
			$source_name = 'post_type';
	
			if ( ! empty( $_POST['post_type'] ) ) {
				$post_type = sanitize_text_field( $_POST['post_type'] );
			}
	
			if ( ! empty( $_POST['source_name'] ) ) {
				$source_name = sanitize_text_field( $_POST['source_name'] );
			}
	
			$search  = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
			$results = $post_list = [];
			switch ( $source_name ) {
				case 'taxonomy':
					$args = [
						'hide_empty' => false,
						'orderby'    => 'name',
						'order'      => 'ASC',
						'search'     => $search,
						'number'     => '5',
					];
	
					if ( $post_type !== 'all' ) {
						$args['taxonomy'] = $post_type;
					}
	
					$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
					break;
				case 'user':
					if ( ! current_user_can( 'list_users' ) ) {
						$post_list = [];
						break;
					}
	
					$users = [];
	
					foreach ( get_users( [ 'search' => "*{$search}*" ] ) as $user ) {
						$user_id           = $user->ID;
						$user_name         = $user->display_name;
						$users[ $user_id ] = $user_name;
					}
	
					$post_list = $users;
					break;
				default:
					$post_info = get_posts( [
						'post_type'      => $post_type,
						's'              => $search,
						'posts_per_page' => 5,
					] );
					$post_list = wp_list_pluck( $post_info, 'post_title', 'ID' );
			}
	
			if ( ! empty( $post_list ) ) {
				foreach ( $post_list as $key => $item ) {
					$results[] = [ 'text' => $item, 'id' => $key ];
				}
			}
	
			wp_send_json( [ 'results' => $results ] );

			wp_die();
		}

		public function get_posts_value_titles() {

			if ( empty( $_POST['id'] ) ) {
				wp_send_json_error( [] );
			}
	
			if ( empty( array_filter( $_POST['id'] ) ) ) {
				wp_send_json_error( [] );
			}
			$ids         = array_map( 'intval', $_POST['id'] );
			$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( $_POST['source_name'] ) : '';
	
			switch ( $source_name ) {
				case 'taxonomy':
					$args = [
						'hide_empty' => false,
						'orderby'    => 'name',
						'order'      => 'ASC',
						'include'    => implode( ',', $ids ),
					];
	
					if ( $_POST['post_type'] !== 'all' ) {
						$args['taxonomy'] = sanitize_text_field( $_POST['post_type'] );
					}
	
					$response = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
					break;
				case 'user':
					$users = [];
	
					foreach ( get_users( [ 'include' => $ids ] ) as $user ) {
						$user_id           = $user->ID;
						$user_name         = $user->display_name;
						$users[ $user_id ] = $user_name;
					}
	
					$response = $users;
					break;
				default:
					$post_info = get_posts( [
						'post_type' => sanitize_text_field( $_POST['post_type'] ),
						'include'   => implode( ',', $ids )
					] );
					$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
			}
	
			if ( ! empty( $response ) ) {
				wp_send_json_success( [ 'results' => $response ] );
			} else {
				wp_send_json_error( [] );
			}

			wp_die();
		}		
	}

	new aThemes_Addons_Ajax_Callbacks();

}
