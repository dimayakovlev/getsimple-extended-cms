<?php
/**
 * User Settings
 *
 * Displays and changes user profile settings
 *
 * @package GetSimple Extended
 * @subpackage Settings
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

# variable settings
login_cookie_check();

$lang_array = getFiles(GSLANGPATH);
if (count($lang_array) > 0) {
    sort($lang_array);
    $langs = '';
    foreach ($lang_array as $lang_file) {
        $lang = basename($lang_file, '.php');
        $langs .= '<option value="' . $lang . '"'. ($lang == $LANG ? ' selected' : '') .'>' . $lang . '</option>';
    }
} else {
    $langs = '<option value="" selected="selected">-- ' . i18n_r('NONE') . ' --</option>';
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('USER_SETTINGS'));

include('template/include-nav.php');
?>
<div class="bodycontent">
    <div id="maincontent">
        <div class="main">
            <h3><?php i18n('USER_SETTINGS');?></h3>
            <form class="largeform" action="changedata.php" method="post" accept-charset="utf-8">
                <input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce('save', 'user.php'); ?>">
                <input type="hidden" name="created" value="<?php echo $datau->attributes()->created; ?>">
                <input id="revision-number" name="revision-number" type="hidden" value="<?php echo (string)$datau->attributes()->revisionNumber ?: '0'; ?>">
                <input id="action" name="action" type="hidden" value="save">
                <p class="leftsec">
                    <label for="user"><?php i18n('LABEL_USERNAME');?>:</label><input name="user" type="text" readonly value="<?php echo $USR; ?>">
                </p>
                <p class="rightsec">
                    <label for="email"><?php i18n('LABEL_EMAIL');?>:</label><input name="email" type="email" value="<?php echo $datau->email; ?>">
                    <?php if (!check_email_address($datau->email)) echo '<span class="attention">' . i18n_r('WARN_EMAILINVALID') . '</span>'; ?>
                </p>
                <p class="widesec">
                    <label for="name"><?php i18n('LABEL_DISPNAME');?>:</label>
                    <span class="hint"><?php i18n('DISPLAY_NAME');?></span>
                    <input name="name" type="text" value="<?php echo $datau->name; ?>">
                </p>
                <p class="widesec">
                    <label for="description"><?php i18n('LABEL_USERDESCRIPTION'); ?>:</label>
                    <span class="hint"><?php i18n('DISPLAY_USERDESCRIPTION'); ?></span>
                    <textarea name="description"><?php echo $datau->description; ?></textarea>
                </p>
                <p class="leftsec">
                    <label for="timezone" ><?php i18n('LOCAL_TIMEZONE');?>:</label>
                    <select name="timezone"><?php if ($TIMEZONE == '') { echo '<option value="" selected="selected">-- ' . i18n_r('NONE') . ' --</option>'; } else { echo '<option selected="selected"  value="' . $TIMEZONE . '">' . $TIMEZONE .'</option>'; } ?>
                    <?php include('inc/timezone_options.txt'); ?>
                    </select>
                </p>
                <p class="rightsec">
                    <label for="lang"><?php i18n('LANGUAGE');?>: <span class="right"><a href="https://github.com/dimayakovlev/getsimple-extended-cms/wiki/Languages" target="_blank" ><?php i18n('MORE');?></a></span></label>
                    <select name="lang"><?php echo $langs; ?></select>
                </p>
                <p class="widesec inline"><input name="enable-html-editor" type="checkbox" value="1"<?php if ($datau->enableHTMLEditor == '1') { echo ' checked '; } ?>> <label for="enable-html-editor"><?php i18n('ENABLE_HTML_ED');?></label></p>
                <p class="widesec inline"><input name="enable-code-editor" type="checkbox" value="1"<?php if ($datau->enableCodeEditor == '1') { echo ' checked '; } ?>> <label for="enable-code-editor"><?php i18n('ENABLE_CODE_ED');?></label></p>
                <p class="widesec inline"><input name="access-front-maintenance" type="checkbox" value="1"<?php if ($datau->accessFrontMaintenance == 1) { echo ' checked '; } ?>> <label for="access-front-maintenance"><?php i18n('ALLOW_ACCESS_IN_MAINTENANCE');?></label></p>
                <?php exec_action('settings-user-extras'); ?>
                <p class="widesec"><?php i18n('ONLY_NEW_PASSWORD'); ?>:</p>
                <p class="leftsec">
                    <label for="password"><?php i18n('NEW_PASSWORD');?>:</label><input autocomplete="off" id="password" name="password" type="password" value="">
                </p>
                <p class="rightsec">
                    <label for="password-confirm"><?php i18n('CONFIRM_PASSWORD');?>:</label><input autocomplete="off" id="password-confirm" name="password-confirm" type="password" value="">
                </p>
                <p id="submit_line">
                    <span><input class="submit" type="submit" name="submitted" value="<?php i18n('BTN_SAVESETTINGS');?>"></span> <?php i18n('OR'); ?> <a class="cancel" href="settings.php?cancel"><?php i18n('CANCEL'); ?></a>
                </p>
                <p class="backuplink">
                <?php
                    if ((string)$datau->attributes()->modified) {
                        echo sprintf(i18n_r('LAST_SAVED'), '<em>' . ((string)$datau->attributes()->user ?: '-') . '</em>', lngDate((string)$datau->attributes()->modified));
                    }
                ?>
                </p>
            </form>
        </div>
    </div>
    <div id="sidebar"><?php include('template/sidebar-settings.php'); ?></div>
</div>
<?php get_template('footer'); ?>
