<?php
/*
Plugin Name: Theme Management WP REST API
Plugin URI: https://github.com:ueni-ltd/wordpress-theme-management-rest-api.git
Description: This plugin allows you to manage the theme on your site using WordPress REST API.
Author: ueniteam
Version: 0.1.0
Author URI: https://ueni.com

License:
 Released under the GPL license
  http://www.gnu.org/copyleft/gpl.html

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function theme_management_rest_api() {

    register_rest_route( '/v1', '/theme-management', array(
        'methods' => 'POST',
        'callback' => 'theme_management_callback',
        'permission_callback' => function () {
            return current_user_can( 'install_themes' );
        },
    ) );
}

add_action( 'rest_api_init', 'theme_management_rest_api' );

function theme_management_callback( $request ) {

    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/class-theme-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/misc.php';

    $theme_slug = $request->get_param( 'theme_slug' );
    $url = 'https://downloads.wordpress.org/theme/' . $theme_slug . '.zip';

    // Instantiate a new Theme_Upgrader object.
    $upgrader = new Theme_Upgrader(new WP_Upgrader_Skin());

    // Attempt to install the theme.
    $result = $upgrader->install($url);

}
