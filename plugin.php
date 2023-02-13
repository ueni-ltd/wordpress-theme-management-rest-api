<?php
/*
Plugin Name: Theme Management WP REST API
Plugin URI: https://github.com:ueni-ltd/wordpress-theme-management-rest-api.git
Description: This plugin allows you to manage the theme on your site using WordPress REST API.
Author: ueni
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

    include_once( ABSPATH . 'wp-admin/includes/file.php' );

    register_rest_route( 'ueni/v1', '/theme-management/', array(
        'methods' => 'POST',
        'callback' => 'theme_management_callback',
        'permission_callback' => function () {
            return current_user_can( 'install_themes' );
        },
    ) );
}

add_action( 'rest_api_init', 'theme_management_rest_api' );

function theme_management_callback( $request ) {

    include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
    include_once( ABSPATH . 'wp-admin/includes/theme.php' );

    $theme_slug = $request->get_param( 'theme_slug' );

    if ( empty( $theme_slug ) ) {
        return new WP_Error( 'missing_theme_slug', 'The theme slug is required to install the theme', array( 'status' => 400 ) );
    }

    // Get the WordPress themes directory path
    $themes_dir = trailingslashit( WP_CONTENT_DIR ) . 'themes/';

    // Connect to the filesystem
    $creds = request_filesystem_credentials( site_url() );
    if ( ! WP_Filesystem( $creds ) ) {
        return new WP_Error( 'filesystem_error', 'Unable to connect to the filesystem', array( 'status' => 500 ) );
    }

    // Download the theme from the WordPress repository
    $temp_file = download_url( 'https://downloads.wordpress.org/theme/' . $theme_slug . '.zip' );
    if ( is_wp_error( $temp_file ) ) {
        return new WP_Error( 'download_error', 'Unable to download the theme archive', array( 'status' => 500 ) );
    }

    // Unzip the theme to the themes directory
    $unzipped = unzip_file( $temp_file, $themes_dir );
    if ( is_wp_error( $unzipped ) ) {
        return new WP_Error( 'unzip_error', 'Unable to unzip the theme archive', array( 'status' => 500 ) );
    }

    // Return the theme information
    return array(
        'message' => 'Theme installed successfully',
        'theme'   => wp_get_theme( $theme_slug ),
    );
}







