<?php
namespace ConcreteIron\Includes;

class RapidTitleTags {
    const SEPARATOR = '|';
    const SITE_TITLE = 'ConcreteIron Classified Ads';

    public function __construct() {
        $this->boot();
    }

    /**
     * Boot up the class
     *
     * @return void
     */
    public function boot() {
        add_action('wpseo_title', [$this, 'lisfinity_title_tags']);
        add_action('wpseo_metadesc', [$this, 'lisfinity_meta_desc']);
    }

    /**
     * Lisfinity Title Tags
     *
     * @param $title
     * @return string
     */
    public function lisfinity_title_tags($title) {
        if (strpos($_SERVER['REQUEST_URI'], '/ad-category/') !== false) {
            $title = 'Buy or Sell Concrete Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-batching-equipment') !== false) {
            $title = 'Buy or Sell Concrete Batching Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-cutting-and-demolition-equipment') !== false) {
            $title = 'Buy or Sell Concrete Cutting and Demolition Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-cutting-and-demolition-equipment') !== false) {
            $title = 'Buy or Sell Concrete Cutting and Demolition Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-placing-and-finishing-equipment') !== false) {
            $title = 'Buy or Sell Concrete Placing and Finishing Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-pumping-equipment') !== false) {
            $title = 'Buy or Sell Concrete Pumping Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-pumping-equipment') !== false) {
            $title = 'Buy or Sell Concrete Pumping Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'general-construction-equipment') !== false) {
            $title = 'Buy or Sell General Construction Equipment';
        }

        if(strpos($_SERVER['QUERY_STRING'], 'other-concrete-equipment') !== false) {
            $title = 'Buy or Sell Other Concrete Equipment';
        }

        $subcategory_lvl_2_terms = get_terms([
            'taxonomy'  => 'concrete-equipment-subcategory',
            'hide_empty' => false,
        ]);

        if(!empty($subcategory_lvl_2_terms) && !is_wp_error($subcategory_lvl_2_terms)) {
            foreach ($subcategory_lvl_2_terms as $term) {
                if(strpos($_SERVER['QUERY_STRING'], $term->slug) !== false) {
                    if ($term->parent != 0) {
                        $parent_term = get_term($term->parent, 'concrete-equipment-type');
                        if (!is_wp_error($parent_term) && !empty($parent_term)) {
                            $title = $term->name . " For Sale - " . $parent_term->name;
                        } else {
                            $title = $term->name . " For Sale";
                        }
                    } else {
                        $title = $term->name . " For Sale";
                    }
                }
            }
        }

        $separator = ' ' . trim(self::SEPARATOR) . ' ';
        return $title . $separator . self::SITE_TITLE;
    }

    /**
     * Lisfinity Meta Descriptions
     *
     * @return string
     */
    public function lisfinity_meta_desc($description) {
        $meta_dec_alt ='';

        if (strpos($_SERVER['REQUEST_URI'], '/ad-category/') !== false) {
            $description = "Your project requires top-quality concrete equipment, and we've got it all. From mixing to demolition, browse our full range to find the tools that will keep your site running smoothly.";
            $meta_dec_alt = "For every concrete task, there's a tool to match in our extensive equipment category. Choose from a variety of mixers, pumps, and finishing tools to ensure your construction success.";
        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-batching-equipment') !== false) {
            $description = "Enhance your concrete production with top-notch batching equipment. Find your ideal mixers and plants for precise mixture formulations. Get the best for your build, shop now!";
            $meta_dec_alt = "Step up your construction game with our concrete batching equipment, featuring the latest cement mixers and batch plants ideal for any project scale. Secure your perfect match today!";

        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-cutting-and-demolition-equipment') !== false) {
            $description = "Maximize efficiency with advanced concrete pumping equipment. Tailor your search for trailer to boom pumps to meet your project's needs. Shop for reliable pumping solutions now!";
            $meta_dec_alt = "Streamline your construction with high-performance concrete pumping equipment. Our selection includes skid-mounted units for precise pumping. Find and purchase your ideal pump today!";

        }

        if(strpos($_SERVER['QUERY_STRING'], 'concrete-pumping-equipment') !== false) {
            $description = "Maximize efficiency with advanced concrete pumping equipment. Tailor your search for trailer to boom pumps to meet your project's needs. Shop for reliable pumping solutions now!";
            $meta_dec_alt = "Streamline your construction with high-performance concrete pumping equipment. Our selection includes skid-mounted units for precise pumping. Find and purchase your ideal pump today!";

        }

        if(strpos($_SERVER['QUERY_STRING'], 'general-construction-equipment') !== false) {
            $description = "Equip your project with our comprehensive range of general construction equipment. From power tools to heavy machinery, find all your essential building tools in one place!";
            $meta_dec_alt = "Upgrade your construction site with our extensive selection of general construction equipment. Quality, reliability, and durability meet here—equip your project with the best!";

        }

        if(strpos($_SERVER['QUERY_STRING'], 'other-concrete-equipment') !== false) {
            $description = "Explore our diverse range of concrete equipment for those niche tasks. Find the perfect addition to your toolkit, from vibrators to repair kits, to ensure a job well done.";
            $meta_dec_alt = "Complete your concrete equipment collection with our specialized selections. Dive into a variety of quality tools designed for specific concrete applications.";
        }

        $random_number = rand(1, 2);
        $meta_description_to_use = ($random_number === 1) ? $description : $meta_dec_alt;

        return $meta_description_to_use ?? $description;
    }
}