<?php
/**
 * Navigation Include Template
 *
 * @package GetSimple Extended
 */

if (cookie_check()) {
	echo '<ul id="pill">';
	echo '<li class="user"><a href="user.php">' . i18n_r('WELCOME') . ', <strong>' . ($datau->name != '' ? var_out($datau->name) : $USR) . '!</strong></a></li>';
	if ($dataw->maintenance == '1') {
		echo '<li class="mode maintenance"><a href="settings.php#maintenance">' . i18n_r('MAINTENANCE_MODE') . '</a></li>';
	}
	if (isDebug()) {
		echo '<li class="mode debug"><a href="#gsdebug">' . i18n_r('DEBUG_MODE') . '</a></li>';
	}
	echo '<li class="logout"><a href="logout.php" accesskey="' . find_accesskey(i18n_r('TAB_LOGOUT')) . '">' . i18n_r('TAB_LOGOUT') . '</a></li>';
	echo '</ul>';
}

//determine page type if plugin is being shown
$plugin_class = get_filename_id() == 'load' ? $plugin_info[$plugin_id]['page_type'] : '';

?>
		<h1 id="sitename"><a href="<?php echo $SITEURL; ?>" target="_blank" ><?php echo cl($SITENAME); ?></a></h1>
		<ul class="nav <?php echo $plugin_class; ?>">
			<li id="nav_pages"><a class="pages" href="pages.php" accesskey="<?php echo find_accesskey(i18n_r('TAB_PAGES'));?>"><?php i18n('TAB_PAGES');?></a></li>
			<li id="nav_upload"><a class="files" href="upload.php" accesskey="<?php echo find_accesskey(i18n_r('TAB_FILES'));?>"><?php i18n('TAB_FILES');?></a></li>
			<li id="nav_theme"><a class="theme" href="theme.php" accesskey="<?php echo find_accesskey(i18n_r('TAB_THEME'));?>"><?php i18n('TAB_THEME');?></a></li>
			<li id="nav_backups"><a class="backups" href="backups.php" accesskey="<?php echo find_accesskey(i18n_r('TAB_BACKUPS'));?>"><?php i18n('TAB_BACKUPS');?></a></li>
			<li id="nav_plugins"><a class="plugins" href="plugins.php" accesskey="<?php echo find_accesskey(i18n_r('PLUGINS_NAV'));?>"><?php i18n('PLUGINS_NAV');?></a></li>
			<?php exec_action('nav-tab'); ?>
			<li id="nav_loaderimg"><img class="toggle" id="loader" src="template/images/ajax.gif" alt="" /></li>
			<li id="nav_support"><a href="support.php" accesskey="<?php echo find_accesskey(i18n_r('TAB_SUPPORT'));?>" ><?php i18n('TAB_SUPPORT');?></a></li>
			<li id="nav_settings"><a href="settings.php" accesskey="<?php echo find_accesskey(i18n_r('TAB_SETTINGS'));?>" ><?php i18n('TAB_SETTINGS');?></a></li>
		</ul>
	</div>
</header>

<div class="wrapper">

<?php include('template/error_checking.php'); ?>
