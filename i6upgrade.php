<?php
/*
Plugin Name: Internet Explorer 6 Upgrade
Plugin URI: http://marcelorodrigo.com/wordpress-plugins/internet-explorer-6-upgrade
Description: Detects if visitor using Internet Explorer 6 and offers a screen to download a better an update version of most popular (and secure, yeah!) browsers.
Version: 1.1
Author: Marcelo Rodrigo
Author URI: http://marcelorodrigo.com
*/

/* Copyright (C) 2009-2010 marcelorodrigo.com

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
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA */
 
class ie6upgrade {
	
	function add_scripts(){
		//	Add jQuery and Thickbox scripts
		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox');
	}
	
	function add_styles(){
		//	Add Thickbox CSS and plugin CSS
		wp_enqueue_style('ie6upgrade',plugins_url('/internet-explorer-6-upgrade/ie6upgrade.css'));
		wp_enqueue_style('thickbox');
	}

	/**
	 * Create cookie setting the current visitor's have displayed message in your browser on this session
	 */
	function create_cookie()
	{
		setcookie("ie6upgrade", '1');
	}

	/**
	 * Check if message of ie6upgrade are displayed in this session, returns true if displayed, false when not displayed
	 */
	function check_cookie()
	{
		return $_COOKIE['ie6upgrade'];

	}
	
	function add_footer() {
		//	Runs conditional script to show output in IE6 or lower browser
		ob_start();
		$retorno = include('ie6upgrade_show.php');
		echo $retorno;
		echo "<!--[if lte IE 6]>";
		echo "<script type=\"text/javascript\">";
		echo "jQuery(function(){";
		echo "setTimeout(function(){ tb_show('",__('Update your Browser','ie6upgrade'),"', '#TB_inline?height=280&inlineId=ie6upgrade_main&width=700&modal=false', null);";
		echo "}, 1000);";
		echo "});";
		echo "</script>";
		echo "<![endif]-->";
		ob_get_contents();
	}	
}

// THIS PLUGINS ONLY 'RUNS' AND OUTPUT THE HTML IF VISITOR BROWSER IS MSIE AND MESSAGE NOT DISPLAYED ON THIS SESSION

// Obtaining browser name
$browser = strtolower($_SERVER['HTTP_USER_AGENT']);

// Loading Instance
$obj_ie6upgrade = new ie6upgrade();

// Checking browser matches IE
if(ereg("msie", $browser) and $obj_ie6upgrade->check_cookie()){

	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain('ie6upgrade', 'wp-content/plugins/' . $plugin_dir, $plugin_dir );
	
	add_action('wp_print_scripts', array($obj_ie6upgrade, 'add_scripts'));
	add_action('wp_print_styles', array($obj_ie6upgrade, 'add_styles'));
	add_action('wp_footer', array($obj_ie6upgrade, 'add_footer'));

	// Message displayed
	$obj_ie6upgrade->create_cookie();
}
?>