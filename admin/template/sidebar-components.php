<?php
/**
 * Sidebar Components Template
 *
 * @package GetSimple Extended
 */
?>
<ul class="snav">
	<li id="sb_components"><a href="components.php" <?php check_menu('components'); ?>><?php i18n('EDIT_COMPONENTS'); ?></a></li>
	<?php exec_action('theme-components'); ?>
</ul>
<p id="js_submit_line"></p>
