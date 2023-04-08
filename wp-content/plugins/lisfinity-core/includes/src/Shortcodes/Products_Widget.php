<?php


namespace Lisfinity\Shortcodes;


use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Models\Taxonomies\TaxonomiesAdminModel;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Number_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Custom_Fields_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Label_On_Sale_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Promoted_Icon_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Tabs_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Title_Typography;


class Products_Widget extends Widget_Base {


	public $types = [];
	public $product_location = [];
	private $lisfinity_is_elementor;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->types            = lisfinity_available_promotion_product_types();
		$this->product_location = [
			'owner_location'   => __( 'Owner Location', 'lisfinity-core' ),
			'listing_location' => __( 'Listing Location', 'lisfinity-core' )
		];
	}

	/**
	 * Get the name of the shortcode
	 * -----------------------------
	 *
	 * @return string
	 */
	public function get_name() {
		return 'products';
	}

	/**
	 * Get the displayed title of the shortcode
	 * ----------------------------------------
	 *
	 * @return string
	 */
	public function get_title() {
		return sprintf( __( '%s Ads', 'lisfinity-core' ), '<strong>Lisfinity > </strong>' );
	}

	/**
	 * Get the icon for the shortcode
	 * ------------------------------
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-newspaper-o';
	}

	/**
	 * Set the categories where the shortcode will be displayed
	 * --------------------------------------------------------
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'lisfinity' ];
	}

	/**
	 * Register shortcode controls
	 * ---------------------------
	 */

	protected function register_controls() {
		$id           = lisfinity_get_option( 'page-single-listing' );
		$elementor_id = ! empty( $_GET['post'] ) ? $_GET['post'] : false;

		$tab_repeater = new Repeater();

		if ( $id === $elementor_id || is_singular( 'product' ) ) {
			$this->start_controls_section(
				'products_title',
				[
					'label' => __( 'Title', 'lisfinity-core' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->title_content();

			$this->end_controls_section();
			$this->start_controls_section(
				'products_title_single_ad_style',
				[
					'label' => __( 'Title', 'lisfinity-core' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->title_style();

			$this->end_controls_section();

		}
		$this->start_controls_section(
			'products_feed',
			[
				'label' => __( 'Listings Feed', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->start_controls_tabs(
			'feed_tabs'
		);

		$this->start_controls_tab(
			'feed',
			[
				'label' => __( 'Listing Feeds', 'lisfinity-core' ),
			]
		);

		// control | title.
		$tab_repeater->add_control(
			'tab_title',
			[
				'label'       => __( 'Tab Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Latest Listings', 'lisfinity-core' ),
				'description' => __( 'Enter the title of the tab you wish to create', 'lisfinity-core' ),
			]
		);

		// control | visited looked ads by the user.
		$tab_repeater->add_control(
			'visited',
			[
				'label'        => __( 'Display Recently Visited Listings', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Include', 'lisfinity-core' ),
					'no'  => __( 'Do Not Include', 'lisfinity-core' ),
				],
				'default'      => 'no',
				'return_value' => 'yes',
				'description'  => __( 'Choose to display visited looked listings by the current user.', 'lisfinity-core' ),
				'separator'    => 'before',
			]
		);

		// control | type.
		$groups_model = new GroupsAdminModel();
		$tab_repeater->add_control(
			'type',
			[
				'label'       => __( 'Listing Type', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $groups_model->format_options_for_select( true ),
				'default'     => '',
				'description' => __( 'Choose listing types that you allow to be displayed or leave empty to enable them all.', 'lisfinity-core' ),
				'condition'   => [
					'visited!' => 'yes',
				],
			]
		);

		// control | price type.
		$this->add_control(
			'auction_settings_heading',
			[
				'label'     => __( 'Auction Settings', 'lisfinity-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'price_type' => 'auction',
				],
			]
		);

		$tab_repeater->add_control(
			'price_type',
			[
				'label'       => __( 'Price Type', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => lisfinity_available_price_types(),
				'default'     => '',
				'description' => __( 'Choose the price type that you wish to display listings from or leave empty to enable them all.', 'lisfinity-core' ),
			]
		);

		$tab_repeater->add_control(
			'price_type_expiring',
			[
				'label'        => __( 'Near Expiration', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Include', 'lisfinity-core' ),
					'no'  => __( 'Do Not Include', 'lisfinity-core' ),
				],
				'default'      => '',
				'return_value' => 'yes',
				'description'  => __( 'Display only auctions that are near expiration', 'lisfinity-core' ),
				'condition'    => [
					'price_type' => 'auction',
				],
			]
		);

		$tab_repeater->add_control(
			'price_type_expiring_hours',
			[
				'label'       => __( 'Near Expiration Hours', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Set the near expiration amount of hours for the listings to display', 'lisfinity-core' ),
				'default'     => '3',
				'condition'   => [
					'price_type'          => 'auction',
					'price_type_expiring' => 'yes',
				],
			]
		);

		// control | taxonomies.
		$taxonomies_model = new TaxonomiesAdminModel();
		$groups           = $groups_model->get_groups_slugs();
		$options          = $groups_model->get_options();
		if ( empty( $options ) ) {
			$groups[]  = 'common';
			$options[] = [
				'single_name' => 'Common',
				'plural_name' => 'Commons',
				'slug'        => 'common',
			];
		}

		$slugs = array_column( $options, 'slug' );
		if ( ! empty( $taxonomies_model->get_options() ) ) {
			foreach ( $taxonomies_model->get_options() as $group => $fields ) {
				$group_key = array_search( $group, $slugs );
				if ( ! empty( $group ) && ! empty( $fields ) ) {
					$tab_repeater->add_control(
						"{$group}-heading",
						[
							'label'     => sprintf( __( '%s Taxonomies', 'lisfinity-core' ), $options[ $group_key ]['plural_name'] ?? $group ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
							'condition' => [
								'type' => $group,
							],
						]
					);
					$taxonomies_count = 1;
					foreach ( $fields as $taxonomy ) {
						if ( in_array( $group, $slugs ) && ! empty( $taxonomy['slug'] ) ) {
							$tab_repeater->add_control(
								"tax[{$taxonomy['slug']}]",
								[
									'label'       => sprintf( __( 'Taxonomy %s', 'lisfinity-core' ), $taxonomy['single_name'] ),
									'type'        => Controls_Manager::SELECT2,
									'multiple'    => true,
									'options'     => ! lisfinity_is_wpml() ? $taxonomies_model->format_taxonomy_for_select($taxonomy['slug'], true ) : [],
									'default'     => '',
									'description' => __( 'Choose listing types that you allow to be displayed or leave empty to enable them all.', 'lisfinity-core' ),
									'condition'   => [
										'type' => $taxonomy['field_group'],
									],
									'separator'   => count( $fields ) === $taxonomies_count ? 'after' : 'none',
								]
							);
							$taxonomies_count += 1;
						}
					}
				}
			}
		}

		// control | number of products.
		$tab_repeater->add_control(
			'number',
			[
				'label'       => __( 'Number of Listings to Show', 'lisfinity-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'default'     => 3,
				'description' => __( 'Choose the number of listings that will be displayed.', 'lisfinity-core' ),
			]
		);

		// control | handpick.
		$tab_repeater->add_control(
			'handpicked',
			[
				'label'       => __( 'Listing', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => lisfinity_format_post_select( [
					'post_type' => 'product',
					'tax_query' => [
						[
							'taxonomy' => 'product_type',
							'field'    => 'name',
							'terms'    => 'listing',
							'operator' => 'IN',
						],
					],
				] ),
				'description' => __( 'Choose listings that you wish to be displayed', 'lisfinity-core' ),
				'condition'   => [
					'visited!' => 'yes',
				],
			]
		);

		// control | include promotions.
		$tab_repeater->add_control(
			'promoted',
			[
				'label'        => __( 'Promoted Listings', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Include', 'lisfinity-core' ),
					'no'  => __( 'Do Not Include', 'lisfinity-core' ),
				],
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => __( 'Choose whether promoted listings should be included in a feed. They will be shown first.', 'lisfinity-core' ),
				'condition'    => [
					'visited!' => 'yes',
				],
				'separator'    => 'before',
			]
		);

		$tab_repeater->add_control(
			'non_promoted',
			[
				'label'        => __( 'Non Promoted Listings', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Display', 'lisfinity-core' ),
					'no'  => __( 'Do Not Display', 'lisfinity-core' ),
				],
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => __( 'Choose whether you wish to display non promoted listings too', 'lisfinity-core' ),
				'condition'    => [
					'promoted' => 'yes',
					'visited!' => 'yes',
				],
			]
		);

		$tab_repeater->add_control(
			'sold',
			[
				'label'        => __( 'Sold Listings', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Display', 'lisfinity-core' ),
					'no'  => __( 'Do Not Display', 'lisfinity-core' ),
				],
				'default'      => 'no',
				'return_value' => 'yes',
				'description'  => __( 'Choose whether you wish to display sold listings', 'lisfinity-core' ),
			]
		);

		if ( $id === $elementor_id ) {
			$tab_repeater->add_control(
				'promo_type',
				[
					'label'   => __( 'Promotion Type', 'lisfinity-core' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $this->types,
					'default' => 'home-ads',
				]
			);

			// control | include promotions.
			$tab_repeater->add_control(
				'same_category',
				[
					'label'        => __( 'From Same Category?', 'lisfinity-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'options'      => [
						'yes' => __( 'Display Listings from the same category', 'lisfinity-core' ),
						'no'  => __( 'Do Not Display', 'lisfinity-core' ),
					],
					'default'      => 'no',
					'return_value' => 'yes',
					'description'  => __( 'Display Listings from the same category', 'lisfinity-core' ),
				]
			);
		}

		$tab_repeater->add_control(
			'promoted_sign',
			[
				'label'        => __( 'Add Promoted Sign', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Yes', 'lisfinity-core' ),
					'no'  => __( 'no', 'lisfinity-core' ),
				],
				'default'      => 'no',
				'return_value' => 'yes',
				'description'  => __( 'Choose whether you wish to add the [AD] sign to mark all the ads in this tab as promoted.', 'lisfinity-core' ),
				'separator'    => 'before',
			]
		);

		// control | order of the products.
		$tab_repeater->add_control(
			'order',
			[
				'label'       => __( 'Listings Order', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'asc'  => __( 'Ascending', 'lisfinity-core' ),
					'desc' => __( 'Descending', 'lisfinity-core' ),
				],
				'default'     => 'asc',
				'description' => __( 'Choose order of the listings', 'lisfinity-core' ),
				'condition'   => [
					'promoted' => '',
				],
			]
		);

		// control | order of the products.
		$tab_repeater->add_control(
			'orderby',
			[
				'label'       => __( 'Listings Sorting', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'date'  => __( 'Date', 'lisfinity-core' ),
					'title' => __( 'Name', 'lisfinity-core' ),
					'views' => __( 'Views', 'lisfinity-core' ),
					'rand'  => __( 'Random', 'lisfinity-core' ),
				],
				'default'     => 'date',
				'description' => __( 'Choose sorting of the listings', 'lisfinity-core' ),
				'condition'   => [
					'promoted' => '',
				],
			]
		);

		$this->add_control(
			'product_tabs',
			[
				'label'         => __( 'Listing Tabs', 'lisfinity-core' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $tab_repeater->get_controls(),
				'prevent_empty' => false,
				'description'   => __( 'Choose listing types that you allow to be displayed or leave empty to enable them all.', 'lisfinity-core' ),
				'title_field'   => __( 'Tab: {{{ tab_title }}}', 'lisfinity-core' ),
				'separator'     => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'taxonomies',
			[
				'label' => __( 'Taxonomies', 'lisfinity-core' ),
			]
		);

		if ( ! empty( $groups ) ) {
			$taxonomy_model = new TaxonomiesAdminModel();
			foreach ( $groups as $group ) {
				$group_key = ! empty( $groups_model->get_options() ) ? array_search( $group, $slugs ) : 0;

				if ( ! empty( $options[ $group_key ] ) ) {
					$this->add_control(
						"taxonomy[{$group}]",
						[
							'label'       => sprintf( __( 'Choose %s Taxonomy to Display', 'lisfinity-core' ), '<strong>' . $options[ $group_key ]['plural_name'] . '</strong>' ),
							'label_block' => true,
							'type'        => Controls_Manager::SELECT2,
							'multiple'    => true,
							'options'     => $taxonomy_model->get_taxonomies_by_group( $group, false ),
							'default'     => '',
							'description' => __( 'Choose taxonomy from which you wish to display terms. Leave empty to disable it.', 'lisfinity-core' ),
						]
					);
				}
			}
		}

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'products_style',
			[
				'label' => __( 'Listings Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// control | template.
		$this->add_control(
			'style',
			[
				'label'       => __( 'Style', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'multiple'    => false,
				'options'     => [
					'1'      => __( 'Style 1', 'lisfinity-core' ),
					'2'      => __( 'Style 2', 'lisfinity-core' ),
					'3'      => __( 'Style 3', 'lisfinity-core' ),
					'4'      => __( 'Style 4', 'lisfinity-core' ),
					'custom' => __( 'Custom Style', 'lisfinity-core' ),
				],
				'default'     => '1',
				'description' => __( 'Choose the style of the product box template.', 'lisfinity-core' ),
			]
		);

		$this->add_control(
			'carousel',
			[
				'label'        => __( 'Display as Carousel', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'lisfinity-core' ),
				'label_off'    => __( 'No', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Display listings in a carousel. Will not be display as such in elementor in the current version.', 'lisfinity-core' ),
			]
		);

		// Style | Image overlay
		$this->add_control(
			'overlay',
			[
				'label'       => __( 'Images Overlay', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(0, 0, 0, .2)',
				'description' => __( 'Set the overlay for the listings background images.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .ajax--lead.z-1' => 'background: linear-gradient(0deg, {{VALUE}} 0%, rgba(255,255,255,0) 100%);',
				]
			]
		);

		$this->add_control(
			'display_description',
			[
				'label'        => __( 'Display Description', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Choose if you want to display description instead of the other content.', 'lisfinity-core' ),
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'style' => '1'
				]
			]
		);

		$this->add_responsive_control(
			'box-height',
			[
				'label'       => __( 'Box Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 445,
				],
				'size_units'  => [ 'px' ],
				'condition'   => [
					'style' => [ '1', '2', '3' ],
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--main' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// product image settings.
		$this->start_controls_section(
			'products_style_layout',
			[
				'label' => __( 'Layout', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->products_layout();

		$this->end_controls_section();

		// product box settings.
		$this->start_controls_section(
			'box_settings',
			[
				'label'     => __( 'Box Settings', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->box_styling();

		$this->end_controls_section();

		// product image settings.
		$this->start_controls_section(
			'products_style_image',
			[
				'label'     => __( 'Image Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom'
				]
			]
		);

		$this->product_image_settings();

		$this->end_controls_section();

		// product tabs settings

		$this->start_controls_section(
			'product_tabs_style',
			[
				'label' => __( 'Listing Tabs Style', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->product_tabs_style();

		$this->end_controls_section();

		// product action bookmark settings.

		$this->start_controls_section(
			'products_bookmark_style',
			[
				'label'     => __( 'Action Bookmark Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_action_bookmark_style();

		$this->end_controls_section();

		// product views settings.

		$this->start_controls_section(
			'author_verified_style',
			[
				'label' => __( 'Author Verified Badge', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->verified_author();

		$this->end_controls_section();
		// product views settings.

		$this->start_controls_section(
			'products_views_style',
			[
				'label' => __( 'Display Views', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->products_views();

		$this->end_controls_section();

		// product promoted icon settings.

		$this->start_controls_section(
			'promoted_icon_style',
			[
				'label'     => __( 'Promoted Icon Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_promoted_icon_style();

		$this->end_controls_section();


		// product image settings.
		$this->start_controls_section(
			'products_style_date',
			[
				'label' => __( 'Date', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->products_date_style();

		$this->end_controls_section();

		// product price settings.

		$this->start_controls_section(
			'product_price',
			[
				'label'     => __( 'Listing Price', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);
		$this->start_controls_tabs(
			'product_price_tabs'
		);

		// product price fixed tab.

		$this->start_controls_tab(
			'product_price_fixed',
			[
				'label' => __( 'Fixed', 'lisfinity-core' ),
			]
		);


		$this->products_fixed_price_style();
		$this->end_controls_tab();

		// product price negotiable tab.

		$this->start_controls_tab(
			'product_price_negotiable',
			[
				'label' => __( 'Negotiable', 'lisfinity-core' ),
			]
		);

		$this->products_negotiable_price_style();
		$this->end_controls_tab();

		// product price auction tab.

		$this->start_controls_tab(
			'product_price_auction',
			[
				'label' => __( 'Auction', 'lisfinity-core' ),
			]
		);

		$this->products_auction_price_style();
		$this->end_controls_tab();

		// product price price on call tab.

		$this->start_controls_tab(
			'product_price_price_on_call',
			[
				'label' => __( 'On Call', 'lisfinity-core' ),
			]
		);

		$this->products_price_on_call_style();
		$this->end_controls_tab();

		// product price free tab.

		$this->start_controls_tab(
			'product_price_free',
			[
				'label' => __( 'Free', 'lisfinity-core' ),
			]
		);

		$this->products_price_free_style();
		$this->end_controls_tab();

		// product price free tab.

		$this->start_controls_tab(
			'product_price_on_sale',
			[
				'label' => __( 'On Sale', 'lisfinity-core' ),
			]
		);

		$this->products_price_on_sale_style();

		$this->products_price_label_on_sale_style();

		$this->end_controls_tab();
		$this->end_controls_tabs();


		$this->end_controls_section();

		// product title settings.

		$this->start_controls_section(
			'products_title_style',
			[
				'label'     => __( 'Listing Title Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_title_style();

		$this->end_controls_section();

		// product custom fields settings.

		$this->start_controls_section(
			'custom_fields_style',
			[
				'label'     => __( 'Custom Fields Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_custom_fields_style();

		$this->end_controls_section();

		// product info settings.

		$this->start_controls_section(
			'product_info_style',
			[
				'label'     => __( 'Listing Info Style', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);
		$this->start_controls_tabs(
			'product_info_tabs'
		);
		$this->start_controls_tab(
			'product_info_ratings',
			[
				'label'     => __( 'Ratings', 'lisfinity-core' ),
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_info_ratings_style();
		$this->end_controls_tab();

		$this->start_controls_tab(
			'product_info_location',
			[
				'label'     => __( 'Location', 'lisfinity-core' ),
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->products_info_location_style();
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// product logo settings.

		$this->start_controls_section(
			'products_logo',
			[
				'label' => __( 'Listing Logo', 'lisfinity-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->products_logo_style();

		$this->end_controls_section();


		// product sorting content elements settings.
		$this->start_controls_section(
			'sorting_content_elements',
			[
				'label'     => __( 'Sorting Content Elements', 'lisfinity-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'custom',
				],
			]
		);

		$this->content_elements_sorting();

		$this->end_controls_section();


	}

	public function products_date_style() {
		$this->display_element( 'display_date', esc_html__( 'Display date', 'lisfinity-core' ), 'no' );
		$this->add_control(
			'date_format',
			[
				'label'       => __( 'Date format', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Y-m-d', 'lisfinity-core' ),
				'placeholder' => __( 'Y-m-d', 'lisfinity-core' ),
				'condition'   => [
					'display_date' => 'yes'
				]
			]
		);
		$this->display_element( 'display_date_message', esc_html__( 'Display message with the hours and days', 'lisfinity-core' ), 'no' );
		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'due_date_typography',
				'selector' => '{{WRAPPER}} .due-date ',
			]
		);

		$this->set_text_color( 'due_date_color', 'Set the color of the text', 'rgba(94, 94, 94, 1)', '.due-date' );

	}


	public function title_content() {

		$this->add_control(
			'title_single_ad',
			[
				'label'       => __( 'Title', 'lisfinity-core' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __( 'Enter your title', 'lisfinity-core' ),
				'default'     => __( 'Advertisement', 'lisfinity-core' ),
			]
		);
		$this->add_control(
			'header_size',
			[
				'label'   => __( 'HTML Tag', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					]
				],
				'default'   => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .single-ad-products-title-row' => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

	public function title_style() {

		$this->add_control(
			'title_color_single_ad',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(215, 215, 215, 1)',
				'selectors' => [
					'{{WRAPPER}} .single-ad-products-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'typography-single-ad',
				'selector'       => '{{WRAPPER}} .single-ad-products-title',
				'fields_options' => [
					'typography'  => [ 'default' => 'yes' ],
					'font_size'   => [
						'default' =>
							[ 'size' => 12 ]
					],
					'font_weight' => [
						'default' => 500
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow_single_ad',
				'selector' => '{{WRAPPER}} .single-ad-products-title',
			]
		);

		$this->add_control(
			'padding_title_single_ad',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .single-ad-products-title-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '20',
					'bottom'   => '0',
					'left'     => '20',
					'isLinked' => false,
				],
			]
		);

		$this->add_control(
			'margin_title_single_ad',
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .single-ad-products-title-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '10',
					'left'     => '0',
					'isLinked' => false,
				],
			]
		);
	}

	public function product_image_settings() {
		$this->add_responsive_control(
			'background-image-position',
			[
				'label'       => __( 'Ad Image Position', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'options'     => [
					'column'         => [
						'title' => __( 'Top', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'row'            => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column-reverse' => [
						'title' => __( 'Bottom', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
					'row-reverse'    => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'column',
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product' => 'display: flex; flex-direction: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'large_image_overlay',
			[
				'label'     => __( 'Set Overlay', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .lisfinity-product--main::after' => 'background-color:{{VALUE}};
																		content: "";
																		position: absolute;
																		display: block;
																		z-index: 10;
																		  top: 0;
																		  left: 0;
																		  height: 100%;
																		  width: 100%;'
				],
			]
		);

		$this->add_responsive_control(
			'background-image-position-centered',
			[
				'label'       => __( 'Ad Image Position Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Start', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'eicon-dot-circle-o',
					],
					'flex-end'   => [
						'title' => __( 'End', 'lisfinity-core' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'toggle'      => false,
				'default'     => 'center',
				'condition'   => [
					'_image_position' => 'absolute',
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product' => 'align-items: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'image-fit',
			[
				'label'       => __( 'Image Fit Mode', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'    => __( 'Default', 'lisfinity-core' ),
					'contain'    => __( 'Contain', 'lisfinity-core' ),
					'cover'      => __( 'Cover', 'lisfinity-core' ),
					'fill'       => __( 'Fill', 'lisfinity-core' ),
					'scale-down' => __( 'Scale Down', 'lisfinity-core' ),
				],
				'default'     => 'cover',
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main img' => 'object-fit: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'image-height',
			[
				'label'       => __( 'Custom Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 284,
				],
				'size_units'  => [ 'px' ],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'height: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'_image_width',
			[
				'label'                => __( 'Width', 'lisfinity-core' ),
				'label_block'          => true,
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'inherit',
				'options'              => [
					''        => __( 'Custom', 'lisfinity-core' ),
					'inherit' => __( 'Full Width', 'lisfinity-core' ) . ' (100%)',
					'auto'    => __( 'Inline', 'lisfinity-core' ) . ' (auto)',
				],
				'selectors_dictionary' => [
					'inherit' => '100%',
				],
				'prefix_class'         => 'elementor-widget%s__width-',
				'selectors'            => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'width: {{VALUE}}; max-width: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'_image_custom_width',
			[
				'label'       => __( 'Custom Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition'   => [
					'_image_width' => '',
				],
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'condition' => [
							'_image_width_tablet' => [ 'initial' ],
						],
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'condition' => [
							'_image_width_mobile' => [ 'initial' ],
						],
					],
				],
				'size_units'  => [ 'px', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'_image_position',
			[
				'label'              => __( 'Position', 'lisfinity-core' ),
				'label_block'        => true,
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => [
					''         => __( 'Default', 'lisfinity-core' ),
					'absolute' => __( 'Absolute', 'lisfinity-core' ),
				],
				'prefix_class'       => 'category-',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'position: {{VALUE}}',
				],
			]
		);

		$start = is_rtl() ? __( 'Right', 'lisfinity-core' ) : __( 'Left', 'lisfinity-core' );
		$end   = ! is_rtl() ? __( 'Right', 'lisfinity-core' ) : __( 'Left', 'lisfinity-core' );

		$this->add_control(
			'_image_offset_orientation_h',
			[
				'label'       => __( 'Horizontal Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					],
					'end'   => [
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					],
				],
				'classes'     => 'elementor-control-start-end',
				'render_type' => 'ui',
				'condition'   => [
					'_image_position!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_x',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'     => [
					'size' => '0',
				],
				'size_units'  => [ 'px', '%', 'vw', 'vh' ],
				'selectors'   => [
					'body:not(.rtl) {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main'       => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_h!' => 'end',
					'_image_position!'             => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_x_end',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 0.1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'     => [
					'size' => '0',
				],
				'size_units'  => [ 'px', '%', 'vw', 'vh' ],
				'selectors'   => [
					'body:not(.rtl) {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main'       => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_h' => 'end',
					'_image_position!'            => '',
				],
			]
		);

		$this->add_control(
			'_image_offset_orientation_v',
			[
				'label'       => __( 'Vertical Orientation', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => __( 'Top', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-top',
					],
					'end'   => [
						'title' => __( 'Bottom', 'lisfinity-core' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'render_type' => 'ui',
				'condition'   => [
					'_image_position!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_y',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units'  => [ 'px', '%', 'vh', 'vw' ],
				'default'     => [
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_v!' => 'end',
					'_image_position!'             => '',
				],
			]
		);

		$this->add_responsive_control(
			'_image_offset_y_end',
			[
				'label'       => __( 'Offset', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => - 1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units'  => [ 'px', '%', 'vh', 'vw' ],
				'default'     => [
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'   => [
					'_image_offset_orientation_v' => 'end',
					'_image_position!'            => '',
				],
			]
		);

		$this->add_responsive_control(
			'image-margin',
			[
				'label'       => __( 'Image Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
			]
		);

		$this->add_responsive_control(
			'image-border_radius',
			[
				'label'      => __( 'Border Radius', 'lisfinity-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
					'{{WRAPPER}} .lisfinity-products--custom .lisfinity-product--main img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);
	}

	/*
	 * Products layout settings.
	 * -------------------------
	 */
	public function products_layout() {
		$this->add_responsive_control(
			'products-columns',
			[
				'label'       => __( 'Break Ads Into Columns', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'min'         => 1,
				'max'         => 6,
				'description' => __( 'Choose the number of columns you wish to break ad boxes', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'width: calc(100% / {{VALUE}});',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'width: calc(100% / {{VALUE}});',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap',
			[
				'label'       => __( 'Ad Columns Gap', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 16,
				],
				'description' => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products--custom .row'         => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products .row'                 => 'margin-left: -{{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'products-columns-gap-y',
			[
				'label'       => __( 'Ad Columns Gap Vertical', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 90,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 32,
				],
				'description' => __( 'Choose the number of columns you wish to break ad boxes.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-products--custom .product-col' => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-products .product-col'         => 'margin-top:0; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Product tabs style
	 * ----------------------
	 */
	public function product_tabs_style() {
		$this->add_group_control(
			Group_Control_Product_Tabs_Typography::get_type(),
			[
				'name'     => 'products_tab_typography',
				'selector' => '{{WRAPPER}} .product-tabs--header .product-tab',
			]
		);

		$this->set_heading_section( 'tabs_heading_inactive', 'Inactive Tab', 'inactive_tab_hr' );

		$this->set_text_color( 'products_tab-inactive_color', 'Text Color', 'rgba(45, 45, 45, 1)', '.product-tabs--header .product-tab' );

		$this->set_text_color( 'products_tab-inactive_color_hover', 'Text Color on Hover', 'rgba(45, 45, 45, 1)', '.product-tabs--header .product-tab:hover' );

		$this->set_background_color( 'products_tabs_bg_color_inactive', '#FFFFFF05', 'Background color', '.product-tabs--header .product-tab' );

		$this->set_background_color( 'products_tabs_bg_color_inactive_hover', 'transparent', 'Background color on hover', '.product-tabs--header .product-tab:hover' );


		$this->set_heading_section( 'tabs_heading_active', 'Active Tab', 'active_tab_hr' );

		$this->set_text_color( 'products_tab_active_color', 'Text Color', 'rgba(45, 45, 45, 1)', '.product-tabs--header .product-tab.active' );

		$this->set_text_color( 'text_color_hover_id', 'Text Color on Hover', 'rgba(45, 45, 45, 1)', '.product-tabs--header .product-tab.active:hover' );


		$this->set_background_color( 'products_tabs_bg_color_active', 'rgba(255, 255, 255, 1)', 'Background color', '.product-tabs--header .product-tab.active' );

		$this->set_background_color( 'products_tabs_bg_color_active_hover', 'rgba(255, 255, 255, 1)', 'Background color on hover', '.product-tabs--header .product-tab.active:hover' );
	}


	/**
	 * Product action bookmark style settings
	 * ----------------------
	 */
	public function products_action_bookmark_style() {

		$this->display_element( 'hide_show_action_bookmark', 'Display Action Bookmark' );

		$this->add_control(
			'place_icon_bookmark',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_bookmark',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_bookmark' => 'yes',
				]
			]
		);

		$this->set_icon_size( 'action_bookmark_icon_size', '18', '.bookmark-icon' );

		$this->set_heading_section( 'action_bookmark_icon_heading', 'Icon Color', 'action_bookmark_icon_color_hr' );

		$this->set_icon_color( 'id_bookmarked', 'Color of the active icon', '#ef4e4e', '.bookmark-icon.h-18.w-18.fill-custom-color' );

		$this->set_icon_color( 'id_not_bookmarked', 'Color of the inactive icon', 'rgba(255, 255, 255, 1)', '.bookmark-icon.h-18.w-18.fill-white' );

		$this->set_heading_section( 'action_bookmark_position_heading', 'Icon Position', 'action_bookmark_position_hr' );

		$this->set_element_position( 'id_bookmark_position_x', '24', 'id_bookmark_position_y', '30', '.action--like', 'hide_show_action_bookmark' );
	}

	/**
	 * Product fixed price style settings
	 * ----------------------
	 */
	public function products_fixed_price_style() {

		$this->set_heading_section( 'price_fixed_heading', 'Price Options', 'price_fixed_hr' );

		$this->display_element( 'display_fixed_price', 'Display Product Price' );

		$this->set_text_color( 'display_fixed_price_color', 'Price Color', 'rgba(76, 76, 76, 1)', '.price-fixed' );

		$this->add_control(
			'display_fixed_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .price-fixed' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_fixed_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_fixed_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_fixed_price' => 'yes',
				]
			]
		);

		$this->icon_style( 'place_icon_fixed', '.fixed-products-icon', 'products_fixed_icon_size', '14', 'products_fixed_icon_color', 'rgba(149, 149, 149, 1)' );

		$this->display_element( 'display_label_fixed', 'Display Label' );

	}

	/**
	 * Product negotiable price style settings
	 * ----------------------
	 */
	public function products_negotiable_price_style() {

		$this->set_heading_section( 'price_negotiable_heading', 'Price Options', 'price_negotiable_hr' );

		$this->display_element( 'display_negotiable_price', 'Display Listing Price' );

		$this->set_text_color( 'display_negotiable_price_color', 'Price Color', 'rgba(76, 76, 76, 1)', '.price-negotiable' );

		$this->add_control(
			'display_negotiable_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .price-negotiable' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_negotiable_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_negotiable_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_negotiable_price' => 'yes',
				]
			]
		);

		$this->icon_style( 'place_icon_negotiable', '.negotiable-products-icon', 'products_negotiable_icon_size', '14', 'products_negotiable_icon_color', 'rgba(149, 149, 149, 1)' );

		$this->display_element( 'display_label_negotiable', 'Display Label' );

	}

	/**
	 * Product price on sale style settings
	 * ----------------------
	 */
	public function products_price_on_call_style() {

		$this->set_heading_section( 'price_on_call_heading', 'Price Options', 'price_on_call_hr' );

		$this->display_element( 'display_price_on_call', 'Display Listing Price' );

		$this->set_text_color( 'display_on_call_price_color', 'Price Color', 'rgba(33, 134, 235, 1)', '.lisfinity-product--meta__price.text-blue-600' );

		$this->add_control(
			'display_on_call_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--meta__price.text-blue-600' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_on_call_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_on_call_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_on_call_price' => 'yes',
				]
			]
		);

		$this->icon_style( 'place_icon_on_call', '.on-call-products-icon', 'products_price_on_call_icon_size', '14', 'products_price_on_call_icon_color', 'rgba(33, 134, 235, 1)' );

	}

	/**
	 * Product auction price style settings
	 * ----------------------
	 */
	public function products_auction_price_style() {

		$this->set_heading_section( 'price_auction_heading', 'Price Options', 'price_auction_hr' );

		$this->display_element( 'display_auction_price', 'Display Listing Price' );

		$this->set_text_color( 'display_auction_price_color', 'Price Color', 'rgba(76, 76, 76, 1)', '.price-auction' );

		$this->add_control(
			'display_auction_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .price-auction' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_auction_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_auction_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_auction_price' => 'yes',
				]
			]
		);
		$this->icon_style( 'place_icon_auction', '.auction-products-icon', 'products_auction_icon_size', '14', 'products_auction_icon_color', 'rgba(149, 149, 149, 1)' );

		$this->display_element( 'display_product_countdown', 'Display Countdown' );

	}

	/**
	 * Product price on sale style settings
	 * ----------------------
	 */

	public function products_price_on_sale_style() {

		$this->set_heading_section( 'price_on_sale_heading', 'Price Options', 'price_hr' );

		$this->display_element( 'display_price_on_sale', 'Display Listing Price' );

		$this->set_text_color( 'display_on_sale_price_color', 'Price Color', 'rgba(97, 2, 21, 1)', '.lisfinity-product--meta__price.text-red-1100' );

		$this->add_control(
			'display_sale_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--meta__price.text-red-1100' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_on_sale_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_on_sale_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_on_sale_price' => 'yes',
				]
			]
		);

		$this->icon_style( 'place_icon_on_sale', '.fill-icon-sale', 'products_on_sale_icon_size', '14', 'products_on_sale_icon_color', 'rgba(97, 2, 21, 1)' );


	}

	public function products_price_label_on_sale_style() {

		$this->add_control(
			'hr_1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->display_element( 'display_label_on_sale', 'Display Listing Label' );


		$this->add_control(
			'hr_2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$this->add_control(
			'display_label_options_on_sale',
			[
				'label'        => __( 'Display Label Additional Options', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'selectors'    => [
					//"{{WRAPPER}} .lisfinity-product" => 'overflow: initial;',
				],
				'condition'    => [
					'display_label_on_sale' => 'yes',
				],
			]
		);


		$this->add_control(
			'label_options_icon_heading',
			[
				'label'     => __( 'Label Icon Options', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_icon_hr',
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_icon',
			[
				'label'        => __( 'Use Custom Icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);
		$this->add_control(
			'label_icon_url',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'label_icon'                    => 'yes',
					'display_label_options_on_sale' => 'yes',
				]
			]
		);

		$this->add_control(
			'label_sale_icon_size_id',

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'em' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
					'em' => [
						'min' => 0,
						'max' => 999
					]
				],
				'default'     => [
					'unit' => 'px',
					'size' => '14',
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon svg'                       => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lisfinity-product--meta__icon.label-on-sale-icon .label-on-sale-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				]
			]
		);
		$this->add_control(
			'label_sale_icon_color_id',
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label--sale .lisfinity-product--meta__icon svg' => 'fill: {{VALUE}}; color: {{VALUE}}',

				]
			]
		);

		$this->add_control(
			'label_options_other_heading',
			[
				'label'     => __( 'Other Label Options', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_other_hr',
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Product_Label_On_Sale_Typography::get_type(),
			[
				'name'      => 'products_label_on_sale_typography',
				'selector'  => '{{WRAPPER}} .lisfinity-product--meta__icon.label-on-sale-icon-wrapper',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				]
			]
		);

		$this->add_control(
			'label_on_sale_text_color_id',
			[
				'label'       => __( 'Text Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(255, 255, 255, 1)',
				'description' => __( 'Set the color of the text.', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label-on-sale-icon' => 'color:{{VALUE}};',
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_on_sale_bg_color_id',
			[
				'label'       => __( 'Label Background Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(239, 78, 78, 1)',
				'description' => __( 'Label Background Color', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .label-on-sale-icon' => 'background-color:{{VALUE}};',
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_on_sale_border_radius_id',
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ 'px', 'em', '%' ],
				'range'       => [
					'%'  => [
						'min' => 0,
						'max' => 50,
					],
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '5',
				],
				'selectors'   => [
					'{{WRAPPER}} .label-on-sale-icon-wrapper' => 'border-radius:{{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_on_sale_padding_id',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .label-on-sale-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => 0,
					'right'    => 6,
					'bottom'   => 0,
					'left'     => 0,
					'isLinked' => false,
				],
				'condition'   => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_options_positioning_heading',
			[
				'label'     => __( 'Label Position', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_position_hr',
			[
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					'display_label_options_on_sale' => 'yes',
				],
			]
		);

		$this->set_element_position( 'label_positioning_x', '-25', 'label_positioning_y', '-4', '.lisfinity-product .label--sale', 'display_label_options_on_sale' );


	}

	/**
	 * Product price style settings
	 * ----------------------
	 */
	public function products_price_free_style() {

		$this->set_heading_section( 'price_free_heading', 'Price Options', 'price_free_hr' );

		$this->display_element( 'display_price_free', 'Display Listing Price' );

		$this->set_text_color( 'display_free_price_color', 'Price Color', 'rgba(20, 125, 100, 1)', '.lisfinity-product--meta__price.text-green-900' );

		$this->add_control(
			'display_free_price_size',

			[
				'label'       => __( 'Price Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 14,
				],
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--meta__price.text-green-900' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'place_icon_free_price',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'icon_free_price',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_free_price' => 'yes',
				]
			]
		);

		$this->icon_style( 'place_icon_free', '.free-products-icon', 'products_free_icon_size', '14', 'products_free_icon_color', 'rgba(20, 125, 100, 1)' );
	}

	/**
	 * Product views style settings
	 * ----------------------
	 */

	public function products_views() {
		$this->display_element( 'display_views', esc_html__( 'Display Views', 'lisfinity-core' ), '' );

		$this->add_control(
			'display_icon',
			[
				'label'        => __( 'Display icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => [ 'before' ]

			]
		);

		$this->add_control(
			'place_icon_views',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ],
				'condition'    => [
					'display_icon' => 'yes'
				]

			]
		);

		$this->add_control(
			'icon_views',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_views' => 'yes',
				]
			]
		);

		$this->set_icon_size( 'views_icon_size', '16', '.views-icon' );

		$this->set_icon_color( 'views_icon_color', 'Color of the icon', 'rgba(255, 255, 255, 1)', '.views-icon' );
		$this->add_responsive_control(
			'icon_indent',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'label'       => __( 'Custom Position', 'elementor' ),
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 999,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} .views-icon" => 'position: relative; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->set_heading_section( 'number_views_heading', esc_html__( 'Number styles', 'lisfinity-core' ), 'number_views_hr' );


		$this->add_responsive_control(
			'products_views_text_size',

			[
				'label'       => __( 'Numbers Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors'   => [
					'{{WRAPPER}} .views-counts' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_text_color( 'products_text_color_id', 'Numbers Color', 'rgba(255, 255, 255, 1)', '.views-counts' );

		$this->add_control(
			'number_of_views_padding',
			[
				'label'       => __( 'Numbers Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .views-counts' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '8',
					'isLinked' => false,
				]
			]
		);
		$this->set_heading_section( 'box_views_heading', esc_html__( 'Box styles', 'lisfinity-core' ), 'box_views_hr' );

		$this->add_responsive_control(
			'products_views_width',

			[
				'label'       => __( 'Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors'   => [
					'{{WRAPPER}} .views-counts-wrapper, {{WRAPPER}} .views-counts-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_height',

			[
				'label'       => __( 'Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors'   => [
					'{{WRAPPER}} .views-counts-wrapper, {{WRAPPER}} .views-counts-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_background_color( 'products_count_bg_color', 'rgba(45, 45, 45, 0.5)', 'Background color', '.views-counts-wrapper' );

		$this->set_border_radius( 'product_counts_border_radius', '0', '3', '3', '0', '%', 'Border radius', '.views-counts-wrapper' );

		$this->add_group_control(
			Group_Control_Category_Carousel_Number_Box_Shadow::get_type(),
			[
				'name'     => 'product_views_number_box_shadow',
				'selector' => '{{WRAPPER}} .views-counts-wrapper',
			]
		);

		$this->add_control(
			'products_of_views_padding',
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .views-counts-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => false,
				]
			]
		);
		$this->add_control(
			'products_of_views_margin',
			[
				'label'       => __( 'Margin', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .views-counts-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => false,
				]
			]
		);


		$this->set_heading_section( 'products_views_position_heading', 'Products Views Position', 'products_views_positioning_hr' );

		$this->add_responsive_control(
			'products_views_position_x',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_y',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 250,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_x_four',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '4',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_y_four',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 220,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '4',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_x_style_one',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_y_style_one',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 280,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_x_style_two',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_y_style_two',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 280,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '2',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_x_style_three',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '3',
				],
			]
		);

		$this->add_responsive_control(
			'products_views_position_y_style_three',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 380,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .views-counts-container' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => '3',
				],
			]
		);
	}

	public function verified_author() {

		$this->set_background_color( 'products_verified_author_bg_color', 'rgba(101, 214, 173, 1)', 'Background color', '.author-verified-wrapper' );

		$this->add_responsive_control(
			'author_badge_wrapper_size',

			[
				'label'       => __( 'Wrapper Width', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 32,
				],
				'selectors'   => [
					"{{WRAPPER}} .author-verified-container" => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_badge_wrapper_height',

			[
				'label'       => __( 'Wrapper Height', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 32,
				],
				'selectors'   => [
					"{{WRAPPER}} .author-verified-container" => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->set_border_radius( 'products_verified_author_border_radius', '50', '50', '50', '50', '%', 'Border radius', '.author-verified-wrapper' );

		$this->add_control(
			'place_icon_author_badge',
			[
				'label'        => __( 'Use different icon', 'lisfinity-core' ),
				'label_block'  => true,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => [ 'before' ]

			]
		);
		$this->add_control(
			'icon_author_badge',
			[
				'label'            => __( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition'        => [
					'place_icon_author_badge' => 'yes',
				]
			]
		);
		$this->set_icon_color( 'products_verified_author_icon_color', 'Icon Color', 'rgba(255, 255, 255, 1)', '.author-verified-icon' );

		$this->set_icon_size( 'products_verified_author_icon_size', '14', '.author-verified-icon' );

		$this->set_heading_section( 'products_author_badge_position_heading', 'Verified Badge Position', 'products_author_badge_positioning_hr' );


		$this->add_responsive_control(
			'products_author_badge_position_y_top',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 20,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .author-verified-container' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'style' => 'custom',
				],
			]
		);
		$this->add_responsive_control(
			'products_author_badge_position_y_left',

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 900,
						'max' => 900,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 20,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .author-verified-container' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

	}


	/**
	 * Product promoted icon style settings
	 * ----------------------
	 */
	public function products_promoted_icon_style() {

		$this->add_control(
			'promoted_text',
			[
				'label'       => __( 'Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'AD', 'elementor' ),
				'placeholder' => __( 'Click here', 'elementor' ),
			]
		);
		$this->add_group_control(
			Group_Control_Product_Promoted_Icon_Typography::get_type(),
			[
				'name'     => 'products_promoted_icon_cap_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--title .label--promoted',
			]
		);

		$this->set_text_color( 'promoted_icon_color', esc_html__( 'Color', 'lisfinity-core' ), 'rgba(247, 201, 72, 1)', '.lisfinity-product--title .label--promoted' );

	}

	/**
	 * Product title style settings
	 * ----------------------
	 */
	public function products_title_style() {
		$this->display_element( 'hide_show_product_title', esc_html__( 'Display Product Title', 'lisfinity-core' ) );

		$this->add_group_control(
			Group_Control_Product_Title_Typography::get_type(),
			[
				'name'     => 'product_title_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--title .product--title ',
			]
		);

		$this->set_text_color( 'products_title_color', 'Set the color of the text', 'rgba(76, 76, 76, 1)', '.lisfinity-product--title .product--title' );


		$this->add_control(
			'align_title',
			[
				'label'       => __( 'Set alignment of the title', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'left'   => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => 'left',
				'toggle'      => true,
				'description' => __( 'Set alignment of the title', 'lisfinity-core' ),
				'selectors'   => [
					'{{WRAPPER}} .lisfinity-product--title .product--title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->set_padding( 'id_title_padding', '.lisfinity-product--title .product--title', '0', '0', '0', '0', 'true', 'hide_show_product_title' );

	}

	/**
	 * Product custom fields style settings
	 * ----------------------
	 */
	public function products_custom_fields_style() {
		$this->display_element( 'hide_show_product_custom_fields', 'Display Element' );


		$this->set_background_color( 'products_custom_fields_bg_color', 'rgba(246, 246, 246, 1)', 'Background color', '.lisfinity-product--content .flex.flex.flex-wrap.items-center.mb-20 .flex-center.mt-1' );

		$this->add_group_control(
			Group_Control_Product_Custom_Fields_Typography::get_type(),
			[
				'name'     => 'product_custom_fields_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--content .flex.flex.flex-wrap.items-center.mb-20 .ml-1.font-semibold ',
			]
		);

		$this->set_text_color( 'products_custom_fields_color', 'Set the color of the text', 'rgba(94, 94, 94, 1)', '.lisfinity-product--content .flex.flex.flex-wrap.items-center.mb-20 .ml-1.font-semibold' );


		$this->set_icon_size( 'products_custom_fields_icon_size', '12', '.lisfinity-product--content .flex.flex.flex-wrap.items-center.mb-20 .flex-center .fill-taxonomy-icon' );


		$this->set_icon_color( 'products_custom_fields_icon_color', 'Icon Color', 'rgba(94, 94, 94, 1)', '.lisfinity-product--content .flex.flex.flex-wrap.items-center.mb-20 .flex-center .fill-taxonomy-icon path' );


		$this->set_elements_alignment( 'align_custom_fields', 'Set alignment of the element', 'flex-start', '.lisfinity-product--content .flex.flex.flex-wrap.items-center.mb-20' );


	}

	/**
	 * Product info ratings style settings
	 * ----------------------
	 */
	public function products_info_ratings_style() {
		$this->display_element( 'hide_show_product_info_mark', 'Display Mark' );


		$this->set_background_color( 'products_info_ratings_bg_color', 'rgba(255, 243, 196, 1)', 'Background color', '.lisfinity-product--info.flex-center.mr-10 .flex-center' );

		$this->set_border_radius( 'products_info_ratings_border_radius', '50', '50', '50', '50', '%', 'Border radius', '.lisfinity-product--info.flex-center.mr-10 .flex-center' );
		$this->display_element( 'hide_show_product_info_mark_icon', 'Display Mark Icon' );
		$this->set_icon_color( 'products_info_ratings_icon_color', 'Icon Color', 'rgba(203, 110, 23, 1)', '.lisfinity-product--info.flex-center.mr-10 .flex-center .fill-product-star-icon' );

		$this->set_icon_size( 'products_info_ratings_icon_size', '14', '.lisfinity-product--info.flex-center.mr-10 .flex-center .fill-product-star-icon' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'product_info_ratings_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info.flex-center.mr-10 .ml-6.text-sm ',
			]
		);


	}

	/**
	 * Product info location style settings
	 * ----------------------
	 */
	public function products_info_location_style() {
		$this->display_element( 'hide_show_product_info_place', 'Display Place' );

		$this->add_control(
			'location_type',
			[
				'label'       => __( 'Location Type', 'lisfinity-core' ),
				'type'        => Controls_Manager::SELECT,
				'multiple'    => true,
				'options'     => $this->product_location,
				'default'     => 'listing_location',
				'description' => __( 'Choose location types that you allow to be displayed.', 'lisfinity-core' ),
			]
		);

		$this->set_background_color( 'products_info_location_bg_color', 'rgba(193, 254, 246, 1)', 'Background color', '.lisfinity-product--info.flex-center .flex-center' );

		$this->set_border_radius( 'products_info_location_border_radius', '50', '50', '50', '50', '%', 'Border radius', '.lisfinity-product--info.flex-center .flex-center' );
		$this->display_element( 'hide_show_product_info_place_icon', 'Display Location Icon' );
		$this->set_icon_color( 'products_info_location_icon_color', 'Icon Color', 'rgba(5, 96, 110, 1)', '.lisfinity-product--info.flex-center .flex-center .fill-product-place-icon' );

		$this->set_icon_size( 'products_info_location_icon_size', '14', '.lisfinity-product--info.flex-center .flex-center .fill-product-place-icon' );

		$this->add_group_control(
			Group_Control_Product_Info_Ratings_Typography::get_type(),
			[
				'name'     => 'product_info_location_typography',
				'selector' => '{{WRAPPER}} .lisfinity-product--info.flex-center .ml-6.text-sm',
			]
		);

	}

	/**
	 * Product logo style settings
	 * ----------------------
	 */
	public function products_logo_style() {

		$this->display_element( 'hide_show_product_owner_logo', 'Display Product Owner Logo' );

//		$this->add_responsive_control(
//			'logo_size',
//
//			[
//				'label'       => __( 'Logo Size', 'lisfinity-core' ),
//				'label_block' => true,
//				'type'        => Controls_Manager::SLIDER,
//				'size_units'  => [ 'px', '%' ],
//				'range'       => [
//					'px' => [
//						'min' => 0,
//						'max' => 999,
//					],
//				],
//				'default'     => [
//					'unit' => 'px',
//					'size' => '100',
//				],
//				'description' => __( 'Choose the size of the logo.', 'lisfinity-core' ),
//				'selectors'   => [
//					"{{WRAPPER}} .lisfinity-product--author" => 'width: {{SIZE}}{{UNIT}};',
//				],
//			]
//		);

	}


	/**
	 * *Product elements sorting
	 * -------------------------
	 */

	public function content_elements_sorting() {

		$this->start_controls_tabs(
			'sorting_tabs'
		);
		$this->start_controls_tab( 'header_tab', [
			'label' => __( 'Header Elements', 'lisfinity-core' ),
		] );
		$this->add_control(
			'date_sorting',
			[
				'label'     => __( 'Set the order of the date', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 2,
				'step'      => 1,
				'default'   => 2,
				'selectors' => [
					"{{WRAPPER}} .due-date" => 'order:{{VALUE}}; display: flex;',
				],
			]
		);

		$this->add_control(
			'price_sorting',
			[
				'label'     => __( 'Set the order of the price', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 2,
				'step'      => 1,
				'default'   => 1,
				'selectors' => [
					"{{WRAPPER}} .lisfinity-product--meta" => 'order:{{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'content_tab', [
			'label' => __( 'Content Elements', 'lisfinity-core' ),
		] );
		$this->sort_elements( 'sort_products_price', 'Header Order', 1, '.lisfinity-product--content .lisfinity-product--image.relative.flex' );
		$this->sort_elements( 'sort_products_title', 'Title Order', 2, '.lisfinity-product--content .lisfinity-product--content' );

		$this->sort_elements( 'sort_custom_fields', 'Custom Fields Order', 3, '.lisfinity-product--content .flex.flex.flex-wrap.items-center.mb-20' );
		$this->sort_elements( 'sort_products_owner_details', 'Footer Order', 4, '.lisfinity-product--content .flex.items-center.justify-between' );

		$this->end_controls_tab();
		$this->start_controls_tab( 'footer_tab', [
			'label' => __( 'Footer Elements', 'lisfinity-core' ),
		] );
		$this->add_control(
			'owner_logo_sorting',
			[
				'label'     => __( 'Set the order of the owner logo', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 2,
				'step'      => 1,
				'default'   => 2,
				'selectors' => [
					"{{WRAPPER}} .lisfinity-product--author" => 'order:{{VALUE}};',
				],
			]
		);

		$this->add_control(
			'info_icons_sorting',
			[
				'label'     => __( 'Set the order of the info icons', 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 2,
				'step'      => 1,
				'default'   => 1,
				'selectors' => [
					"{{WRAPPER}} .lisfinity-product--info-wrapper" => 'order:{{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	/**
	 * *Product box styling
	 * -------------------------
	 */


	public function box_styling() {

		$this->add_control(
			'box_bg_color',
			[
				'label'     => __( 'Background color', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 1)',
				'selectors' => [
					'{{WRAPPER}} .product-col .lisfinity-product--content.bg-white'      => 'background-color:{{VALUE}};',
					'{{WRAPPER}} .product-col .lisfinity-product--content.bg-bump-color' => 'background-color: rgba(255, 242, 171, 1));'
				],
			]
		);

		$this->add_control(
			'box_bg_color_hover',
			[
				'label'     => __( 'Background color on hover', 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .lisfinity-product:hover' => 'background-color:{{VALUE}};',
				],
			]
		);

		$this->set_border_radius( 'box_border_radius', '3', '3', '3', '3', 'px', 'Border radius', '.lisfinity-product' );

		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'products_border_box',
				'selector' => '{{WRAPPER}} .lisfinity-product',
			]
		);
		$this->add_group_control(
			Group_Control_Product_Box_Shadow::get_type(),
			[
				'name'     => 'products_border_box_hover',
				'selector' => '{{WRAPPER}} .lisfinity-product:hover',
				'label'    => 'Box Shadow On hover'
			]
		);

	}


	/**
	 * * functions
	 * -------------------------
	 */


	public function custom_icon( $id_place_icon, $id_icon_url ) {
		$this->add_control(
			$id_place_icon,
			[
				'label'        => __( 'Use Custom Icon', 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => true,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_responsive_control(
			$id_icon_url,
			[
				'label'       => __( 'Place icon', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => '',
				],
				'condition'   => [
					$id_place_icon => 'yes',
				],
			]
		);
	}

	public function icon_style( $id_place_icon, $icon_class, $id_icon_size, $default_size, $id_icon_color, $default_color ) {


		$this->add_responsive_control(
			$id_icon_size,

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_size,
				],
				'description' => __( 'Choose the size of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			$id_icon_color,
			[
				'label'       => __( 'Icon Color', 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( 'Set the color of the icon.', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} {$icon_class}" => 'fill: {{VALUE}};color: {{VALUE}};',
				]
			]
		);

	}

	public function display_element( $id, $message, $default = 'yes' ) {
		$this->add_control(
			$id,
			[
				'label'        => __( $message, 'lisfinity-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'lisfinity-core' ),
				'label_off'    => __( 'Hide', 'lisfinity-core' ),
				'return_value' => 'yes',
				'default'      => $default,
			]
		);
	}

	public function set_background_color( $id, $default_color, $message, $selector ) {
		$this->add_control(
			$id,
			[
				'label'       => __( $message, 'lisfinity-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => $default_color,
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'background-color:{{VALUE}};'
				],
			]
		);
	}

	public function sort_elements( $id, $description, $order_number, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $description, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'step'      => 1,
				'default'   => $order_number,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'order:{{VALUE}};',
				],
			]
		);
	}

	public function set_heading_section( $id, $heading, $hr_id ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $heading, 'lisfinity-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			$hr_id,
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
	}

	public function set_border_radius( $id, $default_top, $default_right, $default_bottom, $default_left, $default_unit, $message, $selector ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( 'Border Radius', 'lisfinity-core' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'size_units'  => [ '%', 'px', 'em' ],
				'range'       => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'     => [
					'top'    => $default_top,
					'right'  => $default_right,
					'bottom' => $default_bottom,
					'left'   => $default_left,
					'unit'   => $default_unit
				],
				'description' => __( $message, 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	}

	public function set_text_color( $id, $message, $default, $selector, $default_args = [] ) {
		$args = [
			'label'     => __( $message, 'lisfinity-core' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => $default,
			'selectors' => [
				"{{WRAPPER}} $selector" => 'color:{{VALUE}};',
			],
		];
		if ( ! empty( $default_args ) ) {
			$args[] = $default_args;
		}
		$this->add_control( $id, $args );
	}

	public function set_icon_color( $id, $message, $default, $selector ) {
		$this->add_control(
			$id,
			[
				'label'     => __( $message, 'lisfinity-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default,
				'selectors' => [
					"{{WRAPPER}} $selector" => 'fill:{{VALUE}}; color: {{VALUE}}',
				]
			]
		);
	}

	public function set_icon_size( $id, $default, $selector ) {
		$this->add_responsive_control(
			$id,

			[
				'label'       => __( 'Icon Size', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 999,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default,
				],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function set_elements_alignment( $id, $label, $default, $selector ) {
		$this->add_responsive_control(
			$id,
			[
				'label'       => __( $label, 'lisfinity-core' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Left', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'lisfinity-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'     => $default,
				'toggle'      => true,
				'description' => __( 'Set alignment', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

	public function set_element_position( $id_x, $default_x, $id_y, $default_y, $selector, $condition ) {
		$this->add_responsive_control(
			$id_x,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_x,
				],
				'description' => __( 'Horizontal', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'right: {{SIZE}}{{UNIT}}; z-index: 50;',
				],
				'condition'   => [
					$condition => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			$id_y,

			[
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px' ],
				'range'       => [
					'px' => [
						'min' => - 350,
						'max' => 350,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => $default_y,
				],
				'description' => __( 'Vertical', 'lisfinity-core' ),
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'top: {{SIZE}}{{UNIT}}; z-index: 50;',
				],
				'condition'   => [
					$condition => 'yes',
				],
			]
		);
	}

	public function set_padding( $id, $selector, $default_top, $default_right, $default_bottom, $default_left, $default_boolean, $condition ) {

		$this->add_responsive_control(
			$id,
			[
				'label'       => __( 'Padding', 'lisfinity-core' ),
				'label_block' => true,
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'selectors'   => [
					"{{WRAPPER}} $selector" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'     => [
					'top'      => $default_top,
					'right'    => $default_right,
					'bottom'   => $default_bottom,
					'left'     => $default_left,
					'isLinked' => $default_boolean,
				],
				'condition'   => [
					$condition => 'yes',
				],
			]
		);
	}

	/**
	 * Get the arguments for the products wp query
	 * -------------------------------------------
	 *
	 * @param $settings
	 * @param bool $excluded
	 * @param bool $ids
	 *
	 * @return array
	 */
	protected function get_product_args( $settings, $excluded = false, $ids = false ) {
		$type     = \WC_Product_Listing::$type;
		$statuses = [ 'publish' ];
		if ( 'yes' === $settings['sold'] ) {
			$statuses[] = 'sold';
		}

		$args = [
			'post_type'      => 'product',
			'post_status'    => $statuses,
			'posts_per_page' => $settings['number'],
			'tax_query'      => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'name',
					'terms'    => $type,
					'operator' => 'IN',
				],
			],
			'no_found_rows'  => true,
			'cache_results'  => true,
		];

		// if on custom category archive page.
		$request = lisfinity_get_taxonomy_and_term();
		if ( $request && taxonomy_exists( $request[0] ) && term_exists( $request[1], $request[0] ) ) {
			$args['tax_query'][] = [
				'taxonomy' => $request[0],
				'field'    => 'slug',
				'terms'    => $request[1]
			];
		}

		// only from the same category.
		$this->lisfinity_is_elementor = lisfinity_is_elementor();
		if ( $request && lisfinity_get_slug( 'slug-category', 'ad-category' ) === $request[0] && get_queried_object_id() ) {
			$settings['same_category'] = 'yes';
		}
		if ( ! empty( $settings['same_category'] ) && 'yes' === $settings['same_category'] ) {
			$current_product_id = get_the_ID();

			if ( $this->lisfinity_is_elementor ) {
				$current_product_id = carbon_get_post_meta( $current_product_id, 'elementor-mockup-product' );
			}
			$current_product_category = carbon_get_post_meta( $current_product_id, 'product-category' );
			$args['meta_query'][]     = [
				'key'   => 'product-category',
				'value' => $current_product_category,
			];
		}

		// add meta to query args only if the 'from same category' fields hasn't been selected.
		if ( ( empty( $settings['same_category'] ) || 'yes' !== $settings['same_category'] ) && ! empty( $settings['type'] ) ) {
			$args['meta_query'][] = [
				'key'   => 'product-category',
				'value' => $settings['type'],
			];
		} else if ( ( empty( $settings['same_category'] ) || 'yes' !== $settings['same_category'] ) && empty( $settings['type'] ) ) {
			if ( ! empty( lisfinity_get_hidden_categories() ) ) {
				$args['meta_query'][] = [
					'key'     => 'product-category',
					'value'   => lisfinity_get_hidden_categories(),
					'compare' => 'NOT IN'
				];
			}
		}

		// from the specific price type.
		if ( ! empty( $settings['price_type'] ) ) {
			$args['meta_query'][] = [
				'key'   => '_product-price-type',
				'value' => $settings['price_type'],
			];
		}

		if ( ! empty( $settings['price_type_expiring'] ) && 'yes' === $settings['price_type_expiring'] ) {
			$hours                = empty( $settings['price_type_expiring_hours'] ) || 0 > $settings['price_type_expiring_hours'] ? 3 : $settings['price_type_expiring_hours'];
			$args['meta_query'][] = [
				'key'     => '_product-auction-ends',
				'value'   => strtotime( "+${hours} hours", current_time( 'timestamp' ) ),
				'compare' => '<=',
			];
		}

		// add taxonomies to query args.
		foreach ( $settings as $taxonomy => $terms ) {
			if ( false !== strpos( $taxonomy, 'tax[' ) ) {
				if ( ! empty( $taxonomy ) ) {
					preg_match_all( "/\[(.*?)\]/", $taxonomy, $matches );
					if ( ! empty( $terms ) ) {
						$args['tax_query'][] = [
							'taxonomy' => $matches[1][0],
							'field'    => 'slug',
							'terms'    => $terms,
							'operator' => 'IN',
						];
					}
				}
			}
		}

		// add handpicked products to query args.
		if ( ! empty ( $settings['handpicked'] ) ) {
			if ( ! empty( $settings['handpicked'] ) ) {
				$args['post__in'] = $settings['handpicked'];
			}
		}

		// add order to query args.
		if ( ! empty( $settings['order'] ) ) {
			$args['order'] = $settings['order'];
		}

		// add sorting to query args.
		if ( ! empty( $settings['orderby'] ) ) {
			if ( 'views' === $settings['orderby'] ) {
				$args['meta_key'] = '_product-views';
				$args['orderby']  = 'meta_value_num';
			} else {
				$args['orderby'] = $settings['orderby'];
			}
		}

		add_filter( 'posts_join', [ $this, 'products_join_expires' ] );
		add_filter( 'posts_where', [ $this, 'products_where_expires' ], 10, 2 );

		// if we're including promoted products.
		if ( $excluded ) {
			$args['post__not_in'] = $ids;
			$args['fields']       = 'ids';
		}

		if ( $ids ) {
			$args['post__in'] = $ids;
		}

		return $args;
	}

	/**
	 * Join products promotion table to default $wpdb->posts table
	 * -----------------------------------------------------------
	 *
	 * @param $join
	 *
	 * @return string
	 */
	public function products_join_expires( $join ) {
		global $wpdb;

		if ( false === strpos( $join, 'LEFT JOIN wp_postmeta' ) ) {
			$join .= " LEFT JOIN {$wpdb->postmeta} AS my_meta ON $wpdb->posts.ID = my_meta.post_id ";
		}

		return $join;
	}

	/**
	 * Include query parameter to load products with a running promotion
	 * -----------------------------------------------------------------
	 *
	 * @param $where
	 *
	 * @return string
	 */
	public function products_where_expires( $where ) {
		$where .= " AND ( my_meta.meta_key = '_product-expiration' AND my_meta.meta_value >= UNIX_TIMESTAMP() ) ";

		return $where;
	}

	/**
	 * Remove custom db filters so they can't interfere with
	 * other wp queries
	 * -----------------------------------------------------
	 */
	public function remove_custom_db_filters() {
		remove_filter( 'posts_join', [ $this, 'products_join_expires' ] );
		remove_filter( 'posts_where', [ $this, 'products_where_expires' ] );
	}

	/**
	 * Render the content on frontend
	 * ------------------------------
	 */
	protected function render() {
		//todo promotions and handpicked shouldn't be possible to choose at the same time.
		$settings   = $this->get_settings_for_display();
		$products   = [];
		$tab_titles = [];
		if ( ! empty( $settings['product_tabs'] ) ) {
			foreach ( $settings['product_tabs'] as $tab_settings ) {
				$tab_titles[] = $tab_settings['tab_title'];

				// if we're including promoted items.
				if ( ! empty( $tab_settings['promoted'] ) && 'yes' === $tab_settings['promoted'] ) {
					$promoted     = lisfinity_get_promoted_products( $tab_settings['promo_type'] ?? 'home-ads' );
					$promoted_ids = [];
					if ( ! empty( $promoted ) ) {
						foreach ( $promoted as $product ) {
							$promoted_ids[] = (int) $product->product_id;
						}
					}
					if ( ! empty( $tab_settings['non_promoted'] ) && 'yes' === $tab_settings['non_promoted'] ) {
						// get non promoted ids.
						$args = $this->get_product_args( $tab_settings, true, $promoted_ids );
						unset( $args['post__in'] );
						$non_promoted_ids = new \WP_Query( wp_parse_args(
							[
								'fields'         => 'ids',
								'posts_per_page' => $tab_settings['number'] - count( $promoted_ids ),
							], $args ) );

						// merge with promotions and get query args.
						$promoted_ids    = array_merge( $promoted_ids, $non_promoted_ids->posts );
						$args            = $this->get_product_args( $tab_settings, false, $promoted_ids );
						$args['orderby'] = 'post__in';
					} else {
						$args = $this->get_product_args( $tab_settings, true, $promoted_ids );
					}
					$products[] = new \WP_Query( $args );
				} else if ( 'yes' === $tab_settings['visited'] ) {
					$recent_listings = get_user_meta( get_current_user_id(), 'recent-listings' );
					$args            = $this->get_product_args( $tab_settings );
					if ( ! empty( $recent_listings ) ) {
						$args['post__in'] = array_shift( $recent_listings );
					}
					$products[] = new \WP_Query( $args );
				} else {
					// without promoted products.
					$args       = $this->get_product_args( $tab_settings );
					$products[] = new \WP_Query( $args );
				}
				// remove promotion db query filters after products query has been made.
				$this->remove_custom_db_filters();
			}
		}

		$args = [
			'settings'     => $settings,
			'tab_titles'   => $tab_titles,
			'tab_products' => $products,
		];


		if ( ! empty( $products ) ) {
			$posts = [];
			foreach ( $products as $products_query ) {
				$posts = array_merge( $posts, $products_query->posts );
			}
			$filtered = [];
			foreach ( $posts as $index => $post ) {
				if ( ! empty( $post->ID ) ) {
					if ( ! in_array( $post->ID, $filtered ) ) {
						do_action( 'lisfinity__store_impression', [
							'user_id'    => carbon_get_post_meta( $post->ID, 'product-business' ),
							'product_id' => $post->ID,
							'type'       => 1,
						] );
						$filtered[] = $post->ID;
					}
				}
			}
		}

		include lisfinity_get_template_part( 'products', 'shortcodes/products', $args );
	}

}
