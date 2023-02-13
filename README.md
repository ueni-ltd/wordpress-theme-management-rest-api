# Theme Management WP REST API
Contributors: ueniteam  
Tags: REST API, Themes  
Requires at least: 5.0  
Tested up to: 6.1.1  
Stable tag: 0.1.1  
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

This plugin allows you to manage the theme on your site using WordPress REST API.

## Description

Enabling this plugin allows you to install theme on your site using WordPress REST API.


## Installation

Upload the entire `wordpress-theme-management-rest-api` folder to the `/wp-content/plugins/` directory.
OR
Activate the plugin through the 'Plugins' menu in WordPress.

## Install Theme Example

```shell
curl -d "params={'theme_slug':'astra'}" "https://YOURWPHOST.DOMAIN/?rest_route=/theme-management/v1/"
```

## Changelog


Initial release.
