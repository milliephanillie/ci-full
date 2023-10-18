<?php
namespace ConcreteIron\Includes;

class RapidTitleTags {
    const SEPARATOR = '|';
    const SITE_TITLE = 'ConcreteIron Classified Ads';

    public function __construct() {
        $this->boot();
    }

    public function boot() {
        add_action('wpseo_title', [$this, 'lisfinity_title_tags']);
    }


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

        $separator = ' ' . trim(self::SEPARATOR) . ' ';
        return $title . $separator . self::SITE_TITLE;
    }
}