<?php
/**
 * Settings
 *
 * Displays and changes website settings
 *
 * @package GetSimple Extended
 * @subpackage Settings
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

# variable settings
login_cookie_check();
$fullpath = suggest_site_path();

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('GENERAL_SETTINGS'));

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
			<h3><?php i18n('WEBSITE_SETTINGS');?></h3>
			<form class="largeform" action="changedata.php" method="post" accept-charset="utf-8">
				<input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce('save', 'settings.php'); ?>">
				<input type="hidden" name="created" value="<?php echo $dataw->attributes()->created; ?>">
				<input id="revision-number" name="revision-number" type="hidden" value="<?php echo (string)$dataw->attributes()->revisionNumber ?: '0'; ?>">
				<input type="hidden" name="theme" value="<?php echo $dataw->theme ?: $dataw->TEMPLATE; ?>">
				<input id="action" name="action" type="hidden" value="save">
				<div class="leftsec">
					<p><label for="title"><?php i18n('LABEL_WEBSITE');?>:</label><input name="title" type="text" value="<?php echo $dataw->title ?: $dataw->SITENAME; ?>"></p>
				</div>
				<div class="rightsec">
					<p><label for="url"><?php i18n('LABEL_BASEURL');?>:</label><input name="url" type="url" value="<?php echo $dataw->url ?: $dataw->SITEURL; ?>"></p>
					<?php if ($fullpath != (string)$dataw->url) { echo '<p id="suggested-url">' . i18n_r('LABEL_SUGGESTION') . ': <code>' . $fullpath . '</code></p>'; } ?>
				</div>
				<div class="widesec">
					<p>
						<label for="lang" ><?php i18n('LABEL_WEBSITELANG');?>:</label>
						<span class="hint"><?php i18n('DISPLAY_WEBSITELANG');?></span>
						<input name="lang" type="text" placeholder="<?php i18n('PLACEHOLDER_LANG'); ?>" value="<?php echo $dataw->lang; ?>">
					</p>
					<p>
						<label for="description"><?php i18n('LABEL_WEBSITEDESCRIPTION'); ?>:</label><textarea name="description"><?php echo $dataw->description; ?></textarea>
					</p>
				</div>
				<p class="inline" ><input name="maintenance" id="maintenance" type="checkbox" value="1"<?php echo $dataw->maintenance == '1' ? ' checked' : ''; ?>> <label for="maintenance" ><?php i18n('MAINTENANCE_ENABLE');?></label></p>
				<p class="inline" ><input name="prettyurls" type="checkbox" value="1"<?php echo $dataw->prettyurls == '1' ? ' checked' : ''; ?>> <label for="prettyurls" ><?php i18n('USE_PRETTY_URLS');?></label></p>
				<div class="widesec">
					<p><label for="permalink" class="clearfix"><?php i18n('PERMALINK');?>: <span class="right"><a href="https://github.com/dimayakovlev/getsimple-extended-cms/wiki/Pretty-URLs" target="_blank" ><?php i18n('MORE');?></a></span></label><input name="permalink" type="text" placeholder="%parent%/%slug%/" value="<?php echo $dataw->permalink ?: $dataw->PERMALINK; ?>"></p>
					<p><a id="flushcache" class="button" href="changedata.php?action=flushcache&nonce=<?php echo get_nonce('flushcache', 'settings.php'); ?>"><?php i18n('FLUSHCACHE'); ?></a></p>
				</div>
				<?php exec_action('settings-website-extras'); ?>
				<p id="submit_line">
					<span><input class="submit" type="submit" name="submitted" value="<?php i18n('BTN_SAVESETTINGS');?>"></span> <?php i18n('OR'); ?> <a class="cancel" href="settings.php?cancel"><?php i18n('CANCEL'); ?></a>
				</p>
				<p class="backuplink">
				<?php
					if ((string)$dataw->attributes()->modified) {
						echo sprintf(i18n_r('LAST_SAVED'), '<em>' . ((string)$dataw->attributes()->user ?: '-') . '</em>', lngDate((string)$dataw->attributes()->modified));
					}
				?>
				</p>
			</form>
		</div>
	</div>

	<div id="sidebar"><?php include('template/sidebar-settings.php'); ?></div>

</div>
<?php get_template('footer'); ?>
