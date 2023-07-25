<?php
/**
 * Helper file for global theme functions.
 *
 * @author pebas
 * @package helpers
 * @version 1.0.0
 */

function lisfinity_remove_type_from_scripts( $tag, $handle ) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}

add_filter( 'style_loader_tag', 'lisfinity_remove_type_from_scripts', 10, 2 );
add_filter( 'script_loader_tag', 'lisfinity_remove_type_from_scripts', 10, 2 );

if ( ! function_exists( 'lisfinity_list_js_translation_strings' ) ) {
    /**
     * List all js translation strings from the given file
     * ---------------------------------------------------
     */
    function lisfinity_list_js_translation_strings() {
        $path = LISFINITY_CORE_DIR . 'languages/lisfinity-core-js.pot';
        $src  = file_get_contents( $path );
        preg_match_all( '/msgid_plural\s+\"([^\"]*)\"/', $src, $matches );
        $msgids = $matches[1];

        $all = [];
        foreach ( $msgids as $msgid ) {
            if ( ! empty( $msgid ) ) {
                $all[] = '__("' . $msgid . '", "lisfinity-core")';
            }
        }

        return implode( ',', $all );
    }
}

if ( ! function_exists( 'lisfinity_dd' ) ) {
    /**
     * Custom var dump function instead of var_dump()
     * ----------------------------------------------
     */
    function lisfinity_dd() {
        array_map(
            function ( $x ) {
                dump( $x );
            },
            func_get_args()
        );
    }
}

if ( ! function_exists( 'lisfinity_get_formatted_time' ) ) {
    /**
     * Get formatted time based on user defined theme option
     * -----------------------------------------------------
     *
     * @param bool $seconds Param that defines if the seconds
     *       should be included within the time format.
     * @param string $divider Choose divider for time format
     * values.
     *
     * @return string
     */
    function lisfinity_get_formatted_time( $seconds = true, $divider = ':', $full_date = true ) {
        $format = lisfinity_get_option( 'format-time' );
        if ( empty( $format ) ) {
            $format = '24';
        }
        $includes_seconds = $seconds ? 'S' : '';

        if($seconds) {
            $time             = "H{$divider}i{$divider}{$includes_seconds}";
        } else {
            $time = "H{$divider}i";
        }

        if ( 'ampm' === $format ) {
            if($seconds) {
                $time = "h{$divider}i{$divider}{$includes_seconds} A";
            } else {
                $time = "h{$divider}i A";
            }
        }

        $value = $full_date ? "j M, Y $time" : $time;

        return apply_filters( 'lisfinity__get_formatted_time', "$value" );
    }

    add_filter( 'lisfinity__get_time_format', 'lisfinity_get_formatted_time' );
}

if ( ! function_exists( 'lisfinity_google_api_key' ) ) {
    /**
     * Return Google API key from theme option
     * ---------------------------------------
     *
     * @param string $key Api key that is expected by carbon fields.
     *
     * @return string
     */
    function lisfinity_google_api_key( $key ) {
        $api_key = lisfinity_get_option( 'map-api' );

        return apply_filters( 'lisfinity__google_api_key', $api_key );
    }

    add_filter( 'carbon_fields_map_field_api_key', 'lisfinity_google_api_key' );
}

if ( ! function_exists( 'lisfinity_format_map_default_position' ) ) {
    /**
     * Format default map position from theme option provided values
     * -------------------------------------------------------------
     *
     * @return array
     */
    function lisfinity_format_map_default_position() {
        $lat       = lisfinity_get_option( 'map-default-latitude' );
        $lng       = lisfinity_get_option( 'map-default-longitude' );
        $zoom      = lisfinity_get_option( 'map-default-zoom' );
        $formatted = [ $lat, $lng, $zoom ];

        return apply_filters( 'lisfinity__format_map_default_position', $formatted );
    }
}

if ( ! function_exists( 'lisfinity_convert_slug_to_name' ) ) {
    function lisfinity_convert_slug_to_name( $slug ) {
        $name  = str_replace( '-', ' ', $slug );
        $names = explode( ' ', ucfirst( $name ) );
        $name  = implode( ' ', $names );

        return apply_filters( 'lisfinity__convert_slug_to_name', $name );
    }
}

if ( ! function_exists( 'lisfinity_convert_to_option' ) ) {
    /**
     * Convert given tags to the counterpart option
     * --------------------------------------------
     *
     * @param $string
     * @param string $product_id
     *
     * @return mixed
     */
    function lisfinity_convert_to_option( $string, $product_id = '', $type = '' ) {
        $product_id = ! empty( $product_id ) ? $product_id : get_the_ID();
        if($type === 'payment_subscription' ) {
            $prefix = 'subscription-';
        } else {
            $prefix = 'package-';
        }
        $convert    = [
            '[products-limit]'    => carbon_get_post_meta( $product_id, "{$prefix}products-limit" ),
            '[products-duration]' => carbon_get_post_meta( $product_id, "{$prefix}products-duration" ),
            '[package-price]'     => wc_get_product( $product_id )->get_price(),
        ];
        foreach ( $convert as $key => $value ) {
            if ( false !== strpos( $string, $key ) ) {
                $string = str_replace( $key, $value, $string );
            }
        }

        return $string;
    }
}

if ( ! function_exists( 'lisfinity_replace_first_instance' ) ) {
    /**
     * Prepare default post meta data to be saved in a format that
     * carbon fields plugin is requiring
     * -----------------------------------------------------------
     *
     * @param $haystack
     * @param $needle
     * @param $replace
     *
     * @return mixed
     */
    function lisfinity_replace_first_instance( $haystack, $needle, $replace ) {
        $new_string = $haystack;
        $pos        = strpos( $haystack, $needle );
        if ( $pos !== false ) {
            $new_string = substr_replace( $haystack, $replace, $pos, strlen( $needle ) );
        }

        return $new_string;
    }
}

if ( ! function_exists( 'lisfinity_insert_at_position' ) ) {
    /**
     * Insert array into the other array at the specified position
     * -----------------------------------------------------------
     *
     * @param $array
     * @param $insert
     * @param $position
     *
     * @return array
     */
    function lisfinity_insert_at_position( $array, $insert, $position ) {
        return array_slice( $array, 0, $position, true ) + $insert + array_slice( $array, $position, null, true );
    }
}

if ( ! function_exists( 'lisfinity_load_social_icon' ) ) {
    /**
     * Load correct social icon SVG
     * ----------------------------
     *
     * @param $icon
     *
     * @return mixed|void
     */
    function lisfinity_load_social_icon_svg( $icon ) {
        $html = '';
        switch ( $icon ) :
            case 'facebook':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__facebook" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
                break;
            case 'twitter':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__twitter" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
                break;
            case 'instagram':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__instagram" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
                break;
            case 'dribbble':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__dribbble" viewBox="0 0 24 24"><path d="M12 0c-6.628 0-12 5.373-12 12s5.372 12 12 12 12-5.373 12-12-5.372-12-12-12zm9.885 11.441c-2.575-.422-4.943-.445-7.103-.073-.244-.563-.497-1.125-.767-1.68 2.31-1 4.165-2.358 5.548-4.082 1.35 1.594 2.197 3.619 2.322 5.835zm-3.842-7.282c-1.205 1.554-2.868 2.783-4.986 3.68-1.016-1.861-2.178-3.676-3.488-5.438.779-.197 1.591-.314 2.431-.314 2.275 0 4.368.779 6.043 2.072zm-10.516-.993c1.331 1.742 2.511 3.538 3.537 5.381-2.43.715-5.331 1.082-8.684 1.105.692-2.835 2.601-5.193 5.147-6.486zm-5.44 8.834l.013-.256c3.849-.005 7.169-.448 9.95-1.322.233.475.456.952.67 1.432-3.38 1.057-6.165 3.222-8.337 6.48-1.432-1.719-2.296-3.927-2.296-6.334zm3.829 7.81c1.969-3.088 4.482-5.098 7.598-6.027.928 2.42 1.609 4.91 2.043 7.46-3.349 1.291-6.953.666-9.641-1.433zm11.586.43c-.438-2.353-1.08-4.653-1.92-6.897 1.876-.265 3.94-.196 6.199.196-.437 2.786-2.028 5.192-4.279 6.701z"/></svg>';
                break;
            case 'linkedin':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon_linkedin" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
                break;
            case 'youtube':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__youtube" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>';
                break;
            case 'reddit':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__reddit" viewBox="0 0 24 24"><path d="M24 11.779c0-1.459-1.192-2.645-2.657-2.645-.715 0-1.363.286-1.84.746-1.81-1.191-4.259-1.949-6.971-2.046l1.483-4.669 4.016.941-.006.058c0 1.193.975 2.163 2.174 2.163 1.198 0 2.172-.97 2.172-2.163s-.975-2.164-2.172-2.164c-.92 0-1.704.574-2.021 1.379l-4.329-1.015c-.189-.046-.381.063-.44.249l-1.654 5.207c-2.838.034-5.409.798-7.3 2.025-.474-.438-1.103-.712-1.799-.712-1.465 0-2.656 1.187-2.656 2.646 0 .97.533 1.811 1.317 2.271-.052.282-.086.567-.086.857 0 3.911 4.808 7.093 10.719 7.093s10.72-3.182 10.72-7.093c0-.274-.029-.544-.075-.81.832-.447 1.405-1.312 1.405-2.318zm-17.224 1.816c0-.868.71-1.575 1.582-1.575.872 0 1.581.707 1.581 1.575s-.709 1.574-1.581 1.574-1.582-.706-1.582-1.574zm9.061 4.669c-.797.793-2.048 1.179-3.824 1.179l-.013-.003-.013.003c-1.777 0-3.028-.386-3.824-1.179-.145-.144-.145-.379 0-.523.145-.145.381-.145.526 0 .65.647 1.729.961 3.298.961l.013.003.013-.003c1.569 0 2.648-.315 3.298-.962.145-.145.381-.144.526 0 .145.145.145.379 0 .524zm-.189-3.095c-.872 0-1.581-.706-1.581-1.574 0-.868.709-1.575 1.581-1.575s1.581.707 1.581 1.575-.709 1.574-1.581 1.574z"/></svg>';
                break;
            case 'pinterest':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__pinterest" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>';
                break;
            case 'medium':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__medium" viewBox="0 0 24 24"><path d="M2.846 6.887c.03-.295-.083-.586-.303-.784l-2.24-2.7v-.403h6.958l5.378 11.795 4.728-11.795h6.633v.403l-1.916 1.837c-.165.126-.247.333-.213.538v13.498c-.034.204.048.411.213.537l1.871 1.837v.403h-9.412v-.403l1.939-1.882c.19-.19.19-.246.19-.537v-10.91l-5.389 13.688h-.728l-6.275-13.688v9.174c-.052.385.076.774.347 1.052l2.521 3.058v.404h-7.148v-.404l2.521-3.058c.27-.279.39-.67.325-1.052v-10.608z"/></svg>';
                break;
            case 'vk':
                $html = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon__vk" viewBox="0 0 24 24"><path d="M13.162 18.994c.609 0 .858-.406.851-.915-.031-1.917.714-2.949 2.059-1.604 1.488 1.488 1.796 2.519 3.603 2.519h3.2c.808 0 1.126-.26 1.126-.668 0-.863-1.421-2.386-2.625-3.504-1.686-1.565-1.765-1.602-.313-3.486 1.801-2.339 4.157-5.336 2.073-5.336h-3.981c-.772 0-.828.435-1.103 1.083-.995 2.347-2.886 5.387-3.604 4.922-.751-.485-.407-2.406-.35-5.261.015-.754.011-1.271-1.141-1.539-.629-.145-1.241-.205-1.809-.205-2.273 0-3.841.953-2.95 1.119 1.571.293 1.42 3.692 1.054 5.16-.638 2.556-3.036-2.024-4.035-4.305-.241-.548-.315-.974-1.175-.974h-3.255c-.492 0-.787.16-.787.516 0 .602 2.96 6.72 5.786 9.77 2.756 2.975 5.48 2.708 7.376 2.708z"/></svg>';
                break;
            default:
                $html = '';
                break;
        endswitch;

        return apply_filters( 'lisfinity__load_correct_social_icon', $html );
    }
}

if ( ! function_exists( 'lisfinity_load_star_icon' ) ) {
    /**
     * Load correct star icon SVG
     * --------------------------
     *
     * @param $icon
     *
     * @return mixed|void
     */
    function lisfinity_load_star_icon_svg( $icon ) {
        $html = '';
        switch ( $icon ) :
            case 'star':
                $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg"x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><g><path class="st0" d="M50,50L50,50L50,50z"/></g><g><path class="st0" d="M50,50L50,50L50,50z"/></g><g><path d="M23.2,95.2c-0.8,0-1.5-0.2-2.2-0.7c-1.2-0.8-1.7-2.2-1.5-3.7l4.7-27.9L3.9,43.1c-1-1-1.4-2.5-0.9-3.8c0.4-1.4,1.6-2.3,3-2.6l28-4.3L46.6,6.9c0.6-1.3,1.9-2.1,3.4-2.1c1.4,0,2.7,0.8,3.4,2.1L66,32.4l28,4.2c1.4,0.2,2.6,1.2,3,2.6c0.4,1.4,0.1,2.8-0.9,3.8L75.8,62.8l4.7,27.9c0.2,1.4-0.3,2.8-1.5,3.7s-2.7,1-3.9,0.3L50,81.6L24.9,94.7C24.4,95,23.8,95.2,23.2,95.2z M10.3,41.6L30.1,61l-4.6,27.2L50,75.4l24.5,12.7l-4.6-27.3l19.8-19.4l-27.4-4.1L50,12.5L37.7,37.4L10.3,41.6z"/></g><g><path class="st0" d="M50,50L50,50L50,50z"/></g><g><path class="st0" d="M50,50L50,50L50,50z"/></g><g><path class="st0" d="M50,50L50,50L50,50z"/></g></svg>';
                break;
            case 'star-filled':
                $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M96.8,37l-30.6-4.5L52.3,4.7c-1-1.9-3.8-1.9-4.7,0L33.8,32.6L3.2,37c-2.2,0.3-3.1,3.1-1.4,4.5l22.1,21.7l-5.2,30.5c-0.3,2.1,1.8,3.8,3.9,2.6L50.1,82l27.4,14.3c1.8,1,4.2-0.6,3.8-2.6l-5.2-30.5l22.1-21.7C99.8,40,99,37.3,96.8,37z"/></svg>';
                break;
            case 'star-half':
                $html = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve"><g><polygon points="37.9,27.1 45.6,27.3 45.9,22 41.6,22 40,18.3 35.2,20.2 	"/><path d="M61.1,22.8l-6.1-0.3l-0.3,5.1l-3.5,2.7l3.2,4.3l8.3-6.4c0.8-0.5,1.3-1.6,1.3-2.4C64,24.1,62.7,22.8,61.1,22.8z"/><polygon points="43.5,44.9 48.8,43.3 47.5,39.6 51,37.2 47.8,32.9 41.4,37.5 	"/><path d="M46.2,52.9l-4-2.4L39.2,55l8.8,5.6l0.8,0.5h0.8c1.6,0,3.2-1.3,3.2-2.9L51,51.6L46.2,52.9z"/><path d="M27,47.3l-9.1,5.6l4.5-15.4l-13-9.9l16.5-0.5L32,12.2l0,0l1.6,4l5.1-1.9L35,4.7c-0.5-1.1-1.6-1.9-2.9-1.9s-2.4,0.8-2.9,1.9L27,10.3l0,0L22.5,22L3,22.8c-1.3,0-2.4,0.8-2.9,2.1c-0.3,1.3,0,2.7,1.1,3.5l15.2,11.4L11,57.4c-0.3,1.3,0,2.4,1.1,3.2c0.8,0.3,1.6,0.5,2.1,0.5s1.1-0.3,1.6-0.5l12.5-8l0,0l3.7-2.1l3.7,2.4l2.9-4.5L32,44.1L27,47.3z"/></g></svg>';
                break;
            default:
                $html = '';
                break;
        endswitch;

        return apply_filters( 'lisfinity__load_correct_star_icon', $html );
    }
}

function lisfinity_find_string( $needle, $haystack, $i = '', $word = '' ) {
    {   // $i should be "" or "i" for case insensitive
        if ( 'W' === strtoupper( $word ) ) {
            if ( preg_match( "/\b{$needle}\b/{$i}", $haystack ) ) {
                return true;
            }
        } else {
            if ( preg_match( "/{$needle}/{$i}", $haystack ) ) {
                return true;
            }
        }

        return false;
    }
}

if ( ! function_exists( 'lisfinity_hex_to_rgba' ) ) {
    /**
     * Change hex color to rgba version
     * --------------------------------
     *
     * @param $hex
     * @param $opacity
     *
     * @return array
     */
    function lisfinity_hex_to_rgba( $hex, $opacity = '' ) {
        $hex = str_replace( '#', '', $hex );
        $hex = strlen( $hex ) > 6 ? $hex : $hex . 'FF';

        $int   = hexdec( $hex );
        $red   = ( $int >> 24 ) & 255;
        $green = ( $int >> 16 ) & 255;
        $blue  = ( $int >> 8 ) & 255;
        $alpha = floatval( $int & 255 ) / 255;

        return array(
            'red'   => $red,
            'green' => $green,
            'blue'  => $blue,
            'alpha' => ! empty( $opacity ) ? $opacity : $alpha,
        );
    }
}

if ( ! function_exists( 'lisfinity_rearrange_bookmarks' ) ) {
    function lisfinity_rearrange_bookmarks() {
        $user_id   = get_current_user_id();
        $bookmarks = carbon_get_user_meta( $user_id, 'bookmarks' );

        $list = [];
        if ( ! empty( $bookmarks ) ) {
            foreach ( $bookmarks as $bookmark ) {
                $list[] = (int) $bookmark['id'];
            }
        }

        return apply_filters( 'lisfinity__rearrange_bookmarks', $list );
    }
}

function lisfinity_kses_svg( $svg ) {
    $kses_defaults = wp_kses_allowed_html( 'post' );

    $svg_args = array(
        'svg'   => array(
            'class'           => true,
            'aria-hidden'     => true,
            'aria-labelledby' => true,
            'role'            => true,
            'xmlns'           => true,
            'width'           => true,
            'height'          => true,
            'viewbox'         => true, // <= Must be lower case!
        ),
        'g'     => array( 'fill' => true ),
        'title' => array( 'title' => true ),
        'path'  => array( 'd' => true, 'fill' => true, ),
    );

    $allowed_tags = array_merge( $kses_defaults, $svg_args );

    return wp_kses( $svg, $allowed_tags );
}


if ( ! function_exists( 'lisfinity_is_wpml' ) ) {
    /**
     * Check if wpml is installed
     * --------------------------
     *
     * @return bool
     */
    function lisfinity_is_wpml() {
        if ( function_exists( 'icl_object_id' ) ) {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'lisfinity_convert_to_readable_slug' ) ) {
    /**
     * Convert chinese characters to wp readable slug so we can store it properly
     * --------------------------------------------------------------------------
     *
     * @param $strTitle
     *
     * @return string
     */
    function lisfinity_convert_to_readable_slug( $strTitle ) {
        $strRet     = '';
        $dictionary = include LISFINITY_CORE_DIR . 'includes/dictionaries/dictionary-chinese.php';


        $origStrTitle    = $strTitle; // Save the original title<-------------------------------------------
        $containsChinese = false; // Setting a flag variable, the default is false, if the title contains Chinese characters it echoes true<-------------------

        if ( get_bloginfo( 'charset' ) != "UTF-8" ) {
            $strTitle = iconv( get_bloginfo( "charset" ), "UTF-8", $strTitle );
        }

        for ( $i = 0; $i < strlen( $strTitle ); $i ++ ) {
            //Take 1 byte???
            $byte1st = ord( substr( $strTitle, $i, 1 ) );
            //If in range between 11100000 and 11101111 then it is a Chinese character in UTF-8
            if ( $byte1st >= 224 && $byte1st <= 239 ) {
                $containsChinese = true; // If the title contains Chinese characters will flag variable is set to true<-------------------------------------------

                //Grab the whole character, UTF-8 is a 3-byte Chinese character
                $fullChar = substr( $strTitle, $i, 3 );
                $i        += 2;
                //Find spelling in the dictionary; if it cannotfind the character, it will be ignored
                foreach ( $dictionary as $pinyin => $val ) {
                    if ( strpos( $val, $fullChar ) !== false ) {
                        $strRet .= $pinyin;
                        break;
                    }
                }
            } else {
                /**
                 * fix to not ignore alphanumerical characters
                 * by [vanabel](https://github.com/vanabel)
                 *
                 * @source: //github.com/senlin/so-pinyin-slugs/issues/4
                 */
                $strRet .= preg_replace( '/[^A-Za-z0-9\-]/', '$0', chr( $byte1st ) );
            }
        }

        if ( ! $containsChinese ) { // If the title does not contain Chinese characters, return the previously saved original title<-----
            $strRet = $origStrTitle;
        }

        return $strRet;
    }
}

if ( ! function_exists( 'lisfinity_is_enabled' ) ) {
    /**
     * Checks if the given option is enabled
     * -------------------------------------
     *
     * @param $option
     *
     * @return bool
     */
    function lisfinity_is_enabled( $option ) {
        if ( 'yes' === $option || '1' === $option || true === $option ) {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'lisfinity_transliterate' ) ) {
    function lisfinity_transliterate( $name ) {
        $name = str_replace( [ 'š', 'č', 'đ', 'č', 'ć', 'ž', 'ñ' ], [ 's', 'c', 'd', 'c', 'c', 'z', 'n' ], $name );
        $name = str_replace( [ 'Š', 'Č', 'Đ', 'Č', 'Ć', 'Ž', 'Ñ' ], [ 'S', 'C', 'D', 'C', 'C', 'Z', 'N' ], $name );
        $name = str_replace(
            [ 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'љ', 'м', 'н', 'њ', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'џ', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'Љ', 'М', 'Н', 'Њ', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Џ', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я' ],
            [ 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'z', 'i', 'j', 'k', 'l', 'lj', 'm', 'n', 'nj', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'c', 'dz', 's', 's', 'i', 'j', 'j', 'e', 'ju', 'ja', 'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Z', 'Z', 'I', 'J', 'K', 'L', 'Lj', 'M', 'N', 'Nj', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'C', 'Dz', 'S', 'S', 'I', 'J', 'J', 'E', 'Ju', 'Ja' ],
            $name );

        return $name;
    }
}
