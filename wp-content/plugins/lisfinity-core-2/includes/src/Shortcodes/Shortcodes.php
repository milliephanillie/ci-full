<?php


namespace Lisfinity\Shortcodes;


use Lisfinity\Models\Taxonomies\GroupsAdminModel;
use Lisfinity\Shortcodes\Auth\Login_Form_Widget;
use Lisfinity\Shortcodes\Authors\Author_Box_Widget;
use Lisfinity\Shortcodes\Authors\Author_Search_Widget;
use Lisfinity\Shortcodes\BusinessProfileSingle\Business_About_Widget;
use Lisfinity\Shortcodes\BusinessProfileSingle\Business_Testimonial_Widget;
use Lisfinity\Shortcodes\BusinessProfileSingle\Business_Store_Widget;
use Lisfinity\Shortcodes\Auth\Auth_Breadcrumbs_Widget;
use Lisfinity\Shortcodes\Auth\Password_Reset_Form_Widget;
use Lisfinity\Shortcodes\Auth\Register_Form_Widget;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Button_Border;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Button_Typography;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Features_Categories_Typography;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Border;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Label_Typography;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Search_Field_Border;
use Lisfinity\Shortcodes\Controls\Category\Group_Control_Category_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category\Group_Control_Category_Title_Typography;
use Lisfinity\Shortcodes\Controls\Category\Group_Control_Category_Typography;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Number_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Text_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Category_Carousel\Group_Control_Category_Carousel_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Avatar_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Button_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Items_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Notification_Message_Footer_Text_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Notification_Message_Footer_Time_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Notification_Message_Text_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Notification_Message_Title_Typography;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Border;
use Lisfinity\Shortcodes\Controls\Navigation\Group_Control_Navigation_Submenu_Item_Typography;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Button_Border;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Button_Typography;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Content_Typography;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Footnote_Typography;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Recommended_Button_Border;
use Lisfinity\Shortcodes\Controls\Packages\Group_Control_Packages_Title_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Author_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Content_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Date_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Post_Category_Typography;
use Lisfinity\Shortcodes\Controls\Posts\Group_Control_Posts_Title_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Custom_Fields_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Label_On_Sale_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Promoted_Icon_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Tabs_Typography;
use Lisfinity\Shortcodes\Controls\Products\Group_Control_Product_Title_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Actions_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Breadcrumbs_Active_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Description_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Current_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Gallery_Current_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Id_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Info_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Location_Map_Address_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Location_Map_Expand_Map_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Messages_Text_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Hover_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Hover_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Messages_Button_Border;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Messages_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Name_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Phone_Button_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Owner_Phone_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Price_Countdown_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Safety_Tips_Link_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Safety_Tips_Title_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sidebar_Menu_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Multiple_Value_Subtitle_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Multiple_Value_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Single_Value_Label_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Single_Value_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Specification_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sticky_Menu_Box_Shadow;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Sticky_Menu_Typography;
use Lisfinity\Shortcodes\Controls\ProductSingle\Group_Control_Single_Product_Title_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profile_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profile_Info_Ratings_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profile_Link_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profiles_Text_Typography;
use Lisfinity\Shortcodes\Controls\Profiles\Group_Control_Profiles_Title_Typography;
use Lisfinity\Shortcodes\Controls\Search_Keyword\Group_Control_Search_Keyword_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Search_Keyword\Group_Control_Search_Keyword_Label_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Number_Of_Terms_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography_Style_Four;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography_Style_Three;
use Lisfinity\Shortcodes\Controls\Taxonomies\Group_Control_Taxonomies_Typography_Style_Two;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Author_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Box_Shadow;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Content_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Ratings_Text_Typography;
use Lisfinity\Shortcodes\Controls\Testimonials\Group_Control_Testimonials_Year_Typography;
use Lisfinity\Shortcodes\ProductSingle\Product_Banner_Image_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Contact_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Custom_Description_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Custom_Fields_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Working_Hours_Widget;
use Lisfinity\Shortcodes\Search\Search_Breadcrumbs;
use Lisfinity\Shortcodes\Search\Search_Detailed;
use Lisfinity\Shortcodes\Search\Search_Filter_Top;
use Lisfinity\Shortcodes\Search\Search_Listings;
use Lisfinity\Shortcodes\Search\Search_Map;
use Lisfinity\Shortcodes\Search\Sidebar_Filter_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Actions_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Breadcrumbs_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Description_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Financing_Calculator_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Gallery_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Id_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Info_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Location_Map_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Logo_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Mobile_Menu_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Owner_Button_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Owner_Info_Icon_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Owner_Name_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Owner_Phone_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Safety_Tips_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Sidebar_Menu_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Specification_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Sticky_Menu_Widget;
use Lisfinity\Shortcodes\ProductSingle\Product_Title_Widget;

class Shortcodes {

	public function init() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_custom_category' ] );
		//add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}

	public function register_widgets() {
		\Elementor\Plugin::instance()->widgets_manager->register( new Title_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Heading_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Products_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Taxonomies_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Posts_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Packages_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Subscriptions_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Partners_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Profiles_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new HowItWorks_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Testimonials_Widget() );

		// Navigation Widgets
		\Elementor\Plugin::instance()->widgets_manager->register( new Navigation_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Navigation_Avatar_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Navigation_Notification_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Navigation_Compare_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Navigation_Cart_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Navigation_Login_Widget() );

		// Banner Widgets
		\Elementor\Plugin::instance()->widgets_manager->register( new Hero_Search_Field() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Banner_Features_Categories() );

		// Authors Widgets
		\Elementor\Plugin::instance()->widgets_manager->register( new Author_Box_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Author_Search_Widget() );

		// Buttons
		\Elementor\Plugin::instance()->widgets_manager->register( new Lisfinity_Button() );

		// Search Elements
		\Elementor\Plugin::instance()->widgets_manager->register( new Search_Keyword() );

		// Search Page Elements
		\Elementor\Plugin::instance()->widgets_manager->register( new Sidebar_Filter_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Search_Breadcrumbs() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Search_Filter_Top() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Search_Map() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Search_Listings() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Search_Detailed() );

		// Product Single Page Elements
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Title_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Custom_Fields_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Custom_Description_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Id_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Working_Hours_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Gallery_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Actions_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Info_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Breadcrumbs_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Specification_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Description_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Sidebar_Menu_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Sticky_Menu_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Logo_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Owner_Name_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Owner_Info_Icon_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Owner_Phone_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Owner_Button_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Location_Map_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Safety_Tips_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Financing_Calculator_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Mobile_Menu_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Contact_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Product_Banner_Image_Widget() );

		//  Page Elements
		\Elementor\Plugin::instance()->widgets_manager->register( new Login_Form_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Register_Form_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Auth_Breadcrumbs_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Password_Reset_Form_Widget() );

		// Global Elements && Tabs Widgets
		\Elementor\Plugin::instance()->widgets_manager->register( new Tabs_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Global_Elements_Widget() );

		// Business Profile Page Elements
		\Elementor\Plugin::instance()->widgets_manager->register( new \Lisfinity\Shortcodes\BusinessProfileSingle\Business_Reviews_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new \Lisfinity\Shortcodes\BusinessProfileSingle\Business_Contact_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Business_About_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Business_Store_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Business_Testimonial_Widget() );

		// do not load category types shortcode if there are no field groups created.
		$groups_model = new GroupsAdminModel();
		if ( ! empty( $groups_model->get_options() ) ) {
			\Elementor\Plugin::instance()->widgets_manager->register( new CategoriesCarousel_Widget() );
		}
		\Elementor\Plugin::instance()->widgets_manager->register( new Categories_Widget() );
	}

	public function register_controls( $controls ) {
		$category_typography                                    = new Group_Control_Category_Typography();
		$category_title_typography                              = new Group_Control_Category_Title_Typography();
		$category_box_shadow                                    = new Group_Control_Category_Box_Shadow();
		$product_custom_fields_typography                       = new Group_Control_Product_Custom_Fields_Typography();
		$product_info_ratings_typography                        = new Group_Control_Product_Info_Ratings_Typography();
		$product_promoted_icon_typography                       = new Group_Control_Product_Promoted_Icon_Typography();
		$product_title_typography                               = new Group_Control_Product_Title_Typography();
		$product_tabs_typography                                = new Group_Control_Product_Tabs_Typography();
		$product_label_on_sale_typography                       = new Group_Control_Product_Label_On_Sale_Typography();
		$product_box_shadow                                     = new Group_Control_Product_Box_Shadow();
		$profiles_title_typography                              = new Group_Control_Profiles_Title_Typography();
		$profiles_text_typography                               = new Group_Control_Profiles_Text_Typography();
		$profiles_info_ratings_typography                       = new Group_Control_Profile_Info_Ratings_Typography();
		$profiles_link_typography                               = new Group_Control_Profile_Link_Typography();
		$profiles_box_shadow                                    = new Group_Control_Profile_Box_Shadow();
		$testimonials_box_shadow                                = new Group_Control_Testimonials_Box_Shadow();
		$testimonials_content_typography                        = new Group_Control_Testimonials_Content_Typography();
		$testimonials_ratings_text_typography                   = new Group_Control_Testimonials_Ratings_Text_Typography();
		$testimonials_author_typography                         = new Group_Control_Testimonials_Author_Typography();
		$testimonials_year_typography                           = new Group_Control_Testimonials_Year_Typography();
		$category_carousel_typography                           = new Group_Control_Category_Carousel_Typography();
		$category_carousel_box_shadow                           = new Group_Control_Category_Carousel_Box_Shadow();
		$taxonomies_box_shadow                                  = new Group_Control_Taxonomies_Box_Shadow();
		$taxonomies_typography                                  = new Group_Control_Taxonomies_Typography();
		$taxonomies_typography_style_two                        = new Group_Control_Taxonomies_Typography_Style_Two();
		$taxonomies_typography_style_three                      = new Group_Control_Taxonomies_Typography_Style_Three();
		$taxonomies_typography_style_four                       = new Group_Control_Taxonomies_Typography_Style_Four();
		$packages_title_typography                              = new Group_Control_Packages_Title_Typography();
		$packages_content_typography                            = new Group_Control_Packages_Content_Typography();
		$packages_button_typography                             = new Group_Control_Packages_Button_Typography();
		$packages_button_border                                 = new Group_Control_Packages_Button_Border();
		$packages_recommended_button_border                     = new Group_Control_Packages_Recommended_Button_Border();
		$packages_box_shadow                                    = new Group_Control_Packages_Box_Shadow();
		$packages_footnote_typography                           = new Group_Control_Packages_Footnote_Typography();
		$navigation_items_typography                            = new Group_Control_Navigation_Items_Typography();
		$navigation_button_border                               = new Group_Control_Navigation_Button_Border();
		$navigation_button_typography                           = new Group_Control_Navigation_Button_Typography();
		$navigation_submenu_item_typography                     = new Group_Control_Navigation_Submenu_Item_Typography();
		$navigation_submenu_border                              = new Group_Control_Navigation_Submenu_Border();
		$navigation_submenu_item_border                         = new Group_Control_Navigation_Submenu_Item_Border();
		$navigation_notification_message_typography             = new Group_Control_Navigation_Notification_Message_Title_Typography();
		$navigation_notification_message_text_typography        = new Group_Control_Navigation_Notification_Message_Text_Typography();
		$navigation_notification_message_footer_text_typography = new Group_Control_Navigation_Notification_Message_Footer_Text_Typography();
		$navigation_notification_message_footer_time_typography = new Group_Control_Navigation_Notification_Message_Footer_Time_Typography();
		$navigation_avatar_box_shadow                           = new Group_Control_Avatar_Box_Shadow();
		$search_keyword_label_typography                        = new Group_Control_Search_Keyword_Label_Typography();
		$search_keyword_box_shadow                              = new Group_Control_Search_Keyword_Box_Shadow();
		$banner_label_typography                                = new Group_Control_Banner_Label_Typography();
		$banner_search_field_border                             = new Group_Control_Banner_Search_Field_Border();
		$banner_button_typography                               = new Group_Control_Banner_Button_Typography();
		$banner_button_border                                   = new Group_Control_Banner_Button_Border();
		$banner_form_wrapper_border                             = new Group_Control_Banner_Form_Wrapper_Border();
		$banner_form_wrapper_box_shadow                         = new Group_Control_Banner_Form_Wrapper_Box_Shadow();
		$banner_features_categories_typography                  = new Group_Control_Banner_Features_Categories_Typography();
		$product_single_id_typography                           = new Group_Control_Single_Product_Id_Typography();
		$product_single_price_countdown_typography              = new Group_Control_Single_Product_Price_Countdown_Typography();
		$product_single_info_button_typography                  = new Group_Control_Single_Product_Info_Button_Typography();
		$product_single_info_button_box_shadow                  = new Group_Control_Single_Product_Info_Button_Box_Shadow();
		$product_single_info_button_border                      = new Group_Control_Single_Product_Info_Button_Border();
		$product_single_title_typography                        = new Group_Control_Single_Product_Title_Typography();
		$product_single_breadcrumbs_active_typography           = new Group_Control_Single_Product_Breadcrumbs_Active_Typography();
		$product_single_product_actions_typography              = new Group_Control_Single_Product_Actions_Typography();
		$product_single_gallery_box_shadow                      = new Group_Control_Single_Product_Gallery_Box_Shadow();
		$controls->add_group_control( $category_typography::get_type(), $category_typography );
		$controls->add_group_control( $category_title_typography::get_type(), $category_title_typography );
		$controls->add_group_control( $category_box_shadow::get_type(), $category_box_shadow );
		$controls->add_group_control( $product_custom_fields_typography::get_type(), $product_custom_fields_typography );
		$controls->add_group_control( $product_info_ratings_typography::get_type(), $product_info_ratings_typography );
		$controls->add_group_control( $product_promoted_icon_typography::get_type(), $product_promoted_icon_typography );
		$controls->add_group_control( $product_title_typography::get_type(), $product_title_typography );
		$controls->add_group_control( $product_tabs_typography::get_type(), $product_tabs_typography );
		$controls->add_group_control( $product_label_on_sale_typography::get_type(), $product_label_on_sale_typography );
		$controls->add_group_control( $product_box_shadow::get_type(), $product_box_shadow );
		$controls->add_group_control( $profiles_title_typography::get_type(), $profiles_title_typography );
		$controls->add_group_control( $profiles_text_typography::get_type(), $profiles_text_typography );
		$controls->add_group_control( $profiles_info_ratings_typography::get_type(), $profiles_info_ratings_typography );
		$controls->add_group_control( $profiles_link_typography::get_type(), $profiles_link_typography );
		$controls->add_group_control( $profiles_box_shadow::get_type(), $profiles_box_shadow );
		$controls->add_group_control( $testimonials_box_shadow::get_type(), $testimonials_box_shadow );
		$controls->add_group_control( $testimonials_content_typography::get_type(), $testimonials_content_typography );
		$controls->add_group_control( $testimonials_ratings_text_typography::get_type(), $testimonials_ratings_text_typography );
		$controls->add_group_control( $testimonials_author_typography::get_type(), $testimonials_author_typography );
		$controls->add_group_control( $testimonials_year_typography::get_type(), $testimonials_year_typography );
		$controls->add_group_control( $category_carousel_typography::get_type(), $category_carousel_typography );
		$controls->add_group_control( $category_carousel_box_shadow::get_type(), $category_carousel_box_shadow );
		$controls->add_group_control( $taxonomies_box_shadow::get_type(), $taxonomies_box_shadow );
		$controls->add_group_control( $taxonomies_typography::get_type(), $taxonomies_typography );
		$controls->add_group_control( $taxonomies_typography_style_two::get_type(), $taxonomies_typography_style_two );
		$controls->add_group_control( $taxonomies_typography_style_three::get_type(), $taxonomies_typography_style_three );
		$controls->add_group_control( $taxonomies_typography_style_four::get_type(), $taxonomies_typography_style_four );
		$controls->add_group_control( Group_Control_Taxonomies_Number_Of_Terms_Box_Shadow::get_type(), new Group_Control_Taxonomies_Number_Of_Terms_Box_Shadow() );
		$controls->add_group_control( Group_Control_Category_Carousel_Text_Box_Shadow::get_type(), new Group_Control_Category_Carousel_Text_Box_Shadow() );
		$controls->add_group_control( Group_Control_Category_Carousel_Number_Box_Shadow::get_type(), new Group_Control_Category_Carousel_Number_Box_Shadow() );
		$controls->add_group_control( Group_Control_Posts_Box_Shadow::get_type(), new Group_Control_Posts_Box_Shadow() );
		$controls->add_group_control( Group_Control_Posts_Post_Category_Typography::get_type(), new Group_Control_Posts_Post_Category_Typography() );
		$controls->add_group_control( Group_Control_Posts_Title_Typography::get_type(), new Group_Control_Posts_Title_Typography() );
		$controls->add_group_control( Group_Control_Posts_Content_Typography::get_type(), new Group_Control_Posts_Content_Typography() );
		$controls->add_group_control( Group_Control_Posts_Date_Typography::get_type(), new Group_Control_Posts_Date_Typography() );
		$controls->add_group_control( Group_Control_Posts_Author_Typography::get_type(), new Group_Control_Posts_Author_Typography() );
		$controls->add_group_control( $packages_title_typography::get_type(), $packages_title_typography );
		$controls->add_group_control( $packages_content_typography::get_type(), $packages_content_typography );
		$controls->add_group_control( $packages_button_typography::get_type(), $packages_button_typography );
		$controls->add_group_control( $packages_button_border::get_type(), $packages_button_border );
		$controls->add_group_control( $packages_recommended_button_border::get_type(), $packages_recommended_button_border );
		$controls->add_group_control( $packages_box_shadow::get_type(), $packages_box_shadow );
		$controls->add_group_control( $packages_footnote_typography::get_type(), $packages_footnote_typography );
		$controls->add_group_control( $navigation_items_typography::get_type(), $navigation_items_typography );
		$controls->add_group_control( $navigation_button_border::get_type(), $navigation_button_border );
		$controls->add_group_control( $navigation_button_typography::get_type(), $navigation_button_typography );
		$controls->add_group_control( $navigation_submenu_border::get_type(), $navigation_submenu_border );
		$controls->add_group_control( $navigation_submenu_item_border::get_type(), $navigation_submenu_item_border );
		$controls->add_group_control( $navigation_submenu_item_typography::get_type(), $navigation_submenu_item_typography );
		$controls->add_group_control( $navigation_notification_message_typography::get_type(), $navigation_notification_message_typography );
		$controls->add_group_control( $navigation_notification_message_text_typography::get_type(), $navigation_notification_message_text_typography );
		$controls->add_group_control( $navigation_notification_message_footer_text_typography::get_type(), $navigation_notification_message_footer_text_typography );
		$controls->add_group_control( $navigation_notification_message_footer_time_typography::get_type(), $navigation_notification_message_footer_time_typography );
		$controls->add_group_control( $navigation_avatar_box_shadow::get_type(), $navigation_avatar_box_shadow );
		$controls->add_group_control( $search_keyword_label_typography::get_type(), $search_keyword_label_typography );
		$controls->add_group_control( $search_keyword_box_shadow::get_type(), $search_keyword_box_shadow );
		$controls->add_group_control( $banner_label_typography::get_type(), $banner_label_typography );
		$controls->add_group_control( $banner_search_field_border::get_type(), $banner_search_field_border );
		$controls->add_group_control( $banner_button_typography::get_type(), $banner_button_typography );
		$controls->add_group_control( $banner_button_border::get_type(), $banner_button_border );
		$controls->add_group_control( $banner_form_wrapper_border::get_type(), $banner_form_wrapper_border );
		$controls->add_group_control( $banner_form_wrapper_box_shadow::get_type(), $banner_form_wrapper_box_shadow );
		$controls->add_group_control( $banner_features_categories_typography::get_type(), $banner_features_categories_typography );
		$controls->add_group_control( Group_Control_Search_Page_Border::get_type(), new Group_Control_Search_Page_Border );
		$controls->add_group_control( Group_Control_Filters_Typography::get_type(), new Group_Control_Filters_Typography() );
		$controls->add_group_control( Group_Control_Filters_Box_Shadow::get_type(), new Group_Control_Filters_Box_Shadow() );
		$controls->add_group_control( $product_single_id_typography::get_type(), $product_single_id_typography );
		$controls->add_group_control( $product_single_price_countdown_typography::get_type(), $product_single_price_countdown_typography );
		$controls->add_group_control( $product_single_info_button_typography::get_type(), $product_single_info_button_typography );
		$controls->add_group_control( $product_single_info_button_box_shadow::get_type(), $product_single_info_button_box_shadow );
		$controls->add_group_control( $product_single_info_button_border::get_type(), $product_single_info_button_border );
		$controls->add_group_control( $product_single_title_typography::get_type(), $product_single_title_typography );
		$controls->add_group_control( $product_single_breadcrumbs_active_typography::get_type(), $product_single_breadcrumbs_active_typography );
		$controls->add_group_control( $product_single_product_actions_typography::get_type(), $product_single_product_actions_typography );
		$controls->add_group_control( $product_single_gallery_box_shadow::get_type(), $product_single_gallery_box_shadow );
		$controls->add_group_control( Group_Control_Single_Product_Gallery_Border::get_type(), new Group_Control_Single_Product_Gallery_Border() );
		$controls->add_group_control( Group_Control_Single_Product_Gallery_Current_Box_Shadow::get_type(), new Group_Control_Single_Product_Gallery_Current_Box_Shadow() );
		$controls->add_group_control( Group_Control_Single_Product_Gallery_Current_Border::get_type(), new Group_Control_Single_Product_Gallery_Current_Border() );
		$controls->add_group_control( Group_Control_Single_Product_Specification_Typography::get_type(), new Group_Control_Single_Product_Specification_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Specification_Single_Value_Typography::get_type(), new Group_Control_Single_Product_Specification_Single_Value_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Specification_Single_Value_Label_Typography::get_type(), new Group_Control_Single_Product_Specification_Single_Value_Label_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Specification_Multiple_Value_Subtitle_Typography::get_type(), new Group_Control_Single_Product_Specification_Multiple_Value_Subtitle_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Specification_Multiple_Value_Typography::get_type(), new Group_Control_Single_Product_Specification_Multiple_Value_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Description_Typography::get_type(), new Group_Control_Single_Product_Description_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Sidebar_Menu_Typography::get_type(), new Group_Control_Single_Product_Sidebar_Menu_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Sticky_Menu_Typography::get_type(), new Group_Control_Single_Product_Sticky_Menu_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Sticky_Menu_Box_Shadow::get_type(), new Group_Control_Single_Product_Sticky_Menu_Box_Shadow() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Name_Typography::get_type(), new Group_Control_Single_Product_Owner_Name_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Phone_Typography::get_type(), new Group_Control_Single_Product_Owner_Phone_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Phone_Button_Typography::get_type(), new Group_Control_Single_Product_Owner_Phone_Button_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Button_Typography::get_type(), new Group_Control_Single_Product_Owner_Button_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Button_Border::get_type(), new Group_Control_Single_Product_Owner_Button_Border() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Hover_Button_Border::get_type(), new Group_Control_Single_Product_Owner_Hover_Button_Border() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Hover_Button_Typography::get_type(), new Group_Control_Single_Product_Owner_Hover_Button_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Messages_Button_Typography::get_type(), new Group_Control_Single_Product_Owner_Messages_Button_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Messages_Button_Border::get_type(), new Group_Control_Single_Product_Owner_Messages_Button_Border() );
		$controls->add_group_control( Group_Control_Single_Product_Owner_Button_Messages_Text_Typography::get_type(), new Group_Control_Single_Product_Owner_Button_Messages_Text_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Location_Map_Address_Typography::get_type(), new Group_Control_Single_Product_Location_Map_Address_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Location_Map_Expand_Map_Typography::get_type(), new Group_Control_Single_Product_Location_Map_Expand_Map_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Safety_Tips_Link_Typography::get_type(), new Group_Control_Single_Product_Safety_Tips_Link_Typography() );
		$controls->add_group_control( Group_Control_Single_Product_Safety_Tips_Title_Typography::get_type(), new Group_Control_Single_Product_Safety_Tips_Title_Typography() );
	}

	public function add_custom_category( $elements_manager ) {
		$elements_manager->add_category(
			'lisfinity',
			[
				'title' => __( 'Lisfinity', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);
		$elements_manager->add_category(
			'lisfinity-banner',
			[
				'title' => __( 'Lisfinity Banner', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);
		$elements_manager->add_category(
			'lisfinity-navigation',
			[
				'title' => __( 'Lisfinity Navigation', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);
		$elements_manager->add_category(
			'lisfinity-search',
			[
				'title' => __( 'Lisfinity Search Elements', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);

		$elements_manager->add_category(
			'lisfinity-category',
			[
				'title' => __( 'Lisfinity Category', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);

		$elements_manager->add_category(
			'lisfinity-single-product',
			[
				'title' => __( 'Lisfinity Single Listing Elements', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);

		$elements_manager->add_category(
			'lisfinity-search-page',
			[
				'title' => __( 'Lisfinity Search Page', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);

		$elements_manager->add_category(
			'lisfinity-auth',
			[
				'title' => __( 'Lisfinity Auth', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);

		$elements_manager->add_category(
			'lisfinity-business-profile',
			[
				'title' => __( 'Lisfinity Business Profile Page', 'lisfinity-core' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}

}
