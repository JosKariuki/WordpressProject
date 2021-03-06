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
 * Fired during the plugin activation
 *
 * Gets the template names from the template directory and loads it into the database.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/includes
 */
class Activator {
    /**
     * Add the template names to the database from the filesystem.
     *
     * @since 1.0.0
     */
    private function load_templates() {
        $plugin_helper = new Plugin_Helper();
        $plugin_helper->refresh_template_list();
    }

    /**
     * The code to run on activation
     *
     * @since 1.4.3
     */
    function activate() {
        $this->load_templates();
    }
}

?>