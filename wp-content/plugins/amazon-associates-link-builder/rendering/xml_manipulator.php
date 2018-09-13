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

use AmazonAssociatesLinkBuilder\constants\Paapi_Constants;
use AmazonAssociatesLinkBuilder\helper\Xml_Helper;
use AmazonAssociatesLinkBuilder\constants\XML_Constants;

/**
 * Helper class for customizations to the xml response
 *
 * @since      1.8.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/lib/php
 */
class Xml_Manipulator {
    private $xml_helper;

    public function __construct( Xml_Helper $xml_helper ) {
        $this->xml_helper = $xml_helper;
    }

    /**
     * Returns the Customized Items SimpleXML Object which contains Aalb Node and
     * some other customized values as per business logic.
     *
     * @since 1.8.0
     *
     * @param string $products_xml Well-formed XML string of Products
     * @param string $marketplace
     *
     * @return \SimpleXMLElement Php xml object.
     */
    public function get_customized_items_object( $products_xml, $marketplace ) {
        $simple_xml_object = $this->parse( $products_xml );
        $this->validate( $simple_xml_object );
        $custom_items = $this->add_custom_nodes( $simple_xml_object->Items, $marketplace );

        return $custom_items;
    }

    /**
     * Convert the well-formed xml string into a\SimpleXMLElement object.
     *
     * @since 1.0.0
     *
     * @param string $xml_string Well-formed XML string
     *
     * @return \SimpleXMLElement Php xml object.
     */
    private function parse( $xml_string ) {
        libxml_use_internal_errors( true );

        return simplexml_load_string( $xml_string );
    }

    /**
     * Checks if SIMPLE XML element object is generated successfully and has no error code from PA-API
     *
     * @since 1.8.0
     *
     * @param \SimpleXMLElement $xml Well-formed XML string
     *
     * @throws \Exception if object is not generated successfully or has an error code from PA-API
     */
    private function validate( $xml ) {
        if ( $xml === false ) {
            //Don't translate as this is also dumped in error logs and will facilitate AALB team to debug
            throw new \Exception( 'Xml_Manipulator::validate::Failed Loading XML' );
        } else if ( ! $this->should_render_xml( $xml ) ) {
            throw new \Exception( 'Xml_Manipulator::validate::' . $xml->Items->Request->Errors->Error->Code );
        }
    }

    /**
     * Whether to allow xml to be rendered
     *
     * @since 1.4.7
     *
     * @param \SimpleXMLElement $xml Well-formed XML string
     *
     * @return boolean should_render_xml
     */
    private function should_render_xml( $xml ) {
        return ! isset( $xml->Items->Request->Errors->Error ) || $this->is_error_acceptable( $xml );
    }

    /**
     * Whether the error is acceptable. For now, It is acceptable for Invalid Parameter value with at least one item set.
     * This handles the case when an expired ASIN is present in a list of products and thus unblocks the ad rendering.
     *
     * @since 1.8.0
     *
     * @param \SimpleXMLElement $xml Well-formed XML string
     *
     * @return boolean is_error_acceptable
     */
    private function is_error_acceptable( $xml ) {
        return $xml->Items->Request->Errors->Error->Code == Paapi_Constants::INVALID_PARAMETER_VALUE_ERROR && isset( $xml->Items->Item );
    }

    /**
     * Add custom nodes to xml response
     *
     * @since 1.0.0
     *
     * @param \SimpleXMLElement $items Well-formed XML string
     *
     * @return \SimpleXMLElement $items XML String with custom nodes added
     */
    private function add_custom_nodes( $items, $marketplace ) {
        $common_marketplace_node_name = 'Marketplace' . $marketplace;
        $items->ID = "[[UNIQUE_ID]]";
        $basic_labels = $this->xml_helper->get_basic_labels( $marketplace );
        $items->PriceLabel = $basic_labels[XML_Constants::PRICE_LABEL];
        $items->StrikePriceLabel = $basic_labels[XML_Constants::STRIKE_PRICE_LABEL];
        $items->ProductsFromAmazonLabel = $basic_labels[XML_Constants::PRODUCTS_FROM_AMAZON_LABEL];
        $aalb_header_node = $items->addChild( 'AalbHeader' );
        $aalb_header_node->$common_marketplace_node_name = 'true';

        foreach ( $items->Item as $item ) {
            $this->decorate_item( $item, $marketplace, $common_marketplace_node_name );
        }

        return $items;
    }

    /**
     * Add the Information for the Item(which is calculated based on business logic) to the Aalb node.
     *
     * @since 1.8.0
     *
     * @param \SimpleXMLElement $item
     * @param String $marketplace
     *
     */
    private function decorate_item( $item, $marketplace, $common_marketplace_node_name ) {
        $basic_info = $this->xml_helper->get_basic_info( $item );
        $image_urls = $this->xml_helper->get_image_urls( $item );
        $price_related_info = $this->xml_helper->get_price_related_information( $item, $marketplace );
        $savings_info = $price_related_info[XML_Constants::SAVINGS_INFO];
        $min_price_info = $price_related_info[XML_Constants::MIN_PRICE_INFO];

        $nodes = array(
            XML_Constants::ASIN                => $basic_info[XML_Constants::ASIN],
            XML_Constants::TITLE               => $basic_info[XML_Constants::TITLE],
            XML_Constants::DETAIL_PAGE_URL     => $basic_info[XML_Constants::DETAIL_PAGE_URL],
            XML_Constants::LARGE_IMAGE_URL     => $image_urls[XML_Constants::LARGE_IMAGE_URL],
            XML_Constants::MEDIUM_IMAGE_URL    => $image_urls[XML_Constants::MEDIUM_IMAGE_URL],
            XML_Constants::SMALL_IMAGE_URL     => $image_urls[XML_Constants::SMALL_IMAGE_URL],
            XML_Constants::BY                  => $this->xml_helper->get_by_information( $item ),
            XML_Constants::PRIME               => $this->xml_helper->get_prime_eligibility( $item ),
            XML_Constants::MERCHANT            => $this->xml_helper->get_merchant_name( $item ),
            XML_Constants::SAVING              => $savings_info[XML_Constants::SAVING],
            XML_Constants::SAVING_VALUE        => $savings_info[XML_Constants::SAVING_VALUE],
            XML_Constants::SAVING_PERCENT      => $savings_info[XML_Constants::SAVING_PERCENT],
            XML_Constants::MINIMUM_PRICE       => $min_price_info[XML_Constants::MINIMUM_PRICE],
            XML_Constants::MINIMUM_PRICE_VALUE => $min_price_info[XML_Constants::MINIMUM_PRICE_VALUE],
            XML_Constants::CURRENT_PRICE       => $price_related_info[XML_Constants::CURRENT_PRICE],
            XML_Constants::CURRENT_PRICE_VALUE => $price_related_info[XML_Constants::CURRENT_PRICE_VALUE],
            XML_Constants::STRIKE_PRICE        => $price_related_info[XML_Constants::STRIKE_PRICE],
            XML_Constants::STRIKE_PRICE_VALUE  => $price_related_info[XML_Constants::STRIKE_PRICE_VALUE],
            XML_Constants::IN_STOCK            => $price_related_info[XML_Constants::IN_STOCK],
            $common_marketplace_node_name      => 'true'
        );

        $aalb_node = $item->addChild( 'aalb' );
        foreach ( $nodes as $key => $value ) {
            if ( ! empty( $value ) ) {
                $aalb_node->$key = $value;
            }
        }

        //Below is done as earlier we were maintaining current price value node even if value is null
        $aalb_node->CurrentPriceValue = $price_related_info[XML_Constants::CURRENT_PRICE_VALUE];
    }
}