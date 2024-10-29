<?php 
/*
Plugin Name: Ajah Comments (Paged Comments Ajaxed!)
Plugin URI: http://wp.uberdose.com/2007/08/19/ajah-comments/
Description: Paged comments with Ajax/Ajah, very useful for huge comment threads.
Author: uberdose
Version: 0.2
Author URI: http://wp.uberdose.com
*/

/*
Copyright (C) 2007 uberdose.com (ajahcomments AT uberdose DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class __Ajah_Comments__ {

	var $comments_per_page = 50;
	
	function init() {
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('ajah_comments', 'wp-content/plugins/ajah-comments');
		}
		$this->comments_per_page = stripcslashes(get_option('ajah_comments_per_page'));
	}

	function wp_head() {
		$home = get_settings('siteurl');
		$stylesheet = $home.'/wp-content/plugins' . $this->get_base() . '/css/ajah-comments.css';
		echo('<link rel="stylesheet" href="' . $stylesheet . '" type="text/css" media="screen" />');
		wp_register_script('ajah-comments', $home.'/wp-content/plugins' . $this->get_base() . '/js/ajah.js', false, '1');	
		wp_print_scripts(array('ajah', 'ajah-comments'));
	}
	
	function get_base() {
   		 return '/'.end(explode('/', str_replace(array('\\','/ajah-comments.php'),array('/',''),__FILE__)));
	}

	function template_redirect() {
		$this->start = $_REQUEST['ajah_start'];
	}
	
	function comments_template($filename) {
		return dirname(__FILE__) . "/templates/ajah-first-comments.php";
	}
	
	function comments_popup_template($filename) {
		return dirname(__FILE__) . "/templates/ajah-all-comments.php";
	}
	
	function comment_post_redirect($location) {
		if (preg_match('/\/\?comments_popup=(\d+)#comment-(\d+)/', $location, $matches)) {
			$post_id = $matches[1];
			$comment_id = $matches[2];
			return get_permalink($post_id) . "#comment-$comment_id";
		}
	}
	
	function comments_per_page() {
		return $this->comments_per_page;
	}
	
	function admin_menu() {
		add_submenu_page('options-general.php', __('Ajah Comments', 'ajah_comments'), __('Ajah Comments', 'ajah_comments'), 5, __FILE__, array($this, 'plugin_menu'));
	}
	
	function plugin_menu() {
		$message = null;
		$message_updated = __("Ajah Comments Options Updated.");
		
		// update options
		if ($_POST['action'] && $_POST['action'] == 'ajah_update') {
			$message = $message_updated;
			update_option('ajah_comments_per_page', $_POST['ajah_comments_per_page']);
			wp_cache_flush();
		}

?>
<?php if ($message) : ?>
<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<div id="dropmessage" class="updated" style="display:none;"></div>
<div class="wrap">
<h2><?php _e('Ajah Comments Options', 'ajah_comments'); ?></h2>
<form name="dofollow" action="" method="post">
<table>
<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<?php _e('Comments Per Page:', 'ajah_comments')?>
</td>
<td>
<input size="5" name="ajah_comments_per_page" value="<?php echo stripcslashes(get_option('ajah_comments_per_page')); ?>"/>
</td>
</tr>
</table>
<p class="submit">
<input type="hidden" name="action" value="ajah_update" /> 
<input type="submit" name="Submit" value="<?php _e('Update Options')?> &raquo;" /> 
</p>
</form>
</div>
<?php
	
	} // plugin_menu
}

$__ajah_comments__ = new __Ajah_Comments__();

add_option("ajah_comments_per_page", $__ajah_comments__->comments_per_page, __('Ajah Comments Number of Pages per Post', 'ajah_comments'), 'yes');

add_filter('comments_template', array($__ajah_comments__, 'comments_template'));
add_filter('comments_popup_template', array($__ajah_comments__, 'comments_popup_template'));
add_action('init', array($__ajah_comments__, 'init'));
add_action('wp_head', array($__ajah_comments__, 'wp_head'));
add_action('template_redirect', array($__ajah_comments__, 'template_redirect'));

add_action('admin_menu', array($__ajah_comments__, 'admin_menu'));


?>