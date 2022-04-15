<?php
/**
 * Sidebar Pages Template
 *
 * @package GetSimple Extended
 */

declare(strict_types=1);

$id = (string)filter_input(INPUT_GET, 'id');
$filename = get_filename_id();

?>
<ul class="snav">
	<li id="sb_pages"><a href="pages.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_VIEW_PAGES'));?>" <?php check_menu('pages'); ?>><?php i18n('SIDE_VIEW_PAGES'); ?></a></li>
	<li id="sb_newpage"><a href="edit.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_CREATE_NEW'));?>" <?php if ($filename == 'edit' && $id == '') { echo 'class="current"'; } ?>><?php i18n('SIDE_CREATE_NEW'); ?></a></li>
	<?php if ($filename == 'edit' && $id != '') { ?><li id="sb_pageedit" ><a href="#" class="current"><?php i18n('EDITPAGE_TITLE'); ?></a></li><?php } ?>
	<li id="sb_menumanager"><a href="menu-manager.php" accesskey="<?php echo find_accesskey(i18n_r('MENU_MANAGER'));?>" <?php check_menu('menu-manager'); ?>><?php i18n('MENU_MANAGER'); ?></a></li>
<!-- System pages -->
	<li id="sb_edit_system_403"><a href="<?php echo ($filename == 'edit-system' && $id == '403') ? '#' : 'edit-system.php?id=403'; ?>"<?php if ($filename == 'edit-system' && $id == '403') echo ' class="current"'; ?>><?php echo i18n_r('EDITPAGE_TITLE') . ' 403'; ?></a></li>
	<li id="sb_edit_system_404"><a href="<?php echo ($filename == 'edit-system' && $id == '404') ? '#' : 'edit-system.php?id=404'; ?>"<?php if ($filename == 'edit-system' && $id == '404') echo ' class="current"'; ?>><?php echo i18n_r('EDITPAGE_TITLE') . ' 404'; ?></a></li>
	<li id="sb_edit_system_503"><a href="<?php echo ($filename == 'edit-system' && $id == '503') ? '#' : 'edit-system.php?id=503'; ?>"<?php if ($filename == 'edit-system' && $id == '503') echo ' class="current"'; ?>><?php echo i18n_r('EDITPAGE_TITLE') . ' 503'; ?></a></li>
<!-- End System pages -->
	<?php exec_action('pages-sidebar'); ?>
</ul>

<?php if ($filename =='edit' || $filename == 'edit-system') { ?>
<p id="js_submit_line"></p>
<p id="pagechangednotify"></p>
<p id="autosavenotify"></p>
<?php } ?>