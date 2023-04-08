<?php
if ( ! class_exists( 'Slugs' ) ) {
	class Slugs {

		public $wc_product_slug = '';
		public $product_slug = '';

		function __construct() {
			// set default slugs
			$wc_permalinks         = get_option( 'woocommerce_permalinks' );
			$this->wc_product_slug = $wc_permalinks['product_base'];
			$this->product_slug     = get_option( 'lisfinity-options' )['_slug-product'];

			// permalink hooks:
			if ( $this->wc_product_slug !== $this->product_slug ) {
				add_filter( 'generate_rewrite_rules', [ &$this, 'permalink_rewrite_rule' ], 0 );
				add_filter( 'query_vars', [ $this, 'permalink_query_vars' ], 0 );
				add_filter( 'admin_init', [ &$this, 'permalink_flush_rewrite_rules' ] );
				//add_action( "parse_request", array( &$this, "permalink_parse_request" ) );
				add_filter( 'post_type_link', [ &$this, 'change_post_type_link' ], 1, 3 );
			}
		}

		/**************************************************************************
		 * Add your rewrite rule.
		 * The rewrite rules array is an associative array with permalink URLs as regular
		 * expressions (regex) keys, and the corresponding non-permalink-style URLs as values
		 * For the rule to take effect, For the rule to take effect, flush the rewrite cache,
		 * either by re-saving permalinks in Settings->Permalinks, or running the
		 * my_permalink_flush_rewrite_rules() method below.
		 *
		 * @see http://codex.wordpress.org/Custom_Queries#Permalinks_for_Custom_Archives
		 *
		 * @param object $wp_rewrite
		 *
		 * @return array New permalink structure
		 **************************************************************************/
		function permalink_rewrite_rule( $wp_rewrite ) {
			if ( empty( $this->product_slug ) || $this->wc_product_slug === $this->product_slug ) {
				return $wp_rewrite->rules;
			}
			$new_rules = array(
				"{$this->product_slug}/(.*)$" => sprintf( "index.php?post_type=product&name=%s", $wp_rewrite->preg_index( 1 ) )
			);

			$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

			return $wp_rewrite->rules;
		}

		/**************************************************************************
		 * Add your custom query variables.
		 * To make sure that our parameter value(s) gets saved,when WordPress parse the URL,
		 * we have to add our variable(s) to the list of query variables WordPress
		 * understands (query_vars filter)
		 *
		 * @see http://codex.wordpress.org/Custom_Queries
		 *
		 * @param array $query_vars
		 *
		 * @return array $query_vars with custom query variables
		 **************************************************************************/
		function permalink_query_vars( $query_vars ) {
			if ( empty( $this->product_slug ) || $this->wc_product_slug === $this->product_slug ) {
				return $query_vars;
			}
			$query_vars[] = $this->product_slug;

			return $query_vars;
		}

		/**************************************************************************
		 * Parses a URL into a query specification
		 * This is where you should add your code.
		 *
		 * @see http://codex.wordpress.org/Query_Overview
		 *
		 * @param $wp_query
		 *
		 * @return string URL to demonstrate custom permalink
		 **************************************************************************/
		function permalink_parse_request( $wp_query ) {
			if ( isset( $wp_query->query_vars[ $this->product_slug ] ) ) {
				//printf( "<pre>%s</pre>", print_r( $wp_query->query_vars, true ) );
				//exit( 0 );

				return $wp_query;
			}
		}

		/**************************************************************************
		 * Flushes the permalink structure.
		 * flush_rules is an extremely costly function in terms of performance, and
		 * should only be run when changing the rule.
		 *
		 * @see http://codex.wordpress.org/Rewrite_API/flush_rules
		 **************************************************************************/
		function permalink_flush_rewrite_rules() {
			if ( empty( $this->product_slug ) || $this->wc_product_slug === $this->product_slug ) {
				return;
			}
			$rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
			if ( ! isset( $rules["{$this->product_slug}/(.*)$"] ) ) {
				//lisfinity_dd( 'flash rules' );
				global $wp_rewrite;
				$wp_rewrite->flush_rules();
			}
		}

		/**************************************************************************
		 * Change the link to the custom post type if there's an option set for it
		 *
		 * @param $post_link
		 * @param int $id
		 *
		 * @return string|string[]
		 **************************************************************************/
		function change_post_type_link( $post_link, $id = 0 ) {
			if ( empty( $this->product_slug ) || $this->wc_product_slug === $this->product_slug ) {
				return $post_link;
			}
			if ( 'product' === get_post_type( $id ) ) {
				$product = wc_get_product( $id );
				if ( 'listing' === $product->get_type() ) {
					return str_replace( $this->wc_product_slug, '/' . $this->product_slug, $post_link );
				}
			}

			return $post_link;
		}
	} //End Class
} //End if class exists statement
