<?php
/*
Plugin Name: Easy url rewrite
Plugin URI: http://wordpress.org/extend/plugins/easy-url-rewrite/
Description: Use custom made urls as easy as include them in an array! It shouldn't be hard to make custom urls! Activate the plugin, don't forget to turn on permalinks and then go to your-blog-url.com/hello_world
Version: 0.1
Author: Joel Larsson
Author URI: http://bolmaster2.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

add_filter('generate_rewrite_rules', 'eur_flush_rules');
add_action('template_redirect', 'eur_redirect');

function eur_flush_rules() {    
  // Dont do anything in admin!
  if (is_admin()) return;
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}       

function eur_redirect() {
  global $wp;
  global $eur_urls;
  
  $plugin_dir = dirname(__FILE__);

  // If the $eur_url is not set - set it as a default with a hello world url
  if (!is_array($eur_urls)) {
    $eur_urls = array(
      "hello_world" => $plugin_dir."/hello_world.php"
    );
  }
  
  // Check for matches in the array
  if (isset($eur_urls[$wp->request])) {  
    $file = $eur_urls[$wp->request];
    
    // Check if the file exists for real...
    if (is_file($file)) {
      // Set HTTP response
      header('HTTP/1.1 200 OK');
      // Include the file
      require $file;
      exit;
    }
  }
}