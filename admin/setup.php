<?php
/**
 * Setup
 *
 * Second step of installation (install.php). Sets up initial files & structure
 *
 * @package GetSimple Extended
 * @subpackage Installation
 */

# setup inclusions
$load['plugin'] = true;
if (isset($_POST['lang']) && trim($_POST['lang']) != '') $LANG = $_POST['lang'];
include('inc/common.php');

# default variables
$logsalt = defined('GSLOGINSALT') ? GSLOGINSALT : null;
$kill = ''; // fatal error kill submission reshow form
$status = '';
$err = null; // used for errors, show form alow resubmision
$message = null; // message to show user
$random = null;
$success = false; // success true show message if message
$fullpath = suggest_site_path();
$path_parts = suggest_site_path(true);

# if the form was submitted, continue
if (isset($_POST['submitted'])) {
	if ($_POST['sitename'] != '') {
		$SITENAME = htmlentities($_POST['sitename'], ENT_QUOTES, 'UTF-8');
	} else {
		$err .= i18n_r('WEBSITENAME_ERROR') . '<br />';
	}

	$urls = $_POST['siteurl'];
	if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $urls)) {
		$SITEURL = tsl($_POST['siteurl']);
	} else {
		$err .= i18n_r('WEBSITEURL_ERROR') .'<br />';
	}

	if($_POST['user'] != '') {
		$USR = strtolower($_POST['user']);
	} else {
		$err .= i18n_r('USERNAME_ERROR') . '<br />';
	}

	if (!check_email_address($_POST['email'])) {
		$err .= i18n_r('EMAIL_ERROR') . '<br />';
	} else {
		$EMAIL = $_POST['email'];
	}

	# if there were no errors, continue setting up the site
	if ($err == '') {
		# create new password
		$random = createRandomPassword();
		$PASSWD = passhash($random);

		# create user xml file
		$file = _id($USR).'.xml';
		createBak($file, GSUSERSPATH, GSBACKUSERSPATH);
		$xml = new SimpleXMLExtended('<item></item>');
		$xml->addChild('user', $USR);
		$xml->addChild('name')->addCData('');
		$xml->addChild('description')->addCData('');
		$xml->addChild('email')->addCData($EMAIL);
		$xml->addChild('password', $PASSWD);
		$xml->addChild('enableHTMLEditor', '1');
		$xml->addChild('enableCodeEditor', '1');
		$xml->addChild('timezone', $TIMEZONE);
		$xml->addChild('lang', $LANG);
		$xml->addChild('accessFrontMaintenance', '1');
		$xml->addAttribute('revisionNumber', '1');
		$xml->addAttribute('created',  date('r'));
		$xml->addAttribute('modified', date('r'));
		$xml->addAttribute('user', $USR);
		$xml->addAttribute('appName', $site_full_name);
		$xml->addAttribute('appVersion', $site_version_no);
		if (!XMLsave($xml, GSUSERSPATH . $file)) {
			$kill = i18n_r('CHMOD_ERROR');
		}

		# create password change trigger file
		$flagfile = GSUSERSPATH . _id($USR). '.xml.reset';
		copy(GSUSERSPATH . $file, $flagfile);

		# create new website.xml file
		$file = 'website.xml';
		$xmls = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><item></item>');
		$xmls->addChild('title')->addCData($SITENAME);
		$xmls->addChild('description')->addCData('');
		$xmls->addChild('url')->addCData($SITEURL);
		$xmls->addChild('theme', 'Innovation');
		$xmls->addChild('prettyurls', '');
		$xmls->addChild('permalink')->addCData('');
		$xmls->addChild('maintenance', '1');
		$xmls->addChild('lang')->addCData('');
		$xmls->addAttribute('revisionNumber', '1');
		$xmls->addAttribute('created', date('r'));
		$xmls->addAttribute('modified', date('r'));
		$xmls->addAttribute('user', $USR);
		$xmls->addAttribute('appName', $site_full_name);
		$xmls->addAttribute('appVersion', $site_version_no);
		if (!XMLsave($xmls, GSDATAOTHERPATH . $file)) {
			$kill = i18n_r('CHMOD_ERROR');
		}

		# create default index.xml page
		$init = GSDATAPAGESPATH . 'index.xml';
		$temp = GSADMININCPATH . 'tmp/tmp-index.xml';
		if (!file_exists($init)) {
			copy($temp, $init);
			$xml = simplexml_load_file($init);
			$xml->pubDate = date('r');
			$xml->creDate = date('r');
			$xml->author = $USR;
			$xml->publisher = $USR;
			$xml->addAttribute('appName', $site_full_name);
			$xml->addAttribute('appVersion', $site_version_no);
			$xml->asXML($init);
		}

		# create default components.xml page
		$init = GSDATAOTHERPATH . 'components.xml';
		$temp = GSADMININCPATH . 'tmp/tmp-components.xml';
		if (!file_exists($init)) {
			copy($temp, $init);
			$xml = simplexml_load_file($init);
			$xml->addAttribute('appName', $site_full_name);
			$xml->addAttribute('appVersion', $site_version_no);
			$xml->addAttribute('revisionNumber', '1');
			$xml->addAttribute('created', date('r'));
			$xml->addAttribute('modified', date('r'));
			$xml->addAttribute('user', $USR);
			$xml->asXML($init);
		}

		# create default 503.xml page
		$init = GSDATAOTHERPATH.'503.xml';
		$temp = GSADMININCPATH.'tmp/tmp-503.xml';
		if (!file_exists($init)) {
			copy($temp, $init);
			$xml = simplexml_load_file($init);
			$xml->pubDate = date('r');
			$xml->creDate = date('r');
			$xml->author = $USR;
			$xml->publisher = $USR;
			$xml->addAttribute('appName', $site_full_name);
			$xml->addAttribute('appVersion', $site_version_no);
			$xml->asXML($init);
		}

		# create default 403.xml page
		$init = GSDATAOTHERPATH.'403.xml';
		$temp = GSADMININCPATH.'tmp/tmp-403.xml';
		if (!file_exists($init)) {
			copy($temp, $init);
			$xml = simplexml_load_file($init);
			$xml->pubDate = date('r');
			$xml->creDate = date('r');
			$xml->author = $USR;
			$xml->publisher = $USR;
			$xml->addAttribute('appName', $site_full_name);
			$xml->addAttribute('appVersion', $site_version_no);
			$xml->asXML($init);
		}

		# create default 404.xml page
		$init = GSDATAOTHERPATH.'404.xml';
		$temp = GSADMININCPATH.'tmp/tmp-404.xml';
		if (!file_exists($init)) {
			copy($temp, $init);
			$xml = simplexml_load_file($init);
			$xml->pubDate = date('r');
			$xml->creDate = date('r');
			$xml->author = $USR;
			$xml->publisher = $USR;
			$xml->addAttribute('appName', $site_full_name);
			$xml->addAttribute('appVersion', $site_version_no);
			$xml->asXML($init);
		}

		# create root .htaccess file
		if (!function_exists('apache_get_modules') or in_arrayi('mod_rewrite', apache_get_modules())) {
			$temp = GSROOTPATH . 'temp.htaccess';
			$init = GSROOTPATH . '.htaccess';

			if (file_exists($temp)) {
				$temp_data = file_get_contents(GSROOTPATH . 'temp.htaccess');
				$temp_data = str_replace('**REPLACE**', tsl($path_parts), $temp_data);
				$fp = fopen($init, 'w');
				fwrite($fp, $temp_data);
				fclose($fp);
				if (!file_exists($init)) {
					$err .= sprintf(i18n_r('ROOT_HTACCESS_ERROR'), 'temp.htaccess', '**REPLACE**', tsl($path_parts)) . '<br />';
				} else if (file_exists($temp)) {
					unlink($temp);
				}
			}
		}

		# create gsconfig.php if it doesn't exist yet.
		$init = GSROOTPATH . 'gsconfig.php';
		$temp = GSROOTPATH . 'temp.gsconfig.php';
		if (file_exists($init)) {
			if (file_exists($temp)) unlink($temp);
			if (file_exists($temp)) $err .= sprintf(i18n_r('REMOVE_TEMPCONFIG_ERROR'), 'temp.gsconfig.php') . '<br />';
		} else {
			rename($temp, $init);
			if (!file_exists($init)) $err .= sprintf(i18n_r('MOVE_TEMPCONFIG_ERROR'), 'temp.gsconfig.php', 'gsconfig.php') . '<br />';
		}
		
		# send email to new administrator
		$subject  = $site_full_name . ' ' . i18n_r('EMAIL_COMPLETE');
		$message .= '<p>' . i18n_r('EMAIL_USERNAME') . ': <strong>' . stripslashes($_POST['user']) . '</strong>';
		$message .= '<br>' . i18n_r('EMAIL_PASSWORD') . ': <strong>' . $random . '</strong>';
		$message .= '<br>' . i18n_r('EMAIL_LOGIN') . ': <a href="' . $SITEURL . $GSADMIN . '/">' . $SITEURL.$GSADMIN . '/</a></p>';
		$message .= '<p><em>'. i18n_r('EMAIL_THANKYOU') . ' ' .$site_full_name . '!</em></p>';
		$status   = sendmail($EMAIL, $subject, $message);
		# activate default plugins
		change_plugin('InnovationPlugin.php', true);

		# set the login cookie, then redirect user to secure panel
		create_cookie();
		$success = true;
	}
}

get_template('header', $site_full_name . ' &raquo; ' . i18n_r('INSTALLATION'));

?>
<div class="wrapper">
<?php
	echo '<div id="notifications">';
	# display error or success messages
	if ($status == 'success') {
		create_notification(i18n_r('NOTE_REGISTRATION') .' ' . filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS), 'updated');
	} elseif ($status == 'error') {
		create_notification(i18n_r('NOTE_REGERROR'), 'error');
	}
	if ($kill != '') {
		$success = false;
		create_notification($kill, 'error');
	}
	if ($err != '') {
		// $success = false;
		create_notification($err, 'error');
	}
	if ($random != '') {
		create_notification(i18n_r('NOTE_USERNAME') . ' <strong>' . filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS) . '</strong> ' . i18n_r('NOTE_PASSWORD') . ' <strong>' . $random . '</strong> &nbsp&raquo;&nbsp; <a href="support.php?updated=2">' . i18n_r('EMAIL_LOGIN') . '</a>', 'updated');
		$_POST = null;
	}
	echo '</div>';
?>
	<div id="maincontent">
		<?php if (!$success) { ?>
		<div class="main">
			<h3><?php echo $site_full_name . ' ' . i18n_r('INSTALLATION'); ?></h3>
			<form action="<?php myself(); ?>" method="post" accept-charset="utf-8">
				<input name="siteurl" type="hidden" value="<?php echo $fullpath; ?>">
				<input name="lang" type="hidden" value="<?php echo $LANG; ?>">
				<p><label for="sitename"><?php i18n('LABEL_WEBSITE'); ?>:</label><input id="sitename" name="sitename" type="text" value="<?php echo filter_input(INPUT_POST, 'sitename', FILTER_SANITIZE_SPECIAL_CHARS); ?>"></p>
				<p><label for="user"><?php i18n('LABEL_USERNAME'); ?>:</label><input name="user" id="user" type="text" value="<?php echo filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS); ?>"></p>
				<p><label for="email"><?php i18n('LABEL_EMAIL'); ?>:</label><input name="email" id="email" type="email" value="<?php echo filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS); ?>"></p>
				<p><input class="submit" type="submit" name="submitted" value="<?php i18n('LABEL_INSTALL'); ?>"></p>
			</form>
		</div>
</div>
<?php get_template('footer'); ?>

<?php } ?>
