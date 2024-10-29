<?php
/**
 * aThemes_Addons_Posts_Helper Class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'aThemes_Addons_Posts_Helper' ) ) {

	class aThemes_Addons_Posts_Helper {

		/**
		 * The single class instance.
		 */
		private static $instance = null;
		
        // Registered post types.
        protected static $post_types = array();

        // Post Taxonomies.
        protected static $post_tax   = array();

        // Taxonomy terms.
        protected static $tax_terms  = array();

        // Taxonomies.
        protected static $taxonomies = array();

		// Page limit.
		public static $page_limit;

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'pre_get_posts', array( $this, 'fix_query_offset' ), 1 );
			add_filter( 'found_posts', array( $this, 'fix_found_posts_query' ), 1, 2 );
			add_action( 'wp_ajax_athemes_addons_update_posts_filter', array( $this, 'get_posts_list' ) );
		}

		/**
		 * Instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get Posts.
		 * 
		 * @return array
		 */
		public static function get_post_types() {
			if ( ! empty( self::$post_types ) ) {
				return self::$post_types;
			}
	
			$post_types = get_post_types(
				array(
					'public' => true,
				),
				'objects'
			);
	
			$options = array();
	
			foreach ( $post_types as $post_type ) {
				$options[ $post_type->name ] = $post_type->label;
			}
	
			self::$post_types = $options;
	
			return $options;
		}

		/**
		 * Get Post Taxonomies.
		 * 
		 * @return array
		 */
		public static function get_taxonomies( $type ) {

			$taxonomies = get_object_taxonomies( $type, 'objects' );
			$data       = array();
	
			foreach ( $taxonomies as $tax_slug => $tax ) {
	
				if ( ! $tax->public || ! $tax->show_ui ) {
					continue;
				}
	
				$data[ $tax_slug ] = $tax;
			}
	
			return $data;
		}

		/**
		 * Get query args to use in WP_Query.
		 * 
		 * @return array
		 */
		public static function get_query_args( $settings ) {
	
			$paged     = self::get_paged();
			$tax_count = 0;
	
			$post_type = $settings['post_type_filter'];
			$post_id   = get_the_ID();
	
			$post_args = array(
				'post_type'        => $post_type,
				'posts_per_page'   => empty( $settings['number'] ) ? 9999 : $settings['number'],
				'paged'            => $paged,
				'post_status'      => 'publish',
				'suppress_filters' => false,
			);
	
			$post_args['orderby'] = $settings['orderby'];
			$post_args['order']   = $settings['order'];
	
			$excluded_posts = array();
	
			// Get all the taxanomies associated with the post type.
			$taxonomy = self::get_taxonomies( $post_type );

			if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {

				// Get all taxonomy values under the taxonomy.

				$tax_count = 0;
				foreach ( $taxonomy as $index => $tax ) {

					if ( ! empty( $settings[ 'tax_' . $index . '_' . $post_type . '_filter' ] ) ) {

						$operator = $settings[ $index . '_' . $post_type . '_filter_rule' ];

						$post_args['tax_query'][] = array(
							'taxonomy' => $index,
							'field'    => 'slug',
							'terms'    => $settings[ 'tax_' . $index . '_' . $post_type . '_filter' ],
							'operator' => $operator,
						);
						$tax_count++;
					}
				}
			}

	
			if ( 0 < $settings['offset'] ) {
				$post_args['offset_to_fix'] = $settings['offset'];
			}
	
			if ( 'yes' === $settings['ignore_sticky_posts'] ) {
				$excluded_posts = array_merge( $excluded_posts, get_option( 'sticky_posts' ) );
			}

			if ( ! empty( $settings['blog_posts_filter'] ) ) {
				if ( 'post__not_in' === $settings['posts_filter_rule'] ) {
					$excluded_posts = array_merge( $excluded_posts, $settings['blog_posts_filter'] );
				} else {
					$post_args['post__in'] = $settings['blog_posts_filter'];
				}
			}
	
			$post_args['post__not_in'] = $excluded_posts;
	
			return $post_args;
		}       

		/**
		 * Get paged number.
		 *
		 * @return int
		 */
		public static function get_paged() {

			global $wp_the_query, $paged;

			$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : false;

			if ( $nonce && wp_verify_nonce( $nonce, 'aafe-posts-widget-nonce' ) ) {
				if ( isset( $_POST['page_number'] ) && '' !== $_POST['page_number'] ) {
					return sanitize_text_field( wp_unslash( $_POST['page_number'] ) );
				}
			}

			$paged_qv = $wp_the_query->get( 'paged' );

			if ( is_numeric( $paged_qv ) ) {
				return $paged_qv;
			}

			$page_qv = $wp_the_query->get( 'page' );

			if ( is_numeric( $page_qv ) ) {
				return $page_qv;
			}

			if ( is_numeric( $paged ) ) {
				return $paged;
			}

			return 0;
		}

		/**
		 * Get Posts. Used for custom posts filter.
		 * 
		 * @return array
		 */
		public static function get_posts_list() {

			check_ajax_referer( 'aafe-posts-widget-nonce', 'nonce' );

			$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';

			if ( empty( $post_type ) ) {
				wp_send_json_error( __( 'There are no posts.', 'athemes-addons-elementor' ) );
			}

			$list = get_posts(
				array(
					'post_type'              => $post_type,
					'posts_per_page'         => -1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				)
			);

			$options = array();

			if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
				foreach ( $list as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			}

			wp_send_json_success( wp_json_encode( $options ) );
		}

		/**
		 * Get posts list
		 *
		 * @param string $post_type  post type.
		 *
		 * @return array
		 */
		public static function get_default_posts_list( $post_type ) {
			$list = get_posts(
				array(
					'post_type'              => $post_type,
					'posts_per_page'         => -1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					'fields'                 => array( 'ids' ),
				)
			);

			$options = array();

			if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
				foreach ( $list as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			}

			return $options;
		}       

		/**
		 * Fix Query Offset.
		 *
		 */
		public function fix_query_offset( &$query ) {

			if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
				if ( $query->is_paged ) {
					$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
				} else {
					$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
				}
			}
		}

		/**
		 * Fix Found Posts Query.
		 *
		 */
		public function fix_found_posts_query( $found_posts, $query ) {

			$offset_to_fix = $query->get( 'offset_to_fix' );

			if ( $offset_to_fix ) {
				$found_posts -= $offset_to_fix;
			}

			return $found_posts;
		}       

		/**
		 * Get pagination.
		 */
		public static function get_pagination( $settings ) {

			if ( 'yes' !== $settings['paginated_posts'] ) {
				return;
			}

			$pages = self::$page_limit;

			if ( ! empty( $settings['max_pages'] ) ) {
				$pages = min( $settings['max_pages'], $pages );
			}

			$paged = self::get_paged();

			$current_page = $paged;

			if ( ! $current_page ) {
				$current_page = 1;
			}

			$nav_links = paginate_links(
				array(
					'current'   => $current_page,
					'total'     => $pages,
					'prev_next' => 'yes' === $settings['pagination_strings'] ? true : false,
					'prev_text' => sprintf( '« %s', $settings['pagination_prev_text'] ),
					'next_text' => sprintf( '%s »', $settings['pagination_next_text'] ),
					'type'      => 'array',
				)
			);

			if ( ! is_array( $nav_links ) ) {
				return;
			}

			?>
			<nav class="pagination-container" role="navigation" aria-label="<?php echo esc_attr( __( 'Pagination', 'athemes-addons-elementor' ) ); ?>">
				<?php echo wp_kses_post( implode( PHP_EOL, $nav_links ) ); ?>
			</nav>
			<?php
		}

		/**
		 * Get terms list.
		 *
		 * @param string $taxonomy  Taxonomy.
		 * @param string $key       Key.
		 *
		 * @return array
		 */
		public static function get_terms_list( $taxonomy = 'category', $key = 'term_id' ) {
			$options = array();
			
			$terms = get_terms( array(
				'taxonomy' => $taxonomy,
				'hide_empty' => true,
			) );
	
			if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$options[$term->{$key}] = $term->name;
				}
			}
	
			return $options;
		}       
	}
}