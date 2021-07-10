<?php
/**
 * Sidebar Settings Template
 *
 * @package GetSimple Extended
 */
?>
<ul class="snav">
<li id="sb_settings"><a href="settings.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_GEN_SETTINGS'));?>" <?php check_menu('settings'); ?>><?php i18n('SIDE_GEN_SETTINGS'); ?></a></li>
<li id="sb_user"><a href="user.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_USER_PROFILE'));?>" <?php check_menu('user'); ?>><?php i18n('SIDE_USER_PROFILE'); ?></a></li>
<?php exec_action("settings-sidebar"); ?>
</ul>

<?php if (in_array(get_filename_id(), array('settings', 'user'))) { ?>
<p id="js_submit_line"></p>
<?php } ?>