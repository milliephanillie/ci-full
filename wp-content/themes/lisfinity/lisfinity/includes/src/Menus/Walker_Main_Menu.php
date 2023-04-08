<?php


namespace Lisfinity\Menus;

use Walker_Nav_Menu;

if ( ! class_exists( 'Walker_Main_Menu' ) ) {
	class Walker_Main_Menu extends Walker_Nav_Menu {

		/**
		 * Starts the list before the elements are added.
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param array $args An array of wp_nav_menu() arguments.
		 *
		 * @see Walker::start_lvl()
		 *
		 * @since 3.0.0
		 *
		 */
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat( "\t", $depth );
            $output .= "\n$indent<div class=\"megamenu sub-menu relative hidden lg:absolute\" aria-labelledby=\"subMenu\"><div class=\"megamenu-container\"><ul class=\"mega-submenu\">\n";
        }

		/**
		 * Starts the element output.
		 *
		 * @param string $output Used to append additional content (passed by reference).
		 * @param WP_Post $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $id Current item ID.
		 *
		 * @see Walker::start_el()
		 *
		 * @since 3.0.0
		 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 *
		 */
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
            $site_url = trailingslashit(home_url());

            if ( $depth == 1 ) {
                $output .= "\n$indent 
<div class='megamenu-cat' data-depth='.$depth.'>
                    <div class='submenucol'>
                        <h4>Concrete Pumping Equipment</h4>
                        <div class='cat-cols'>
                            <ul class='megamenu-cats'>
                                <li><a href='". $site_url ."search/?category-type=concrete-pumping-equipment&tax%5Bconcrete-pumping-equipment-type%5D=boom-pumps'>Boom Pumps</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-pumping-equipment&tax%5Bconcrete-pumping-equipment-type%5D=concrete-city-pumps'>Concrete City Pumps</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-pumping-equipment&tax%5Bconcrete-pumping-equipment-type%5D=concrete-diversion-valves'>Concrete Diversion Valves</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-pumping-equipment&tax%5Bconcrete-pumping-equipment-type%5D=concrete-trailer-line-pumps'>Concrete Trailer Line Pumps</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-pumping-equipment&tax%5Bconcrete-pumping-equipment-type%5D=concrete-grout-pumps'>Grout Pumps</a></li>                 
                            </ul>
                        </div>
                    </div>
                </div>
<div class='megamenu-cat' data-depth='.$depth.'>
                    <div class='submenucol'>
                        <h4>Concrete Batching Equipment</h4>
                        <div class='cat-cols'>
                            <ul class='megamenu-cats'>
                                <li><a href='". $site_url ."search/?category-type=concrete-batching-equipment&tax%5Bconcrete-batching-type%5D=cellular-foam-plants'>Cellular Foam Plants</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-batching-equipment&tax%5Bconcrete-batching-type%5D=cement-blending-equipment'>Cement Blending Equipment</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-batching-equipment&tax%5Bconcrete-batching-type%5D=cement-transport-storage'>Cement Transport Storage</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-batching-equipment&tax%5Bconcrete-batching-type%5D=concrete-batch-plants'>Concrete Batch Plants</a></li> 
                                <li><a href='". $site_url ."search/?category-type=concrete-batching-equipment&tax%5Bconcrete-batching-type%5D=concrete-ready-mix-trucks'>Concrete Ready Mix Trucks</a></li>               
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class='megamenu-cat' data-depth='.$depth.'>
                    <div class='submenucol'>
                        <h4>Concrete Cutting & Demolition Equipment</h4>
                        <div class='cat-cols'>
                            <ul class='megamenu-cats'>
                                <li><a href='". $site_url ."search/?category-type=concrete-demolition-equipment&tax%5Bdemolition-equipment-type%5D=concrete-breakers'>Concrete Breakers</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-demolition-equipment&tax%5Bdemolition-equipment-type%5D=concrete-drills-core-drills'>Concrete Drills Core Drills</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-demolition-equipment&tax%5Bdemolition-equipment-type%5D=concrete-saws'>Concrete Saws</a></li>
                                <li><a href='". $site_url ."search/?category-type=concrete-demolition-equipment&tax%5Bdemolition-equipment-type%5D=hydro-demolition'>Hyrdo Demolition</a></li>                            
                                <li><a href='". $site_url ."search/?category-type=concrete-demolition-equipment&tax%5Bdemolition-equipment-type%5D=jack-hammers'>Jack Hammers</a></li>                            
                              </ul>
                        </div>
                    </div>
                </div>
                <div class='megamenu-cat' data-depth='.$depth.'>
                    <div class='submenucol'>
                        <h4>Concrete Placing & Finishing Equipment</h4>
                        <div class='cat-cols'>
                            <ul class='megamenu-cats'>
                                <li><a href='". $site_url ."search/?category-type=fininishing-and-placing&tax%5Bfininishing-and-placing-types%5D=3d-profiling-systems'>3D Profiling Systems</a></li>
                                <li><a href='". $site_url ."search/?category-type=fininishing-and-placing&tax%5Bfininishing-and-placing-types%5D=belt-trucks-or-telebelts'>Belt Trucks or Telebelts</a></li>
                                <li><a href='". $site_url ."search/?category-type=fininishing-and-placing&tax%5Bfininishing-and-placing-types%5D=concrete-buckets'>Concrete Buckets</a></li>   
                                <li><a href='". $site_url ."search/?category-type=fininishing-and-placing&tax%5Bfininishing-and-placing-types%5D=concrete-buggies'>Concrete Buggies</a></li>
                                <li><a href='". $site_url ."search/?category-type=fininishing-and-placing&tax%5Bfininishing-and-placing-types%5D=concrete-vibrators'>Concrete Vibrators</a></li>                                 
                            </ul>
                        </div>
                    </div>
                </div>
                <div class='megamenu-cat' data-depth='.$depth.'>
                    <div class='submenucol'>
                        <h4>General Equipment</h4>
                        <div class='cat-cols'>
                            <ul class='megamenu-cats'>
                                <li><a href='". $site_url ."search/?category-type=general-equipment&tax%5Bgeneral-equipment-type%5D=air-compressors'>Air Compressors</a></li>
                                <li><a href='". $site_url ."search/?category-type=general-equipment&tax%5Bgeneral-equipment-type%5D=backhoes'>Backhoes</a></li>
                                <li><a href='". $site_url ."search/?category-type=general-equipment&tax%5Bgeneral-equipment-type%5D=compact-track-loaders'>Compact Track Loaders</a></li>                           
                                <li><a href='". $site_url ."search/?category-type=general-equipment&tax%5Bgeneral-equipment-type%5D=concrete-heaters'>Concrete Heaters</a></li>                           
                                <li><a href='". $site_url ."search/?category-type=general-equipment&tax%5Bgeneral-equipment-type%5D=concrete-reclaimers'>Concrete Reclaimers</a></li>                                                  
                              </ul>
                          </div>
                    </div>
                </div>
                <div class='megamenu-cat' data-depth='.$depth.'>
                    <div class='submenucol'>
                        <h4>Other Concrete Equipment</h4>
                        <ul class='megamenu-cats'>
                            <li><a href='". $site_url ."search/?category-type=other-concrete-equipment&tax%5Bother-concrete-equipment-type%5D=concrete-engraving'>Concrete Engraving</a></li>
                            <li><a href='". $site_url ."search/?category-type=other-concrete-equipment&tax%5Bother-concrete-equipment-type%5D=concrete-paving-forms'>Concrete Paving Forms</a></li>
                            <li><a href='". $site_url ."search/?category-type=other-concrete-equipment&tax%5Bother-concrete-equipment-type%5D=concrete-polishing-and-grinding'>Concrete Polishing and Grinding</a></li>                           
                            <li><a href='". $site_url ."search/?category-type=other-concrete-equipment&tax%5Bother-concrete-equipment-type%5D=concrete-stamp-tools'>Concrete Stamp Tools</a></li>                           
                            <li><a href='". $site_url ."search/?category-type=other-concrete-equipment&tax%5Bother-concrete-equipment-type%5D=concrete-wall-forms'>Concrete Wall Forms</a></li>                                                
                          </ul>
                    </div>
                </div>
                ";
            }

            if( $depth == 0 ) {
                $class_names = $value = '';

                $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
                $classes[] = 'relative menu-item-' . $item->ID . ' px-16';
                $svg       = '';
                if ( $this->has_children ) {
                    $classes[] = 'has-dropdown group';
                    $svg       = '<svg version="1.1" class="ml-4 w-12 h-12 fill-black" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve"><g><path d="M32,48.1c-1.3,0-2.4-0.5-3.5-1.3L0.8,20.7c-1.1-1.1-1.1-2.7,0-3.7c1.1-1.1,2.7-1.1,3.7,0L32,42.8l27.5-26.1c1.1-1.1,2.7-1.1,3.7,0c1.1,1.1,1.1,2.7,0,3.7L35.5,46.5C34.4,47.9,33.3,48.1,32,48.1z"/></g></svg>';
                }
                $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
                $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

                $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
                $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

                $output .= $indent . '<li' . $id . $value . $class_names . '>';

                $atts           = array();
                $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
                $atts['target'] = ! empty( $item->target ) ? $item->target : '';
                $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
                $atts['href']   = ! empty( $item->url ) ? $item->url : '';

                $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

                $attributes = '';
                foreach ( $atts as $attr => $value ) {
                    if ( ! empty( $value ) ) {
                        $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                }

                $item_output = '';
                $item_label  = get_post_meta( $item->ID, 'menu-item-menu-label', true );
                $item_label  = isset( $item_label ) ? $item_label : '';
                if ( ! empty( $item_label ) ) {
                    $item_output .= '<span class="menu-label">' . esc_html( $item_label ) . '</span>';
                }
                $a_class     = $this->has_children ? 'has-sub' : '';
                $item_output .= '<a class="flex items-center text-lg font-semibold text-white ' . $a_class . ' " ' . $attributes . '>';
                $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
                if ( $this->has_children ) {
                    $item_output .= $svg;
                }
                $item_output .= '</a>';
                $output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }

		}


		/**
		 * Ends the element output, if needed.
		 *
		 * @param string $output Used to append additional content (passed by reference).
		 * @param WP_Post $item Page data object. Not used.
		 * @param int $depth Depth of page. Not Used.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::end_el()
		 *
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
            if ( $depth == 1 ) {
                $output .= "";
            }

            if ( $depth == 0 ) {
                $output .= "</li>{$n}";
            }
		}

	}
}
