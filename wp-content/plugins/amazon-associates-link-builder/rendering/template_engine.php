<?php

/*
Copyright 2016-2018 Amazon.com, Inc. or its affiliates. All Rights Reserved.

Licensed under the GNU General Public License as published by the Free Software Foundation,
Version 2.0 (the "License"). You may not use this file except in compliance with the License.
A copy of the License is located in the "license" file accompanying this file.

This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
either express or implied. See the License for the specific language governing permissions
and limitations under the License.
*/
namespace AmazonAssociatesLinkBuilder\rendering;

use AmazonAssociatesLinkBuilder\configuration\Config_Helper;
use AmazonAssociatesLinkBuilder\helper\Xml_Helper;
use AmazonAssociatesLinkBuilder\cache\Cache_Loader;
use AmazonAssociatesLinkBuilder\cache\Cache_Template_Loader;
use AmazonAssociatesLinkBuilder\includes\Remote_Loader;
use AmazonAssociatesLinkBuilder\rendering\Xml_Manipulator;
use AmazonAssociatesLinkBuilder\rendering\Impression_Generator;
use AmazonAssociatesLinkBuilder\constants\Db_Constants;
use AmazonAssociatesLinkBuilder\constants\Plugin_Constants;
use AmazonAssociatesLinkBuilder\exceptions\Invalid_Marketplace_Exception;
use AmazonAssociatesLinkBuilder\helper\Plugin_Helper;

/**
 * Template engine to render the product in the particular display unit.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/rendering
 */
class Template_Engine {
    protected $xml_loader;
    protected $cache_template_loader;
    protected $mustache;
    protected $xml_helper;
    protected $helper;
    protected $impression_generator;
    private $config_helper;

    public function __construct() {
        $this->xml_loader = new Cache_Loader( new Remote_Loader() );
        $this->helper = new Plugin_Helper();
        $this->cache_template_loader = new Cache_Template_Loader();
        $this->mustache = new \Mustache_Engine( array( 'loader' => new \Mustache_Loader_FilesystemLoader( AALB_TEMPLATE_DIR ) ) );
        $this->mustache_custom = new \Mustache_Engine( array( 'loader' => new \Mustache_Loader_FilesystemLoader( $this->helper->get_template_upload_directory() ) ) );
        $this->config_helper = new Config_Helper();
        $this->xml_manipulator = new Xml_Manipulator( new Xml_Helper( $this->config_helper ) );
        $this->impression_generator = new Impression_Generator( $this->config_helper );
    }

    /**
     * Render the products into the display unit.
     * If the display unit exists in the cache return the display unit.
     * Else get the xml and render the product.
     *
     * @since 1.0.0
     *
     * @param string $display_key  Key of the display unit.
     * @param string $products_key Key of the combined products.
     * @param string $template     Template to render the display unit.
     * @param string $url          Url to get the product from if not present in cache.
     * @param string $marketplace  Marketplace to which the product belongs.
     * @param string $link_code    Link Code to be entered in URLS for attribution purposes.
     * @param string $store_id     Store id of associate
     * @param string $asin_group   Group of different asins speated by ","
     *
     * @return string  HTML of the disply unit.
     */
    public function render( $display_key, $products_key, $template, $url, $marketplace, $link_code, $store_id, $asin_group ) {
        if ( false === ( $display_unit = $this->cache_template_loader->get_display_unit( $display_key ) ) ) {
            $products = $this->get_products( $products_key, $url, $link_code );
            $products = $this->unescape_numeric_character_references( $products );

            $custom_items = $this->xml_manipulator->get_customized_items_object( $products, $marketplace );
            $display_unit = $this->render_xml( $custom_items, $template );
            $display_unit = $this->add_html_for_impression_tracking( $display_unit, $marketplace, $link_code, $store_id, $asin_group );

            $this->cache_template_loader->save_display_unit( $display_key, $display_unit );
        }

        return $display_unit;
    }

    /**
     * Single Escape Numeric Character References(NCR) using regular expression replacement
     *
     * @since 1.7.0
     *
     * @param string $products Deserialized XML string with NCRs double escaped
     *
     * @return string Deserialized XML string with NCRS single escaped
     */
    private function unescape_numeric_character_references( $products ) {
        //Single Escape NCR represented as hex number
        $products = preg_replace( "/&amp;(#x[a-fA-F0-9]{4,6};)/", "&$1", $products );

        //Single Escape other special characters escaped by Product Advertising API like Σ(&#931;),Θ(&#920;)
        $products = preg_replace( "/&amp;(#[0-9]{1,7};)/", "&$1", $products );

        return $products;
    }


    /**
     * Adds pixel image HTML element to the display unit
     *
     * @since 1.6.0
     *
     * @param string $display_unit HTML of the display unit.
     * @param String $marketplace  marketplace
     * @param String $store_id     Store id of associate
     * @param String $link_code    Link code used for tracking
     * @param String $asin_group   Group of different asins speated by ","
     *
     * @return string $display_unit HTML of the display unit along with pixel image
     */
    private function add_html_for_impression_tracking( $display_unit, $marketplace, $link_code, $store_id, $asin_group ) {
        try {
            $impression = $this->impression_generator->get_impression( $marketplace, $link_code, $store_id, $asin_group );
            $display_unit = $impression . $display_unit;
        } catch ( Invalid_Marketplace_Exception $e ) {
            //Do Nothing as it is because of a new marketplace added and we are currently not racling impression for this new marketplace.
        } catch ( \InvalidArgumentException $e ) {
            error_log( "Aalb_Template_Engine::add_html_for_impression_tracking " . $e->getMessage() );
        } catch ( \Exception $e ) {
            error_log( "Aalb_Template_Engine::add_html_for_impression_tracking " . $e->getMessage() );
        }

        return $display_unit;
    }

    /**
     * Get the products information.
     *
     * @since 1.0.0
     *
     * @param string $key       Unique identification of the product.
     * @param string $url       Signed URL for the PAAPI request.
     * @param string $link_code Link Code to be entered in URLS for attribution purposes.
     *
     * @return string Xml response from PAAPI.
     */
    private function get_products( $key, $url, $link_code ) {
        return $this->xml_loader->load( $key, $url, $link_code );
    }

    /**
     * Render the xml with a specific template.
     *
     * @since 1.0.0
     *
     * @param array $items     Each key consists of an item information object.
     * @param string $template Template in which the content has to be rendered.
     *
     * @return string HTML of the display unit.
     */
    private function render_xml( $items, $template ) {
        $aalb_default_templates = explode( ",", Plugin_Constants::AMAZON_TEMPLATE_NAMES );
        try {
            if ( in_array( $template, $aalb_default_templates ) ) {
                $template = $this->mustache->loadTemplate( $template );
            } else {
                $template = $this->mustache_custom->loadTemplate( $template );
            }
        } catch ( \Mustache_Exception_UnknownTemplateException $e ) {
            $template = $this->mustache->loadTemplate( get_option( Db_Constants::DEFAULT_TEMPLATE, Db_Constants::DEFAULT_TEMPLATE_NAME ) );
        }

        return $template->render( array( 'StoreId' => get_option( Db_Constants::DEFAULT_STORE_ID ), 'Items' => $items ) );
    }
}

?>
