<?php
/*
Plugin Name: Adrecord Affiliate
Plugin URI: https://wordpress.org/plugins/adrecord-affiliate/
Description: Adrecords WordPress plugin for affiliates. Easily enable and make use of clean links and other features right in your WordPress site.
Version: 1.0.0
Author: Adrecord
Author URI: https://www.adrecord.com
Text Domain: adrecord
Domain Path: /languages
*/

defined('ABSPATH') or die('No script kiddies please!');


define('JMGRAPHIC_ADRECORD_FILE', __FILE__);
define('JMGRAPHIC_ADRECORD_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('JMGRAPHIC_ADRECORD_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define('ADRECORD_TEXT_DOMAIN_PATH', dirname(plugin_basename(__FILE__)) . '/languages');

define('JMGRAPHIC_ADRECORD_OPTIONS_KEY', 'Adrecord-Wordpress-Plugin'); //DB option key
define('ADRECORD_REST_NAMESPACE', 'adrecord/v1'); //Rest api namespace

require_once(JMGRAPHIC_ADRECORD_PATH . '/php/Main.php');
Jmgraphic\Adrecord\Main::instance();



add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');
function add_action_links($links)
{
  $link = '<a href="' . admin_url('admin.php?page=adrecord-settings') . '">' . __('Settings') . '</a>';
  array_unshift($links, $link);
  return $links;
}
