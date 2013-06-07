<?php
/*
Plugin Name: My Hawk
Plugin URI: http://www.danycode.com/my-hawk/
Description: Keep track of your Facebook Comments by using this simple plugin.
Version: 1.00
Author: Danilo Andreini
Author URI: http://www.danycode.com
License: GPLv2 or later
*/

/*  Copyright 2012  Danilo Andreini (email : andreini.danilo@gmail.com)

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

//includes external files
require_once('includes/head.php');//front end and administrator hooks
require_once('includes/menu_discussion.php');//create the table

//create the mail list menu
add_action( 'admin_menu', 'my_hawk_menu_handler' );
function my_hawk_menu_handler() {
	$form_name='My Hawk';
	add_menu_page($form_name, $form_name, 'manage_options', 'menu_discussion','menu_discussion_my_hawk',plugins_url().'/my-hawk/img/icon16.png');
	//add_submenu_page('menu_discussion', $form_name.' - Discussion', 'Discussion', 'manage_options', 'menu_discussion', 'menu_discussion_my-hawk');
	//add_submenu_page('menu_discussion', $form_name.' - Setup', 'Setup', 'manage_options', 'menu_setup_my-hawk', 'menu_setup_my-hawk');
}

?>
