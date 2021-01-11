<?php
/**
 * @package WSME by f20manuel
 * @version 1.0.0
 */
/*
Plugin Name: WSME by f20manuel
Plugin URI: https://f20manuel.com/plugins/wsme
Description: This plugin is used to add a floating whatsapp button to your website
Version: 1.0.0
Author: Manuel Fernández
Author URI: https://f20manuel.com
*/
require_once("admin/options.php");

defined('ABSPATH') or die("Bye bye");

//register styles
add_action('init', 'onHead');
function onHead () {
  wp_register_script( 'wsme_fontAwesome', "https://kit.fontawesome.com/0c3e8c9d69.js", false, true );
  wp_register_style( 'wsme_styles', plugins_url( "/css/styles.css", __FILE__ ), false, 'all' );
}

add_action('wp_enqueue_scripts', 'enqueue_head');
function enqueue_head () {
   wp_enqueue_script('wsme_fontAwesome');

   wp_enqueue_style( 'wsme_styles' );
}

add_filter('the_content', 'wsme_content', 1);
function wsme_content () {
  echo "
    <a class='wsme_btn' href='https://wa.me/".get_option('wsme_number')."'>
      <i class='fab fa-whatsapp'></i>
    </a>
  ";
}

add_action("admin_menu", 'wsme_menu_administrator');
function wsme_menu_administrator () {
  add_menu_page( "wsme configuración", "WSME", 'manage_options', plugins_url( "/admin/options.php", __FILE__ ), 'wsme_options_page' );
  add_action("admin_init", "register_wsme_options");
}

function register_wsme_options () {
  register_setting( "wsme_options_group", "wsme_number" );
}

