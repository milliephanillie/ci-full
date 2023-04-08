<?php


namespace Lisfinity\Shortcodes\Search;


use Elementor\Controls_Manager;
use Lisfinity\Abstracts\Shortcode;
use Lisfinity\Shortcodes\Controls\Banner\Group_Control_Banner_Form_Wrapper_Box_Shadow;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Filters_Typography;
use Lisfinity\Shortcodes\Controls\SearchPage\Group_Control_Search_Page_Border;

class Search_FilterMenu extends Shortcode
{


    /**
     * Get the name of the shortcode
     * -----------------------------
     *
     * @return string
     */
    public function get_name()
    {
        return 'search-filter-menu';
    }

    /**
     * Get the displayed title of the shortcode
     * ----------------------------------------
     *
     * @return string
     */
    public function get_title()
    {
        return sprintf(__('%s Search Filter Menu Trigger', 'lisfinity-core'), '<strong>Lisfinity > </strong>');
    }

    /**
     * Get the icon for the shortcode
     * ------------------------------
     *
     * @return string
     */
    public function get_icon()
    {
        return 'fas fa-search';
    }

    /**
     * Set the categories where the shortcode will be displayed
     * --------------------------------------------------------
     *
     * @return array
     */
    public function get_categories()
    {
        return ['lisfinity-search-page'];
    }

    /**
     * Register shortcode controls
     * ---------------------------
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon Color', 'elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 1)',
                'selectors' => [
                    '{{WRAPPER}} .cst-color' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the content on frontend
     * ------------------------------
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $args = [
            'settings' => $settings,
        ];

        include lisfinity_get_template_part('search-menu-trigger', 'shortcodes/search-page', $args);
    }

}
