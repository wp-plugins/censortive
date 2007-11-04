<?php
/* 
Plugin Name: Censortive
Plugin URI: http://www.daobydesign.com/blog/censortive 
Version: 1.0
Description: A plugin that replaces pre-defined words with images to fool Internet censorship robots.
Author: Dao By Design
Author URI: http://www.daobydesign.com
*/

/*  Copyright 2007  Dao By Design  (email : info (at) daobydesign [dot] com)

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
if (!class_exists("CensortivePlugin")) {
	class CensortivePlugin {
		var $adminOptionsName = "CensortivePluginAdminOptions";
		
		function CensortivePlugin() {
		
		}
		
		function Censortize($content) {
			$ImgVariables = get_option($this->adminOptionsName);
			$CenImgVars = '&amp;font=' . $ImgVariables['font_file'] . '&amp;fsize=' . $ImgVariables['font_size'] . '&amp;fcolor=' . $ImgVariables['font_color']
						. '&amp;bgcol=' . $ImgVariables['bg_color'] . '&amp;trans=' . $ImgVariables['transparent_bg']
						. '&amp;cache=' . $ImgVariables['cache_images'] . '&amp;cachef=' . $ImgVariables['cache_folder'];

			$subject = $content;
			$pattern = '{(\[\*)(.*?)(\*\])}';
			preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
			foreach ($matches as $val) {
				$foundwords = $val[2];
				$imgurl = '<img src="' . get_bloginfo('wpurl') . '/wp-content/plugins/censortive/censimg.php?code=' . $foundwords . $CenImgVars .'" style="vertical-align: middle;" alt="censortive word" />';
				$replacethis = $val[0];
				$subject = str_replace($replacethis, $imgurl, $subject);
			}
			return $subject;
		}

		function init() {
		  $this->getAdminOptions();
		}

		//Returns an array of admin options
		function getAdminOptions() {
		  $CensortiveAdminOptions = array('font_file' => 'lib-sans-reg.ttf',
			  'font_size' => '11', 
			  'font_color' => '000000', 
			  'bg_color' => 'ffffff', 
			  'transparent_bg' => 'true', 
			  'cache_images' => 'true', 
			  'cache_folder' => 'cache');
		  $cenOptions = get_option($this->adminOptionsName);
		  if (!empty($cenOptions)) {
			  foreach ($cenOptions as $key => $option)
				  $CensortiveAdminOptions[$key] = $option;
		  }            
		  update_option($this->adminOptionsName, $CensortiveAdminOptions);
		  return $CensortiveAdminOptions;
		}


		//Prints out the admin page
		function printAdminPage() {
			$cenOptions = $this->getAdminOptions();
							 
			if (isset($_POST['update_CensortiveSettings'])) {
			  if (isset($_POST['CensortiveFontFile'])) {
				  $cenOptions['font_file'] = $_POST['CensortiveFontFile'];
			  }   
			  if (isset($_POST['CensortiveFontSize'])) {
				  $cenOptions['font_size'] = $_POST['CensortiveFontSize'];
			  }   
			  if (isset($_POST['CensortiveFontColor'])) {
				  $cenOptions['font_color'] = $_POST['CensortiveFontColor'];
			  }   
			  if (isset($_POST['CensortiveBGColor'])) {
				  $cenOptions['bg_color'] = $_POST['CensortiveBGColor'];
			  }   
			  if (isset($_POST['CensortiveTransBG'])) {
				  $cenOptions['transparent_bg'] = $_POST['CensortiveTransBG'];
			  }   
			  if (isset($_POST['CensortiveCache'])) {
				  $cenOptions['cache_images'] = $_POST['CensortiveCache'];
			  }   
			  if (isset($_POST['CensortiveCacheFolder'])) {
				  $cenOptions['cache_folder'] = $_POST['CensortiveCacheFolder'];
			  }   
			  update_option($this->adminOptionsName, $cenOptions);
			  ?>
		<div class="updated"><p><strong><?php _e("Settings Updated.", "CensortivePlugin");?></strong></p></div>
			<?php
			} ?>
		<div class=wrap>
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<h2>Censortive Options</h2>
			<div style="float:right;margin:10px; padding:5px;border:1px solid #999;background:#ffffcc;color:#336699;">
			<h3 style="margin:0;padding:0;width:98%;border-bottom:1px solid #003366;padding-bottom:3px;margin-bottom:3px;">Font Files Available</h3>
				<ul style="list-style:none;border-top:1px solid #333;border-bottom:1px solid #333;background:#eee;color:#003366;">
				<?php
				$dir = ABSPATH . "wp-content/plugins/censortive/fonts/";
				
				// Open a known directory, and proceed to read its contents
				if (is_dir($dir)) {
					if ($dh = opendir($dir)) {
						while (($file = readdir($dh)) !== false) {
							if ($file != '.' && $file != '..')
							echo "<li>$file</li>";
						}
						closedir($dh);
					}
				}
				?>
				</ul>
				<p><em>Upload additional TrueType (.ttf) font files to:<br /><strong>.../wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__)); ?>/fonts</strong>.</em></p>
 			</div>

		<h3 style="margin-bottom:3px;">Font File Location</h3>
		<input type="text" name="CensortiveFontFile" id="CensortiveFontFile_ID" style="width: 40; height: 20;" <?php if ($cenOptions['font_file'] != "") { _e('value=' . $cenOptions['font_file'], "CensortivePlugin"); }?> />
		<br /><strong>Default:</strong> <em>lib-sans-reg.ttf</em>
		<h3 style="margin-bottom:3px;padding-top:6px;border-top:1px solid #ccc;width:60%;display:block;">Font Size</h3>
		<input type="text" name="CensortiveFontSize" id="CensortiveFontSize_ID" style="width: 40; height: 20;" <?php if ($cenOptions['font_size'] != "") { _e('value=' . $cenOptions['font_size'], "CensortivePlugin"); }?> />
		<br /><strong>Default:</strong> <em>11 (measured in points)</em>
		<h3 style="margin-bottom:3px;padding-top:6px;border-top:1px solid #ccc;width:60%;display:block;">Font Color</h3>
		<input type="text" name="CensortiveFontColor" id="CensortiveFontColor_ID" style="width: 40; height: 20;" <?php if ($cenOptions['font_color'] != "") { _e('value=' . $cenOptions['font_color'], "CensortivePlugin"); }?> />
		<br /><strong>Default:</strong> <em>000000 (must be full <a href="http://html-color-codes.com/" title="Click for colour codes.">hex colour code</a>, not incl. #)</em>
		<h3 style="margin-bottom:3px;padding-top:6px;border-top:1px solid #ccc;width:60%;display:block;">Background Color</h3>
		<input type="text" name="CensortiveBGColor" id="CensortiveBGColor_ID" style="width: 40; height: 20;" <?php if ($cenOptions['bg_color'] != "") { _e('value=' . $cenOptions['bg_color'], "CensortivePlugin"); }?> />
		<br /><strong>Default:</strong> <em>ffffff (must be a full <a href="http://html-color-codes.com/" title="Click for colour codes.">hex colour code</a>, not incl. #)</em>
		<h3 style="margin-bottom:3px;padding-top:6px;border-top:1px solid #ccc;width:60%;display:block;">Transparent Background</h3>
		<label for="CensortiveTransBG_yes"><input type="radio" id="CensortiveTransBG_yes" name="CensortiveTransBG" value="true" <?php if ($cenOptions['transparent_bg'] == "true") { _e('checked="checked"', "CensortivePlugin"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="CensortiveTransBG_no"><input type="radio" id="CensortiveTransBG_no" name="CensortiveTransBG" value="false" <?php if ($cenOptions['transparent_bg'] == "false") { _e('checked="checked"', "CensortivePlugin"); }?>/> No</label>
		<br /><div style="width:60%;"><strong>Default:</strong> <em>'yes'. When set to 'yes', the edges of the image’s text will be blended with the background color to prevent anti-aliasing, and the actual background color will be entirely invisible.</em></div>
		<h3 style="margin-bottom:3px;padding-top:6px;border-top:1px solid #ccc;width:60%;display:block;">Cache Images</h3>
		<label for="CensortiveCache_yes"><input type="radio" id="CensortiveCache_yes" name="CensortiveCache" value="true" <?php if ($cenOptions['cache_images'] == "true") { _e('checked="checked"', "CensortivePlugin"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="CensortiveCache_no"><input type="radio" id="CensortiveCache_no" name="CensortiveCache" value="false" <?php if ($cenOptions['cache_images'] == "false") { _e('checked="checked"', "CensortivePlugin"); }?>/> No</label>
		<br /><strong>Default:</strong> <em>'yes'</em>
		<h3 style="margin-bottom:3px;padding-top:6px;border-top:1px solid #ccc;width:60%;display:block;">Cache Folder</h3>
		<input type="text" name="CensortiveCacheFolder" id="CensortiveCacheFolder" style="width: 40; height: 20;" <?php if ($cenOptions['cache_folder'] != "") { _e('value=' . $cenOptions['cache_folder'], "CensortivePlugin"); }?> />
		<br /><strong>Default:</strong> <em>cache. We suggest you not change this.</em>

		<div class="submit">
		<input type="submit" name="update_CensortiveSettings" value="<?php _e('Update Settings', 'CensortivePlugin') ?>" /></div>
		</form>
		 </div>
		<?php
		}//End function printAdminPage()
	
	} // Ends the Class

} //End Class CensortivePlugin IF

if (class_exists("CensortivePlugin")) {
	$censor_tive = new CensortivePlugin();
}

//Initialize the admin panel
//if (!function_exists("Censortive_ap")) {
	function CensortivePlugin_ap() {
		global $censor_tive;
		if (!isset($censor_tive)) {
			return;
		}
		if (function_exists('add_options_page')) {
		add_options_page('Censortive', 'Censortive', 5, basename(__FILE__), array(&$censor_tive, 'printAdminPage'));
		}
	}	
//}

//Actions and Filters	
if (isset($censor_tive)) {
	//Actions
	add_action('admin_menu', 'CensortivePlugin_ap');
	add_action('activate_censortive/censortive.php',  array(&$censor_tive, 'init'));
	//Filters
	add_filter('the_content', array(&$censor_tive, 'censortize')); 
}

?>