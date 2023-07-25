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
 * ConcreteIron_Subheading widget class.
 *
 * @since 1.0.0
 */
class ConcreteIron_Subheading extends Widget_Base {
    const HANDLE = 'concreteiron-subheading';
    const DOMAIN = 'concreteiron';
    /**
     * Class constructor.
     *
     * @param array $data Widget data.
     * @param array $args Widget arguments.
     */
    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( self::HANDLE, plugins_url( '/assets/css/' . self::HANDLE . '.css', CONCRETEIRON ), array(), '1.0.0' );
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
        return self::HANDLE;
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
        return __( 'Concrete Iron Subheading', self::DOMAIN );
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
        return array( self::DOMAIN );
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
                'label' => __( 'Content', self::DOMAIN ),
            )
        );

        $this->add_control(
            'ci_subheading',
            array(
                'label'   => __( 'Subheading', self::DOMAIN ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Subheading', self::DOMAIN ),
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
        $this->add_inline_editing_attributes( 'ci_subheading', 'none' );
        ?>
        <div class="ci-widget-subheading">
            <div class="ci-widget-subheading-inner">
                <h4 <?php echo $this->get_render_attribute_string( 'ci_subheading' ); ?>><?php echo wp_kses( $settings['ci_subheading'], 'span' ); ?></h4>
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
        view.addInlineEditingAttributes( 'ci_subheading', 'none' );
        #>
        <div class="ci-widget-subheading">
            <div class="ci-widget-subheading-inner">
                <h2 {{{ view.getRenderAttributeString( 'ci_subheading' ) }}}>{{{ settings.ci_subheading }}}</h2>
            </div>
        </div>
        <?php
    }
}
