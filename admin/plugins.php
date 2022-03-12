<?php
/**
 * All Plugins
 *
 * Displays all installed plugins
 *
 * @package GetSimple Extended
 * @subpackage Plugins
 */

declare(strict_types=1);

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('inc/common.php');

$pluginid = (string)filter_input(INPUT_GET, 'set');
$nonce = (string)filter_input(INPUT_GET, 'nonce');

if ($pluginid != '' && check_nonce($nonce, 'set', 'plugins.php')) {
	$plugin = antixss($pluginid);
	change_plugin($plugin);
	redirect('plugins.php');
}

// Variable settings
login_cookie_check();
$counter = 0;
$table = '';

$pluginfiles = getFiles(GSPLUGINPATH);
natcasesort($pluginfiles);
foreach ($pluginfiles as $fi) {
	$pathExt = pathinfo($fi, PATHINFO_EXTENSION);
	$pathName = pathinfo_filename($fi);
	$nonce = '&amp;nonce=' . get_nonce('set', 'plugins.php');
	
	if ($pathExt == 'php') {
		if ($live_plugins[$fi]=='true') {
			$cls_Enabled = ' hidden';
			$cls_Disabled = '';
			$trclass = 'enabled';
		} else {
			$cls_Enabled = '';
			$cls_Disabled = ' hidden';
			$trclass = 'disabled';
		}
		$table .= '<tr id="tr-' . $counter . '" class="' . $trclass . '" >';
		$table .= '<td class="name"><span>' . $plugin_info[$pathName]['name'] . '</span></td>';
		$table .= '<td><span>' . $plugin_info[$pathName]['description'];
		if ($plugin_info[$pathName]['version'] != 'disabled') {
			$table .= '<br><b>' . i18n_r('PLUGIN_VER') . ' ' . $plugin_info[$pathName]['version'] . '</b> &mdash; ' . i18n_r('AUTHOR') . ': <a href="' . $plugin_info[$pathName]['author_url'] . '" target="_blank">' . $plugin_info[$pathName]['author'] . '</a></span>';
		}
		$table.= '</td><td class="status">';
		$table .= '<a href="plugins.php?set=' . $fi . $nonce . '" class="toggleEnable' . $cls_Enabled . '" title="' . i18n_r('ENABLE') . ': ' . $plugin_info[$pathName]['name'] . '">' . i18n_r('ENABLE') . '</a>';
		$table .= '<a href="plugins.php?set=' . $fi . $nonce . '" class="cancel toggleEnable' . $cls_Disabled . '" title="' . i18n_r('DISABLE') . ': ' . $plugin_info[$pathName]['name'] . '">' . i18n_r('DISABLE') . '</a></td>';
		$table .= '</tr>';
		$counter++;
	}
}

exec_action('plugin-hook');
get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('PLUGINS_MANAGEMENT'));

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">
	
	<div id="maincontent">
		<div class="main">
			<h3><?php i18n('PLUGINS_MANAGEMENT'); ?></h3>

		<?php if ($counter > 0) { ?>
			<table class="plugins edittable highlight">
				<tr><th class="name"><?php i18n('PLUGIN_NAME'); ?></th><th><?php i18n('PLUGIN_DESC'); ?></th><th class="status"><?php i18n('STATUS'); ?></th></tr>
				<?php echo $table; ?>
			</table>
		<?php } ?>

		<p><em><?php i18n('PLUGINS_INSTALLED'); ?>: <strong><span id="pg_counter"><?php echo $counter; ?></span></strong></em></p>

		</div>
	</div>

	<div id="sidebar"><?php include('template/sidebar-plugins.php'); ?></div>

</div>

<?php get_template('footer'); ?>
