<?php
/**
 * ConcreteIronTitle class.
 *
 * @category   Class
 * @package    ConcreteIron
 * @subpackage WordPress
 * @author     Philip Rudy <me@philiparudy.com>
 * @copyright  2022 Philip Rudy
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @since      1.0.0
 * php version 7.3.9
 */
namespace ConcreteIron\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();
/**
 * ConcreteIronTitle widget class.
 *
 * @since 1.0.0
 */
class ConcreteIron_Title_Center extends Widget_Base {
    /**
     * Class constructor.
     *
     * @param array $data Widget data.
     * @param array $args Widget arguments.
     */
    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'concreteiron-title-center', plugins_url( '/assets/css/concreteiron-title-center.css', CONCRETEIRON ), array(), '1.0.0' );
    }
    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'concreteiron-title-center';
    }
    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Concrete Iron Title Centered', 'concreteiron' );
    }
    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'fa fa-pencil';
    }
    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return array( 'general' );
    }

    /**
     * Enqueue styles.
     */
    public function get_style_depends() {
        return array( 'concreteiron' );
    }
    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            array(
                'label' => __( 'Content', 'concreteiron' ),
            )
        );
        $this->add_control(
            'title_centered',
            array(
                'label'   => __( 'Title', 'concreteiron' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Title', 'concreteiron' ),
                'placeholder' => esc_html__( 'Enter your title <span>Using Span Tags for color</span>', 'concreteiron' ),
            )
        );
        $this->add_control(
            'subtitle_centered',
            array(
                'label'   => __( 'Subtitle', 'concreteiron' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Subtitle', 'concreteiron' ),
            )
        );

        $this->end_controls_section();
    }
    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes( 'title_centered', 'none' );
        $this->add_inline_editing_attributes( 'subtitle_centered', 'basic' );
        ?>
        <div class="ci-header-widget ci-header-widget-center">
            <div class="ci-header-widget-content">
                <div class="ci-header-widget-header">
                    <h2 <?php echo $this->get_render_attribute_string( 'title_centered' ); ?>><?php echo $settings['title_centered']; ?></h2>
                </div>
                <div class="ci-header-widget-subtitle">
                    <div <?php echo $this->get_render_attribute_string( 'subtitle_centered' ); ?>><p><?php echo $settings['subtitle_centered']; ?></p></div>
            </div>
        </div>
        <?php
    }
    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function content_template() {
        ?>
        <#
        view.addInlineEditingAttributes( 'title_centered', 'none' );
        view.addInlineEditingAttributes( 'subtitle_centered', 'basic' );
        #>
        <div class="ci-header-widget ci-header-widget-center">
            <div class="ci-header-widget-content">
                <div class="ci-header-widget-header">
                    <h2 {{{ view.getRenderAttributeString( 'title_centered' ) }}}>{{{ settings.title_centered }}}</h2>
                </div>
                <div class="ci-header-widget-subtitle">
                    <div {{{ view.getRenderAttributeString( 'subtitle_centered' ) }}}><p>{{{ settings.subtitle_centered }}}</p></div>
            </div>
        </div>
        <?php
    }
}
