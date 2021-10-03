<?php
/**
 * Sidebar Pages Template
 *
 * @package GetSimple
 */
?>
<ul class="snav">
	<li id="sb_pages"><a href="pages.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_VIEW_PAGES'));?>" <?php check_menu('pages'); ?>><?php i18n('SIDE_VIEW_PAGES'); ?></a></li>
	<li id="sb_newpage"><a href="edit.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_CREATE_NEW'));?>" <?php if (!isset($_GET['id']) && (get_filename_id() === 'edit') && filter_input(INPUT_GET, 'pageType', FILTER_SANITIZE_NUMBER_INT) != 1) { echo 'class="current"'; } ?>><?php i18n('SIDE_CREATE_NEW'); ?></a></li>
	<li id="sb_newpagedyn"><a href="edit.php?pageType=1" accesskey="<?php echo find_accesskey(i18n_r('SIDE_CREATE_NEW_DYNAMIC'));?>" <?php if (!isset($_GET['id']) && (get_filename_id() === 'edit') && filter_input(INPUT_GET, 'pageType', FILTER_SANITIZE_NUMBER_INT) == 1) { echo 'class="current"'; } ?>><?php i18n('SIDE_CREATE_NEW_DYNAMIC'); ?></a></li>
	<?php if ((isset($_GET['id']) && $_GET['id'] != '') && (get_filename_id() === 'edit')) { ?><li id="sb_pageedit" ><a href="#" class="current"><?php i18n('EDITPAGE_TITLE'); ?></a></li><?php } ?>
	<li id="sb_menumanager"><a href="menu-manager.php" accesskey="<?php echo find_accesskey(i18n_r('MENU_MANAGER'));?>" <?php check_menu('menu-manager'); ?>><?php i18n('MENU_MANAGER'); ?></a></li>
	<?php exec_action('pages-sidebar'); ?>
</ul>

<?php if (get_filename_id() === 'edit') { ?>
<p id="js_submit_line"></p>
<p id="pagechangednotify"></p>
<p id="autosavenotify"></p>
<?php } ?>