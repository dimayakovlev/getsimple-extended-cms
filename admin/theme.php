<?php
/**
 * Theme
 *
 * @package GetSimple Extended
 * @subpackage Theme
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

# variable settings
login_cookie_check();

$theme_options 	= '';

# get available themes (only look for folders)
$themes_handle = opendir(GSTHEMESPATH) or die("Unable to open " . GSTHEMESPATH);
while ($file = readdir($themes_handle)) {
	$curpath = GSTHEMESPATH . $file;
	if (is_dir($curpath) && $file != "." && $file != ".." ) {
		if (file_exists($curpath . '/template.php')) {
			$theme_options .= '<option' . (($TEMPLATE == $file) ? ' selected' : '') . ' value="' . $file . '" >' . $file . '</option>';
		}
	}
}

get_template('header', cl($SITENAME).' &raquo; '.i18n_r('THEME_MANAGEMENT')); 

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
		<h3><?php i18n('CHOOSE_THEME');?></h3>
		<form action="changedata.php" method="post" accept-charset="utf-8">
		<input name="nonce" type="hidden" value="<?php echo get_nonce('save', 'theme.php'); ?>">
		<input id="action" name="action" type="hidden" value="save">
		<?php
			$theme_path = str_replace(GSROOTPATH, '' ,GSTHEMESPATH);
			if ($SITEURL) {
				echo '<p><strong>'.i18n_r('THEME_PATH').':</strong> <code>' . $SITEURL  .$theme_path . $TEMPLATE . '/</code></p>';
			}
		?>
		<p>
			<select id="theme_select" class="text" style="width:250px;" name="theme"><?php echo $theme_options; ?></select> <input class="submit" type="submit" name="submitted" value="<?php i18n('ACTIVATE_THEME');?>">
		</p>
		</form>
		<?php
		 	if (file_exists('../theme/' . $TEMPLATE . '/images/screenshot.png')) { 
				echo '<p><img id="theme_preview" src="../' . $theme_path . $TEMPLATE . '/images/screenshot.png" alt="' . i18n_r('THEME_SCREENSHOT') . '" /></p>';
				echo '<span id="theme_no_img" style="visibility:hidden"><p><em>'.i18n_r('NO_THEME_SCREENSHOT').'</em></p></span>';
			} else {
				echo '<p><img id="theme_preview" style="visiblity:hidden;" src="../' . $theme_path . $TEMPLATE . '/images/screenshot.png" alt="' . i18n_r('THEME_SCREENSHOT') . '" /></p>';
				echo '<span id="theme_no_img"><p><em>' . i18n_r('NO_THEME_SCREENSHOT') . '</em></p></span>';
			}

			exec_action('theme-extras');
		?>

		</div>

	</div>

	<div id="sidebar" >
		<?php include('template/sidebar-theme.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>