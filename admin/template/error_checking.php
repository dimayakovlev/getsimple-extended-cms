<?php if (!defined('IN_GS')) die('you cannot load this page directly.');
/**
 * Error Checking
 *
 * Displays error, success and other notifications messages
 *
 * @package GetSimple Extended
 *
 * You can pass $update(global) directly if not using a redirrect and querystring
 */

echo '<div id="notifications">';

if (file_exists(GSUSERSPATH . _id($USR) . '.xml.reset') && get_filename_id() != 'index' && get_filename_id() != 'resetpassword') {
	create_notification(i18n_r('ER_PWD_CHANGE'), 'error', false);
}

if ((!defined('GSNOAPACHECHECK') || GSNOAPACHECHECK == false) and !server_is_apache()) {
	create_notification('<strong>' . i18n_r('WARNING') . ':</strong> <a href="health-check.php">' . i18n_r('SERVER_SETUP') . ' non-Apache</a>', 'error', false);
}

if ((string)$dataw->maintenance == '1') {
	create_notification('<strong>' . i18n_r('WARNING') . ':</strong> ' . i18n_r('MAINTENANCE_WARNING'), 'error', false);
}

if (get_filename_id() == 'components' && getDef('GSCOMPONENTACTION', true)) {
	create_notification(i18n_r('ER_COMPONENT_ACTION'), 'info', false);
}

if (!isset($update)) $update = '';
$err = '';
$restored = '';
if (isset($_GET['upd'])) $update = filter_input(INPUT_GET, 'upd', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($_GET['success'])) $success = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($_GET['error'])) $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($_GET['err'])) $err = filter_input(INPUT_GET, 'err', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($_GET['id'])) $errid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
if (isset($_GET['updated']) && $_GET['updated'] == '1') $success = i18n_r('SITE_UPDATED');

switch ($update) {
	case 'bak-success':
		create_notification(sprintf(i18n_r('ER_BAKUP_DELETED'), $errid), 'updated', false);
		break;
	case 'bak-err':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> '. i18n_r('ER_REQ_PROC_FAIL'), 'error', false);
		break;
	case 'edit-success':
		if ($ptype == 'edit') {
			create_notification(sprintf(i18n_r('ER_YOUR_CHANGES'), $id) . '. <a href="backup-edit.php?p=restore&id=' . $id . '&nonce=' . get_nonce("restore", "backup-edit.php") . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		} elseif ($ptype == 'restore') {
			create_notification(sprintf(i18n_r('ER_HASBEEN_REST'), $id), 'updated', false);
		} elseif ($ptype == 'delete') {
			create_notification(sprintf(i18n_r('ER_HASBEEN_DEL'), $errid) . '. <a href="backup-edit.php?p=restore&id=' . $errid . '&nonce=' . get_nonce("restore", "backup-edit.php") . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		} elseif ($ptype == 'new') {
			create_notification(sprintf(i18n_r('ER_YOUR_CHANGES'), $id) . '. <a href="deletefile.php?id=' . $id . '&nonce='.get_nonce("delete", "deletefile.php") . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		}
		break;
	case 'clone-success':
		create_notification(sprintf(i18n_r('CLONE_SUCCESS'), '<a href="edit.php?id=' . $errid . '">' . $errid . '</a>'), 'updated', false);
		break;
	case 'edit-index':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_CANNOT_INDEX'), 'error', false);
		break;
	case 'edit-error':
		create_notification('<strong>' . i18n_r('ERROR').':</strong> ' . var_out($ptype), 'error', false);
		break;
	case 'pwd-success':
		create_notification(i18n_r('ER_NEW_PWD_SENT') . '. <a href="index.php">' . i18n_r('LOGIN') . '</a>', 'updated', false);
		break;
	case 'pwd-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_SENDMAIL_ERR'), 'error', false);
		break;
	case 'del-success':
		create_notification(i18n_r('ER_FILE_DEL_SUC') . ': <strong>' . $errid . '</strong>', 'updated', false);
		break;
	case 'flushcache-success':
		create_notification(i18n_r('FLUSHCACHE-SUCCESS'), 'updated', false);
		break;
	case 'del-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_PROBLEM_DEL'), 'error', false);
		break;
	case 'comp-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> '. i18n_r('ER_COMPONENT_SAVE_ERROR'), 'error', false);
		break;
	case 'comp-success':
		create_notification(i18n_r('ER_COMPONENT_SAVE') . '. <a href="components.php?undo&nonce=' . get_nonce("undo") . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		break;
	case 'comp-restored':
		create_notification(i18n_r('ER_COMPONENT_REST') . '. <a href="components.php?undo&nonce=' . get_nonce("undo") . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		break;
	case 'menu-success':
		create_notification(i18n_r('MENU_MANAGER_SUCCESS'), 'updated', false);
		break;
	case 'menu-error':
		create_notification(i18n_r('MENU_MANAGER_ERROR'), 'error', false);
		break;
	case 'settings-success':
		create_notification(i18n_r('ER_SETTINGS_UPD') . '. <a href="changedata.php?action=undo&nonce=' . get_nonce('undo', 'settings.php') . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		break;
	case 'settings-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_SETTINGS_SAVE_ERROR'), 'error', false);
		break;
	case 'settings-restored':
		create_notification(i18n_r('ER_OLD_RESTORED'), 'updated', false);
		break;
	case 'settings-restored-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_OLD_RESTORED_ERROR'), 'error', false);
		break;
	case 'user-success':
		create_notification(i18n_r('ER_USER_UPD') . '. <a href="changedata.php?action=undo&nonce=' . get_nonce('undo', 'user.php') . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		break;
	case 'user-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_USER_SAVE_ERROR'), 'error', false);
		break;
	case 'user-restored':
		create_notification(i18n_r('ER_USER_RESTORED'), 'updated', false);
		break;
	case 'user-restored-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_USER_RESTORED_ERROR'), 'error', false);
		break;
	case 'user-password-mismatch':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('ER_USER_PASSWORD_MISMATCH'), 'error', false);
		break;
	case 'theme-success':
		create_notificaton(i18n_r('THEME_CHANGED'), 'updated', false);
		break;
	case 'theme-error':
		create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . i18n_r('THEME_CHANGED_ERROR'), 'error', false);
		break;
	/**/
	default:
		if (isset( $error )) create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . $error, 'error', false);
		else if (isset($_GET['rest']) && $_GET['rest'] == 'true') create_notification(i18n_r('ER_OLD_RESTORED') . '. <a href="support.php?undo&nonce=' . get_nonce("undo", "support.php") . '">' . i18n_r('UNDO') . '</a>', 'updated', false);
		elseif (isset($_GET['cancel'])) create_notification(i18n_r('ER_CANCELLED_FAIL'), 'error', false);
		elseif (isset($error)) create_notification($error, 'error', false);
		elseif (!empty($err)) create_notification('<strong>' . i18n_r('ERROR') . ':</strong> ' . $err, 'error', false);
		elseif (isset($success)) create_notification($success, 'updated', false);
		break;
	/**/
}

echo '</div>';