<?php
/**
 * Footer Admin Template
 *
 * @package GetSimple Extended
 */
?>
		<div id="footer">
			<div class="footer-content">
<?php
	include(GSADMININCPATH . 'configuration.php');
	if (cookie_check()) echo '<p><a href="pages.php">' . i18n_r('PAGE_MANAGEMENT') . '</a> &nbsp;&bull;&nbsp; <a href="upload.php">' . i18n_r('FILE_MANAGEMENT') . '</a> &nbsp;&bull;&nbsp; <a href="theme.php">' . i18n_r('THEME_MANAGEMENT') . '</a> &nbsp;&bull;&nbsp; <a href="backups.php">' . i18n_r('BAK_MANAGEMENT') . '</a> &nbsp;&bull;&nbsp; <a href="plugins.php">' . i18n_r('PLUGINS_MANAGEMENT') . '</a> &nbsp;&bull;&nbsp; <a href="settings.php">' . i18n_r('GENERAL_SETTINGS') . '</a> &nbsp;&bull;&nbsp; <a href="support.php">' . i18n_r('SUPPORT') . '</a></p>';
	if (!isAuthPage()) {
		echo '<p>&copy; 2009 - ' . date('Y') . '<a href="' . $site_link_back_url . '" target="_blank">' . $site_full_name . '</a> &ndash; ' . i18n_r('VERSION') . ' ' . $site_version_no . '</p>';
		get_scripts_backend(true);
		exec_action('footer');
	}
?>
			</div> <!-- end .footer-content -->
		</div><!-- end #footer -->
<?php
	if (!isAuthPage() && isDebug()) {
		global $GS_debug;
		echo '<div id="debug-console"><h2>' . i18n_r('DEBUG_CONSOLE') . '</h2><div id="gsdebug"><pre>';
		foreach ($GS_debug as $log) {
			if (is_array($log)) {
				print_r($log) . '<br/>';
			} else {
				print($log . '<br/>');
			}
		}
		echo '</pre></div></div>';
	}
?>
	</div><!-- end .wrapper -->
</body>
</html>
