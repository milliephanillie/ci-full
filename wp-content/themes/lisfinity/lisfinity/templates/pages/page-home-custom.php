<?php
/**
 * Template Name: Custom Page | Custom Homepage Template
 * Description: Page template that is being used as the main page of the site
 *
 * @author pebas
 * @package templates/pages
 * @version 1.0.0
 */
?>

<?php $is_error = ! empty( $_GET['e'] ) ? $_GET['e'] : false; ?>
<?php if ( $is_error ) : // if there's an error display it instead of the content. ?>
    <?php
    $args = [];
    ob_start();
    include lisfinity_get_template_part( 'no-business', 'errors', [] );
    $error_html = ob_get_clean();
    wp_die( wp_kses_post( $error_html ), sprintf( __( '%s › Error', 'lisfinity-core' ), get_option( 'blogname' ) ), $args );
    ?>
<?php endif; ?>

<?php get_header(); ?>
<?php the_post(); ?>


<?php if ( '1' !== lisfinity_get_option( 'hero-disable' ) ) : ?>
    <?php $page_id = get_the_ID(); ?>
    <?php $banner_type = lisfinity_get_option( 'hero-type' ) ?>
    <?php $banner_bg = lisfinity_get_option( 'home-banner-bg' ); ?>
    <?php $banner_bg_x = lisfinity_get_option( 'home-banner-bg-position-x' ); ?>
    <?php $banner_bg_y = lisfinity_get_option( 'home-banner-bg-position-y' ); ?>
    <?php $banner_video = lisfinity_get_option( 'home-banner-video' ); ?>
    <?php $banner_overlay = lisfinity_get_option( 'home-overlay' ) ?>
    <?php $banner_video_image = lisfinity_get_option( 'home-video-fallback' ); ?>
    <!-- Section | Banner -->
    <section class="banner relative flex justify-center items-center bg-grey-900 overflow-x-hidden">
        <?php if ( ! empty( $banner_bg['url'] ) && 'image' == $banner_type ) : ?>
            <span class="banner--bg absolute inset-0 bg-cover"
                  style="background-image: url(<?php echo esc_url( $banner_bg['url'] ); ?>); background-position: <?php echo esc_attr( "{$banner_bg_x} {$banner_bg_y}" ); ?>">
			</span>
        <?php endif; ?>
        <?php if ( ! empty( $banner_video_image ) && 'video' == $banner_type ) : ?>
            <span class="banner--bg absolute inset-0 bg-cover"
                  style="background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $banner_video_image, 'full' ) ); ?>); background-position: <?php echo esc_attr( "{$banner_bg_x} {$banner_bg_y}" ); ?>">
    		</span>
        <?php endif; ?>

        <?php if ( 'video' === $banner_type && ! empty( $banner_video ) ) : ?>
            <?php $video_options = [
                'id'              => lisfinity_get_youtube_id_from_src( $banner_video ),
                'loop'            => lisfinity_get_option( 'home-video-loop' ),
                'starts'          => lisfinity_get_option( 'home-video-starts' ),
                'ends'            => lisfinity_get_option( 'home-video-ends' ),
                'show_on_mobiles' => lisfinity_get_option( 'home-video-mobiles' ),
            ]; ?>
            <div id="videoId"
                 class="home--video"
                 data-options="<?php echo esc_attr( json_encode( $video_options ) ); ?>">
                <div id="player"></div>
            </div>
        <?php endif; ?>
        <span class="video--overlay"
              style="background-color: <?php echo esc_attr( $banner_overlay['rgba'] ); ?>"></span>

        <div class="container mx-auto z-10 px-20 lg:p-0">
            <div class="banner--wrapper">
                <?php $banner_text = lisfinity_get_option( 'home-banner-text' ); ?>
                <?php if ( ! empty( $banner_text ) ) : ?>
                    <!-- Banner | Home -->
                    <div class=banner--home">
                        <?php echo wp_kses_post( $banner_text ); ?>
                        <p style="font-size: 24px; line-height: 32px; font-weight: bold; text-align: center;">Basic Listings (Normally $30) Free for a Limited Time!</p>
                    </div>
                <?php endif; ?>

                <div class="custom-search-wrapper">
                    <?php $search = get_page_by_title( 'Search', 'OBJECT', 'page'); ?>
                    <?php if ( ! empty($search) ) : ?>
                    <div class="custom-search-bubble">
                        <?php $search_page = lisfinity_get_page_id( 'page-search' ); ?>
                        <form id="main-search" action="<?php echo trailingslashit(home_url()) . trailingslashit(lisfinity_get_slug( 'slug-category', 'ad-category' )) . trailingslashit('concrete-equipment') ?>">
                            <div class="custom-search-bubble-inner">
                                <div class="custon-search-input-wrapper">
                                    <img src="<?php echo trailingslashit(get_template_directory_uri()) . 'custom/images/search.svg'; ?>" />
                                    <input id="main-search-keyword" type="text" name="keyword" placeholder="What are you looking for?" />
                                </div>

                                <div class="custom-search-categories">
                                    <?php $banner_taxonomies = lisfinity_get_option( 'home-banner-taxonomies' ); ?>
                                    <?php if ( ! empty( $banner_taxonomies ) ) : ?>
                                    <?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
                                    <?php $search_page_permalink = get_permalink( $search_page_id ); ?>
                                    <?php $groups_model = new \Lisfinity\Models\Taxonomies\GroupsAdminModel(); ?>
                                    <?php $group_options = $groups_model->get_options(); ?>
                                    <?php $group_slugs = array_column( $group_options, 'slug' ); ?>
                                    <?php

                                    $terms = get_terms('concrete-equipment-type', [
                                            'hide_empty' => false,
                                    ]);

                                    ?>
                                    <select id="main-search-category" name="tax[concrete-equipment-type]">
                                        <option value="">All Categories</option>
                                        <?php foreach ( $terms as $term ) : ?>
                                                <?php
                                                $url = get_site_url( null, '', 'relative' ) . '/' . lisfinity_get_slug( 'slug-category', 'ad-category' ) . '/' . 'concrete-equipment/';

                                                ?>
                                                <option value="<?php echo sanitize_title($term->name) ?>" data-url="<?php echo esc_url( $url ) . '?tax%5Bconcrete-equipment-type%5D='.$term->slug; ?>"><?php echo $term->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php endif; ?>
                                </div>

                                <div class="home-search-button">
                                    <button id="main-search-button" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>

                <?php $banner_taxonomies = lisfinity_get_option( 'home-banner-taxonomies' ); ?>
                <?php $icon_size = lisfinity_get_option( 'home-banner-taxonomies-icon-size' ) ?>
                <?php $icon_size_mobile = lisfinity_get_option( 'home-banner-taxonomies-icon-size-mobile' ) ?>
                <?php if ( ! empty( $banner_taxonomies ) ) : ?>
                    <?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
                    <?php $search_page_permalink = get_permalink( $search_page_id ); ?>

                <?php endif; ?>
                <?php
                $model  = new \Lisfinity\Models\Taxonomies\GroupsAdminModel();
                $groups = $model->get_groups_with_taxonomies();
                if ( empty( $groups ) ) :
                    ?>
                    <?php $banner_terms = lisfinity_get_option( 'home-banner-terms' ); ?>
                    <?php if ( ! empty( $banner_terms ) ) : ?>
                    <?php $search_page_id = lisfinity_get_page_id( 'page-search' ); ?>
                    <?php $search_page_permalink = get_permalink( $search_page_id ); ?>
                    <!-- Banner | Taxonomies -->
                    <div class="banner--taxonomies flex flex-wrap justify-center items-center mt-30 -mb-10 -mx-2">
                        <?php foreach ( $banner_terms as $term_id ) : ?>
                            <?php $term = get_term( $term_id ); ?>
                            <?php if ( ! empty( $term ) ) : ?>
                                <?php $term_meta = get_term_meta( $term->term_id ); ?>
                                <?php $icon = ! empty( $term_meta['icon'][0] ) ? wp_get_attachment_image_url( $term_meta['icon'][0], 'full' ) : false; ?>
                                <a href="<?php echo esc_url( add_query_arg( "tax[{$term->taxonomy}]", $term->slug, $search_page_permalink ) ); ?>"
                                   class="banner--taxonomy__container flex-center flex-col mt-10 px-2">
                                    <div class="banner--taxonomy__bg flex-center h-64 w-86 sm:h-86 sm:w-86 rounded">
                                        <?php if ( $icon ) : ?>
                                            <div class="banner--taxonomy__icon ">
                                                <img src="<?php echo esc_url( $icon ); ?>" alt="icon"
                                                >
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="mt-6 text-sm text-grey-400"><?php echo esc_html( $term->name ); ?></h5>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php $banner_style = lisfinity_get_option( 'home-banner-style' ); ?>
        <?php if ( '2' === $banner_style ) : ?>
            <svg
                class="banner--svg absolute bottom-0 left-0"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 3000 50">
                <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                      d="M27.716,53.999 L0.000,36.380 L18.647,35.471 C18.647,35.471 402.770,4.728 924.879,37.290 C1446.988,69.851 1767.713,0.180 2021.308,-0.002 C2135.326,-0.084 2147.565,25.649 2126.505,53.999 L27.716,53.999 Z"/>
            </svg>
        <?php endif; ?>
    </section>
<?php endif; ?>

<main>
    <?php the_content(); ?>
</main>

<?php get_footer(); ?>
