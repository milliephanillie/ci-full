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
class ConcreteIron_Title extends Widget_Base {
    /**
     * Class constructor.
     *
     * @param array $data Widget data.
     * @param array $args Widget arguments.
     */
    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'concreteiron-title', plugins_url( '/assets/css/concreteiron-title.css', CONCRETEIRON ), array(), '1.0.0' );
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
        return 'concreteiron-title';
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
        return __( 'Concrete Iron Title', 'concreteiron' );
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
            'title',
            array(
                'label'   => __( 'Title', 'concreteiron' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Title', 'concreteiron' ),
                'placeholder' => esc_html__( 'Enter your title <span>Using Span Tags for color</span>', 'concreteiron' ),
            )
        );
        $this->add_control(
            'subtitle',
            array(
                'label'   => __( 'Subtitle', 'concreteiron' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Subtitle', 'concreteiron' ),
            )
        );

//        $this->add_control(
//            'description',
//            array(
//                'label'   => __( 'Description', 'concreteiron' ),
//                'type'    => Controls_Manager::TEXTAREA,
//                'default' => __( 'Description', 'concreteiron' ),
//            )
//        );
//        $this->add_control(
//            'content',
//            array(
//                'label'   => __( 'Content', 'concreteiron' ),
//                'type'    => Controls_Manager::WYSIWYG,
//                'default' => __( 'Content', 'concreteiron' ),
//            )
//        );
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
        $this->add_inline_editing_attributes( 'title', 'none' );
        $this->add_inline_editing_attributes( 'subtitle', 'basic' );
        ?>
            <div class="ci-header-widget">
                <div class="ci-header-widget-content">
                    <div class="ci-header-widget-header">
                        <h2 <?php echo $this->get_render_attribute_string( 'title' ); ?>><?php echo $settings['title']; ?></h2>
                    </div>
                    <div class="ci-header-widget-subtitle">
                        <div <?php echo $this->get_render_attribute_string( 'subtitle' ); ?>><p><?php echo wp_kses( $settings['subtitle'], ['span' => []] ); ?></p></div>
                    </div>
                </div>
                <div class="ci-header-widget-all">
                    <a href="">All Categories <i class="fa-solid fa-arrow-right"></i></a>
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
        view.addInlineEditingAttributes( 'title', 'none' );
        view.addInlineEditingAttributes( 'subtitle', 'basic' );
        #>
        <div class="ci-header-widget">
            <div class="ci-header-widget-content">
                <div class="ci-header-widget-header">
                    <h2 {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</h2>
                </div>
                <div class="ci-header-widget-subtitle">
                    <div {{{ view.getRenderAttributeString( 'subtitle' ) }}}><p>{{{ settings.subtitle }}}</p></div>
                </div>
            </div>
            <div class="ci-header-widget-all">
                <a href="">All Categories <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
        <?php
    }
}