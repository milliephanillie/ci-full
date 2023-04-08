<?php
/**
 * ConcreteIron_Paragraph class.
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
 * ConcreteIron_Latest_Posts widget class.
 *
 * @since 1.0.0
 */
class ConcreteIron_Latest_Posts extends Widget_Base {
    const HANDLE = 'concreteiron-latest-posts';
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
        return __( 'Concrete Iron Latest Posts', self::DOMAIN );
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

        $this->end_controls_section();
    }

    /**
     * Get the latest posts
     *
     * @return \WP_Error
     */
    public function get_ci_latest_posts() {
        $query = new \WP_Query([
                "post_status" => 'publish',
                "post_type" => 'post',
                "posts_per_page" => 8,
        ]);

        if( ! $query->found_posts ) {
            return null;
        }

        $data = [];

        foreach ($query->posts as $post) {
            $category = get_the_category();
            $category = $category ? $category[0]->cat_name : '';
            $author = get_user_by( 'ID', $post->post_author );
            $author_name = $author ? $author->display_name : '';
            $default = trailingslashit(get_template_directory_uri()) . 'custom/images/new-blog-post.jpg';
            $thumb = ( get_the_post_thumbnail_url($post->ID) !== false)  ? get_the_post_thumbnail_url($post->ID) : $default;

            $array = [
                    "thumbnail_url" => $thumb,
                    "post_title" => $post->post_title,
                    "permalink" => get_permalink($post->ID),
                    "post_excerpt" => wp_trim_words( $post->post_content, 40, " ..."),
                    "category" => $category,
                    "author_name" => $author_name,
                    "post_date" => date("M d Y", strtotime($post->post_date)),
                    "avatar" => get_avatar_url($post->post_author)
            ];

            array_push($data, $array);
        }

        return $data;
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
        $posts = $this->get_ci_latest_posts();
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes( 'ci_paragraph', 'none' );

        $html = '<h1>No posts</h1>';

        if( $posts ) {
            $html = '<div class="ci-widget-latest-posts">';

            foreach ($posts as $post) {
                $html .= '<div class="ci-widget-post-card">
                    <div class="ci-widget-post-card-inner">
                        <div class="ci-widget-post-card-image" style=" background-image: url(' . $post['thumbnail_url'] . '); background-size: cover; background-position: center;">
                            <a class="ci-widget-post-card-image-link" href="' . $post['permalink'] . '"></a>
                        </div>
                        <div class="ci-widget-post-card-cat">
                            <span>' . $post['category'] .'</span>
                        </div>
                        <div class="ci-widget-post-card-body">
                            <div class="ci-widget-latest-posts-title">
                                <h3><a class="ci-widget-latest-posts-title-link" href="' . $post['permalink'] . '">' . $post['post_title'] . '</a></h3>
                            </div>
                            <div class="ci-widget-latest-posts-paragraph">
                                <p>' .$post['post_excerpt'] . '</p>
                            </div>
                        </div>
                        <div class="ci-widget-latest-posts-footer">
                            <div class="ci-widget-latest-posts-author-avatar">
                              <img src="' . $post['avatar'] . '" />
                            </div>
                            <div class="ci-widget-latest-posts-meta">
                                <div class="ci-widget-latest-posts-author-name">
                                    <span>' . $post['author_name'] .' </span>
                                </div>
                                <div class="ci-widget-latest-posts-date">
                                    <span>' . $post['post_date'] .' </span>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>';
            }

            $html .= '</div>';
        }

        echo $html;
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
        view.addInlineEditingAttributes( 'ci_paragraph', 'none' );
        #>
        <div class="ci-widget-paragraph">
            <div class="ci-widget-paragraph-inner">
                <h2 {{{ view.getRenderAttributeString( 'ci_paragraph' ) }}}>{{{ settings.ci_paragraph }}}</h2>
            </div>
        </div>
        <?php
    }
}