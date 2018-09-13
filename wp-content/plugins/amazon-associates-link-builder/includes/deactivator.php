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
namespace AmazonAssociatesLinkBuilder\includes;

use AmazonAssociatesLinkBuilder\helper\Plugin_Helper;

/**
 * Fired during the plugin deactivation
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/includes
 *
 * CAUTION: Any function present here should contain code that is compatible with at least PHP 5.3(even lower if possible) so
 * that anyone not meeting compatibility requirements for min php versions gets deactivated successfully.
 */
class Deactivator {
    private $plugin_helper;

    public function __construct() {
        $this->plugin_helper = new Plugin_Helper();
    }

    /**
     * Remove the cache stored in the database.
     *
     * @since 1.0.0
     */
    private function remove_cache() {
        $this->plugin_helper->clear_cache_for_substring( '' );
    }

    /**
     * The code to run on deactivation
     *
     * @since 1.8.0
     */
    public function deactivate() {
        $this->remove_cache();
    }
}

?>
