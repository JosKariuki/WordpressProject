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
namespace AmazonAssociatesLinkBuilder\cache;

/**
 * Cache Loader for rendered templates.
 *
 * Loads ands saves the display unit in the cache.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/includes
 */
class Cache_Template_Loader {
    /**
     * Get the html of the display unit from the cache
     *
     * @since 1.0.0
     *
     * @param string $key Unique Key for the display unit in cache.
     *
     * @return string  HTML of the display unit.
     */
    public function get_display_unit( $key ) {
        return get_transient( $key );
    }

    /**
     * Get the product information.
     *
     * @since 1.0.0
     *
     * @param string $key          Unique identification of the product.
     * @param string $display_unit HTML of the display unit to save in the cache.
     */
    public function save_display_unit( $key, $display_unit ) {
        set_transient( $key, $display_unit, AALB_CACHE_FOR_ASIN_ADUNIT_TTL );
    }

}

?>
