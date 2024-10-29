<?php

/* anflex_ga.php
	anflex_ga class file
*/

if (!class_exists("anflex_ga")) {
	class anflex_ga {

		/* Default Values */
		var $aon="anflex_ga_settings";
		var $d_gaid="UA-XXXXXX-XX";
		var $d_el="exe,txt,xml,doc,xls,ppt,docx,xlsx,pptx,pdf,zip,wav,mp3,mov,mpg,avi,wmv";
		
		/* Saved Settings */
		var $ss;
		
		/* function anflex_ga()
		Constructor
		*/
		function anflex_ga() {
		}

		/* function gs()
		Returns saved settings, if not saved, returns default settings
		*/
		
		function gs() {
			anflex_msg('gs() started');
			/* Default list of preferences */
			$s = array(
			'gaid' => $this->d_gaid,
			't' => false,
			'ea' => true,
			'lt_e' => false,
			'lt_el' => '',
			'lt_d' => false,
			'lt_dl' => $this->d_el
			);

			/* Get previously saved preference and overwrite defaults */
			if(empty($this->ss)) {
				anflex_msg('gs() $this->ss is empty');
				$ss=get_option($this->aon);
			} else {
				anflex_msg('gs() $this->ss is not empty');
				$ss=$this->ss;
			}
			
			/* Legacy Setting Deletion: delete whole database entry if oblivious setting is in the db */
			if($ss['tracking']) {
				anflex_msg('gs() old settings detected');
				delete_option($this->aon);
				anflex_msg('gs() old settings deleted');
			}
			
			if(!empty($ss)) {
			
				/* Legacy setting cleanup */
				foreach ($ss as $key => $option) {
					if($key=='tracking') {$s['t']==$option;unset($ss[$key]);}
					if($key=='exclude_admin') {$s['ea']==$option;unset($ss[$key]);}
					if($key=='link_tracking_external') {$s['lt_e']==$option;unset($ss[$key]);}
					if($key=='link_tracking_external_exclude_list') {$s['lt_el']==$option;unset($ss[$key]);}
					if($key=='link_tracking_download') {$s['lt_d']==$option;unset($ss[$key]);}
					if($key=='link_tracking_download_extension_list') {$s['lt_dl']==$option;unset($ss[$key]);}
					if($key=='event_tracking') {unset($ss[$key]);}
				}
				
				/* integrate with new setting */
				foreach ($ss as $key => $option) {
					$s[$key] = $option;
				}
			}
			
			update_option($this->aon,$s);
			anflex_msg('gs() option updated');
			$this->ss=$s;
			return $s;
		}

		/* function aef()
		Admin Tracking Exclusion flag
		*/
		function aef() {
		anflex_msg('aef() started');
			if ($this->ss['ea'] == true) {
				if (current_user_can('level_8')||current_user_can('activate_plugins')) {
					return true;
				}
			}
			return false;
		}

		/* function aac()
		Adds Google Analytics code to page
		*/
		function aac() {
		anflex_msg('aac() started');
			if ($this->aef()==false) {
			
				if(!empty($this->ss)) {
					anflex_msg('aac() $this->ss is not empty');
					$s=$this->ss;
				} else {
					anflex_msg('aac() $this->ss is empty - $this->gs() is being called');
					$s=$this->gs();
				}

?>
<script type="text/javascript">
var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $s['gaid']; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

<?php
				if($s['lt_e']){
?>
if(anflexGA) {
	anflexGA.lt_e = true;
	anflexGA.internal_domain_list = location.hostname+"<?php if($s['lt_el']) echo ','.$s['lt_el']; ?>";
}
<?php
				}

				if($s['lt_d']){
?>
if(anflexGA) {
	anflexGA.lt_d = true;
	anflexGA.download_extension_list = "<?php if($s['lt_dl']) echo $s['lt_dl']; ?>";
}
<?php
				}
?>
</script>
<?php
			} else if ($this->aef()==true) {
?>
<!-- Admins excluded from Google Analytics tracking, by Anflex GA -->
<?php
			}
		}
		

		/* function ap()
		Prints admin panel HTML
		*/
		
		function ap() {
			anflex_msg('ap() started');

			if(!empty($this->ss)) {
				anflex_msg('ap() $this->ss is not empty');
				$s=$this->ss;
			} else {
				anflex_msg('ap() $this->ss is empty - $this->gs() is being called');
				$s=$this->gs();
			}
			
			/* form submit result: gaid update */
			if (isset($_POST['update_gaid'])) {
				$s['gaid']=isset($_POST['gaid'])?$_POST['gaid']:$s['gaid'];
				update_option($this->aon, $s);
?>
<div class="updated"><p><strong><?php _e("Google Analytics Web Property ID Updated.", "anflex_ga");?></strong></p></div>
<?php
			}

			/* form submit result: enable/disable tracking */
			if (isset($_POST['toggle_tracking'])) {
				if(isset($_POST['t'])) {
					if($_POST['t']=='true') {
						$s['t']=true;
					} else if($_POST['t']=='false') {
						$s['t']=false;
					}
				}
				update_option($this->aon, $s);
?>
<div class="updated"><p><strong><?php if($s['t']==true) {_e("Tracking Enabled", "anflex_ga");} else {_e("Tracking Disabled.", "anflex_ga");} ?></strong></p></div>
<?php
			}

			/* form submit result: tracking options update */
			if (isset($_POST['update_options'])) {
				$s['ea'] = isset($_POST['ea'])?true:false;
				$s['lt_e'] = isset($_POST['lt_e'])?true:false;
				$s['lt_el'] = isset($_POST['lt_el'])?$_POST['lt_el']:$s['lt_el'];
				$s['lt_d'] = isset($_POST['lt_d'])?true:false;
				$s['lt_dl'] = isset($_POST['lt_dl'])?$_POST['lt_dl']:$s['lt_dl'];
				$s['404'] = isset($_POST['404'])?true:false;

				update_option($this->aon, $s);
?>
<div class="updated"><p><strong><?php _e("Settings Updated.", "anflex_ga");?></strong></p></div>
<?php
			}
?>

<div class=wrap>

<h2>Anflex GA Settings <span id="anflex_ga_version">v<?php global $anflex_ga_ver; echo $anflex_ga_ver; ?></span></h2>
<h3>Tracking Setup<!-- <a href="http://anflex.net/ga-wordpress/help/trackingsetup/<?php global $anflex_ga_ver; echo $anflex_ga_ver; ?>/" class="anflex_ga_textbtn">?help</a>--></h3>

<form name="anflex_ga_gaid" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<p><?php _e('Google Analytics Web Property ID','anflex_ga'); ?> <span id="anflex_ga_gaid"><?php if($s['gaid']&&$s['gaid']!=$this->d_gaid) { ?><strong><?php echo $s['gaid']; ?></strong></span> <span id="anflex_ga_gaid_update" style="display:none;"><input type="text" name="gaid" size="14" value="<?php echo $s['gaid']; ?>" /><span class="submit"><input type="submit" name="update_gaid" value="<?php _e('Update ID', 'anflex_ga') ?>" /></span></span><?php } else { ?><input type="text" name="gaid" size="14" value="<?php echo $s['gaid']; ?>" /><span class="submit"><input type="submit" name="update_gaid" value="<?php _e('Save ID', 'anflex_ga') ?>" /></span><?php } ?> <a href="javascript:anflexGA.tsi('anflex_ga_gaid');" id="anflex_ga_gaid_clink" class="anflex_ga_textbtn">change</a></span></p>
</form>

<form name="anflex_ga_toggle_tracking" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<p><span id="anflex_ga_tracking_update" style="display:none;"><?php if($s['t']==true) { ?><input type="hidden" name="t" value="false" /><span class="submit"><input type="submit" name="toggle_tracking" value="<?php _e('Disable Tracking', 'anflex_ga') ?>" /></span><?php } else { ?><input type="hidden" name="t" value="true" /><span class="submit"><input type="submit" name="toggle_tracking" value="<?php _e('Enable Tracking', 'anflex_ga') ?>" /></span><?php } ?></span> <span id="anflex_ga_tracking"><?php _e('Tracking','anflex_ga'); ?> <strong><?php if($s['t']==true) {_e('Enabled','anflex_ga');} else {_e('Disabled','anflex_ga');} ?></strong></span> <a href="javascript:anflexGA.tsi('anflex_ga_tracking');" id="anflex_ga_tracking_clink" class="anflex_ga_textbtn">change</a> <?php if($s['t']==false) _e('You can still change settings below.','anflex_ga'); ?></p>
</form>


<div id="anflex_ga_tracking_settings" class="anflex_ga_admin_block" style="padding:0 10px;border:solid 1px #ddd;<?php if($s['t']==false) echo 'color:#aaaaaa'; ?>">

<form name="update_options" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

<h3>Tracking Options<!-- <a href="http://anflex.net/ga-wordpress/help/trackingoptions/<?php global $anflex_ga_ver; echo $anflex_ga_ver; ?>/" class="anflex_ga_textbtn">?help</a>--></h3>

<p><input type="checkbox" name="ea" <?php if($s['ea']==true) {echo 'checked="checked"';} ?> /> <strong><?php _e('Exclude Admins From Tracking','anflex_ga'); ?></strong></p>
<p>With this box checked, Google Analytics code snippet won't be inserted to the web pages for logged-in Administrator accesses.</p>

<p><input type="checkbox" name="lt_d" <?php if($s['lt_d']==true) {echo 'checked="checked"';} ?> /> <strong><?php _e('Enable Download Link Tracking','anflex_ga'); ?></strong> for the file extensions listed (comma-separated)</p>
<p><input type="text" name="lt_dl" class="anflex_ga_input_list" value="<?php _e(apply_filters('Link Tracking Download File Extension List',$s['lt_dl']), 'anflex_ga') ?>" /><?php if($s['lt_dl']!=$this->d_el) { ?><br />Default <?php echo $this->d_el; ?></p><?php } ?>

<p><input type="checkbox" name="lt_e" <?php if($s['lt_e']==true) {echo 'checked="checked"';} ?> /> <strong><?php _e('Enable External Link Tracking','anflex_ga'); ?></strong> with domains to exclude other than the page domain (comma-separated)</p>
<p><input type="text" name="lt_el" class="anflex_ga_input_list" value="<?php _e(apply_filters('Link Tracking External Exclude List',$s['lt_el']), 'anflex_ga') ?>" /><?php if($s['lt_el']) { ?><br />Default blank</p><?php } ?>

<ul>
<li>When both Download Link and External Link criteria are met at the same time, only Download Link occurs.</li>
<li>Links are tracked as Events, found in "Content > Event Tracking".</li>
</ul>

<div class="submit"><input type="submit" class="button-primary" name="update_options" value="<?php _e('Update Tracking Option Settings', 'anflex_ga') ?>" /></div>
</form>


</div>


</div><!-- end .wrap -->



<?php
		}

		/* function aap()
		Add the admin panel option page
		Page html is added through ap()
		*/
		function aap() {
			anflex_msg('aap() started');
			global $anflex_ga;
			if (!isset($anflex_ga)) {
				return;
			}

			if (function_exists('add_options_page')) {
				add_options_page('Anflex GA', 'Anflex GA', 9, basename(__FILE__), array(&$anflex_ga, 'ap'));
			}
		}
		
		/* function load_jquery()
		load jQuery
		*/
		function load_jquery() {
			anflex_msg('load_jquery() started');
			wp_enqueue_script('jquery');
		}

		/* function load_admin_scripts()
		load admin scripts
		*/
		function load_admin_scripts() {
			anflex_msg('load_admin_scripts() started');
			wp_enqueue_style('anflex_ga_admin_css',WP_PLUGIN_URL . '/anflex-ga/css/admin_panel.css',false,false,'all');
			wp_enqueue_script('anflex_ga_admin_js',WP_PLUGIN_URL . '/anflex-ga/js/admin_panel.js',array('jquery'));
		}

		/* function load_event_scripts()
		load event tracking script
		*/
		function load_event_scripts() {
			anflex_msg('load_event_scripts() started');
			wp_enqueue_script('anflex_ga_event_tracking_js',WP_PLUGIN_URL . '/anflex-ga/js/event_tracking.js',array('jquery'));
		}


	}
}
?>