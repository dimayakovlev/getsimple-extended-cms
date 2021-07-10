<?php 
/**
 * Save submitted data
 *
 * This is the action page to save submitted data
 *
 * @package GetSimple Extended
 */

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('inc/common.php');

if (!isset($_SERVER['HTTP_REFERER'])) {
	die('No Referer');
}

// check referer domain
/*
if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != parse_url($SITEURL, PHP_URL_HOST)) {
	die('Invalid Referer Domain');
}
*/

$referer = basename(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));

$actions = array(
	'edit.php' => array('save'), // Save page
	'menu-manager.php' => array('save'), // Save menu order
	'components.php' => array('save'), // Save components
	'settings.php' => array('save', 'undo', 'flushcache'), // Save, undo website settings, flush cache
	'user.php' => array('save', 'undo'), // Save, undo user settings
	'theme.php' => array('save'), // Save theme
);

// check referer page
if (!isset($actions[$referer])) {
	die('Wrong Referer Page');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = filter_input(INPUT_POST, 'action');
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$action = filter_input(INPUT_GET, 'action');
} else {
	$action = '';
}

// check action
if ($action == '' || !in_array($action, $actions[$referer])) {
	die('Wrong action');
}

// check for csrf
// Create nonce in forms: get_nonce('action-name', pathinfo(__FILE__, PATHINFO_BASENAME));
if (!defined('GSNOCSRF') || GSNOCSRF == false) {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nonce = filter_input(INPUT_POST, 'nonce');
	} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$nonce = filter_input(INPUT_GET, 'nonce');
	} else {
		$nonce = '';
	}
	if ($nonce == '' || !check_nonce($nonce, $action, $referer)) {
		die('CSRF detected');
	}
}

login_cookie_check();


if ($referer == 'edit.php' && $action == 'save') {
	// Save page data
	$existingurl = isset($_POST['existing-url']) ? $_POST['existing-url'] : null;

	if (trim($_POST['post-title']) == '') {
		redirect($referer . '?upd=edit-error&type=' . urlencode(i18n_r('CANNOT_SAVE_EMPTY')));
	} else {
		$autoSaveDraft = false; // auto save to autosave drafts
		$url = '';

		// is a slug provided?
		if ($_POST['post-id']) {
			$url = trim($_POST['post-id']);
			if (isset($i18n['TRANSLITERATION']) && is_array($i18n['TRANSLITERATION']) && count($i18n['TRANSLITERATION']) > 0) {
				$url = str_replace(array_keys($i18n['TRANSLITERATION']), array_values($i18n['TRANSLITERATION']), $url);
			}
			$url = to7bit($url, 'UTF-8');
			$url = clean_url($url); //old way
		} else {
			if ($_POST['post-title']) { 
				$url = trim($_POST['post-title']);
				if (isset($i18n['TRANSLITERATION']) && is_array($i18n['TRANSLITERATION']) && count($i18n['TRANSLITERATION']) > 0) {
					$url = str_replace(array_keys($i18n['TRANSLITERATION']), array_values($i18n['TRANSLITERATION']), $url);
				}
				$url = to7bit($url, 'UTF-8');
				$url = clean_url($url); //old way
			} else {
				$url = 'temp';
			}
		}

		//check again to see if the URL is empty
		if (trim($url) == '') $url = 'temp';

		// was the slug changed on an existing page?
		if (isset($existingurl)) {
			if ($_POST['post-id'] != $existingurl) {
				// dont change the index page's slug
				if ($existingurl == 'index') {
					$url = $existingurl;
					redirect($referer . '?id=' . urlencode($existingurl) . '&upd=edit-index&type=edit');
				} else {
					exec_action('changedata-updateslug');
					updateSlugs($existingurl);
					$file = GSDATAPAGESPATH . $url . '.xml';
					$existing = GSDATAPAGESPATH . $existingurl . '.xml';
					$bakfile = GSBACKUPSPATH . 'pages/'. $existingurl . '.bak.xml';
					copy($existing, $bakfile);
					unlink($existing);
				} 
			} 
		}

		$file = GSDATAPAGESPATH . $url . '.xml';

		// If saving a new file do not overwrite existing, get next incremental filename, file-count.xml
		// @todo this is a mess, new file existing file should all be determined at beginning of block and defined
		if ((file_exists($file) && $url != $existingurl) ||  in_array($url, $reservedSlugs)) {
			$count = 1;
			$file = GSDATAPAGESPATH . $url . '-' . $count . '.xml';
			while (file_exists($file)) {
				$count++;
				$file = GSDATAPAGESPATH . $url . '-' . $count. '.xml';
			}
			$url = $url . '-' . $count;
		}

		// if we are editing an existing page, create a backup
		if (file_exists($file)) {
			copy($file, GSBACKUPSPATH . 'pages/' . $url . '.bak.xml');
		}

		$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><item></item>');
		$xml->addChild('pubDate', date('r'));
		$xml->addChild('creDate', filter_input(INPUT_POST, 'post-creDate', FILTER_SANITIZE_STRING) ?: date('r'));
		$xml->addChild('title')->addCData(filter_var(trim(xss_clean(filter_input(INPUT_POST, 'post-title'))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('url', $url);
		$xml->addChild('meta')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-metak')))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('metad')->addCData(filter_var(trim(xss_clean(filter_input(INPUT_POST, 'post-metad'))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('menu')->addCData(filter_var(trim(xss_clean(filter_input(INPUT_POST, 'post-menu'))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('menuOrder', filter_input(INPUT_POST, 'post-menu-order', FILTER_SANITIZE_NUMBER_INT) ?: '0');
		$xml->addChild('menuStatus', filter_input(INPUT_POST, 'post-menu-enable', FILTER_SANITIZE_STRING));
		$xml->addChild('template', filter_input(INPUT_POST, 'post-template', FILTER_SANITIZE_STRING));
		$xml->addChild('parent', filter_input(INPUT_POST, 'post-parent', FILTER_SANITIZE_STRING));
		$xml->addChild('content')->addCData(filter_input(INPUT_POST, 'post-content', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('component')->addCData(filter_input(INPUT_POST, 'post-component', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('componentEnabled', (string)filter_input(INPUT_POST, 'post-component-enable', FILTER_VALIDATE_BOOLEAN));
		$xml->addChild('componentContent', (string)filter_input(INPUT_POST, 'post-component-content', FILTER_VALIDATE_BOOLEAN));
		$xml->addChild('private', filter_input(INPUT_POST, 'post-private', FILTER_SANITIZE_STRING));
		$xml->addChild('author', filter_input(INPUT_POST, 'post-author', FILTER_SANITIZE_STRING) ?: $USR);
		$xml->addChild('publisher', $USR);
		$xml->addChild('lang')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-lang', FILTER_SANITIZE_STRING)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('permalink')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-permalink', FILTER_SANITIZE_URL)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('image')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-image', FILTER_SANITIZE_URL)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addAttribute('autoOpenMetadata', (string)filter_input(INPUT_POST, 'auto-open-metadata', FILTER_VALIDATE_BOOLEAN));
		$xml->addAttribute('autoOpenComponent', (string)filter_input(INPUT_POST, 'auto-open-component', FILTER_VALIDATE_BOOLEAN));
		$xml->addAttribute('disableHTMLEditor', (string)filter_input(INPUT_POST, 'disable-html-editor', FILTER_VALIDATE_BOOLEAN));
		$xml->addAttribute('disableCodeEditor', (string)filter_input(INPUT_POST, 'disable-code-editor', FILTER_VALIDATE_BOOLEAN));
		$xml->addAttribute('revisionNumber', (int)filter_input(INPUT_POST, 'revision-number', FILTER_SANITIZE_NUMBER_INT) + 1);

		exec_action('changedata-save');
		if (isset($_POST['autosave']) && $_POST['autosave'] == 'true' && $autoSaveDraft == true) {
			$status = XMLsave($xml, GSAUTOSAVEPATH . $url);
		} else {
			$status = XMLsave($xml, $file);
			if ($status) update_website_data();
		}

		//ending actions
		exec_action('changedata-aftersave');
		generate_sitemap();

		// redirect user back to edit page 
		if (isset($_POST['autosave']) && $_POST['autosave'] == 'true') {
			echo $status ? 'OK' : 'ERROR';
		} else {
			if (!$status) redirect($referer . '?id=' . $url . '&upd=edit-error&type=edit');
			$redirect_url = filter_input(INPUT_POST, 'redirectto', FILTER_SANITIZE_URL) ?: 'edit.php';
			if (!isset($existingurl)) {
				redirect($redirect_url . '?id=' . $url . '&upd=edit-success&type=new');
			} elseif ($url == $existingurl) {
				// redirect save new file
				redirect($redirect_url . '?id=' . $url . '&upd=edit-success&type=edit');
			} else {
				// redirect new slug, undo for old slug
				redirect($redirect_url . '?id=' . $url . '&old=' . $existingurl . '&upd=edit-success&type=edit');
			}
		}
	}
} elseif ($referer == 'menu-manager.php' && $action == 'save') {
	// Save page priority order
	if (isset($_POST['menuOrder'])) {
		$menuOrder = explode(',', $_POST['menuOrder']);
		$priority = 0;
		foreach ($menuOrder as $slug) {
			$file = GSDATAPAGESPATH . $slug . '.xml';
			if (file_exists($file)) {
				$data = getXML($file, 0);
				if ($priority != (int)$data->menuOrder) {
					$data->menuOrder = $priority;
					$data->pubDate = date('r');
					$data->publisher = $USR;
					$data->attributes()->revisionNumber = (int)$data->attributes()->revisionNumber + 1;
					copy($file, GSBACKUPSPATH . 'pages/' . $slug. '.bak.xml');
					XMLsave($data, $file);
				}
			}
			$priority++;
		}
		create_pagesxml('true');
		update_website_data();
		redirect($referer . '?upd=menu-success');
	} else {
		redirect($referer . '?upd=menu-error');
	}
} elseif ($referer == 'components.php' && $action == 'save') {
	// Save components
	$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><components></components>');
	$xml->addAttribute('created', filter_input(INPUT_POST, 'created') ?: date('r'));
	$xml->addAttribute('modified', date('r'));
	$xml->addAttribute('user', $USR);
	$components = array();
	if (isset($_POST['components'])) {
		foreach($_POST['components'] as $component) {
			if (!isset($component['title']) || trim($component['title']) == '') {
				$component['title'] = uniqid('Component ');
			} else {
				$component['title'] = safe_slash_html(trim($component['title']));
			}
			if (!isset($component['slug']) || trim($component['slug']) == '') {
				$slug = clean_url(to7bit(trim($component['title'])), 'UTF-8');
				if ($slug) {
					$component['slug'] = $slug;
				} else {
					$component['slug'] = uniqid('component-');
				}
			}
			if (isset($component['value'])) {
				$component['value'] = safe_slash_html($component['value']);
			} else {
				$component['value'] = '';
			}
			$component['enabled'] = isset($component['enabled']) ? $component['enabled'] : '';
			$components[] = $component;
		}
	}

	if ($components) {
		$components = subval_sort($components, 'title');
		foreach ($components as $component) {
			$item = $xml->addChild('component');
			$item->addChild('title')->addCData($component['title']);
			$item->addChild('slug', $component['slug']);
			$item->addChild('enabled', $component['enabled']);
			$item->addChild('value')->addCData($component['value']);
		}
	}
	$file = 'components.xml';
	createBak($file, GSDATAOTHERPATH, GSBACKUPSPATH . 'other/');

	exec_action('component-save');
	if (XMLsave($xml, GSDATAOTHERPATH . $file)) {
		redirect($referer . '?upd=comp-success');
	} else {
		redirect($referer . '?upd=comp-error');
	}
} elseif ($referer == 'settings.php' && $action == 'save') {
	// Save website settings
	$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><item></item>');
	$xml->addChild('title')->addCData(filter_var(trim(xss_clean(filter_input(INPUT_POST, 'title'))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$xml->addChild('description')->addCData(filter_var(trim(xss_clean(filter_input(INPUT_POST, 'description'))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$xml->addChild('url')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$xml->addChild('lang')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_STRING)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$xml->addChild('prettyurls', (string)filter_input(INPUT_POST, 'prettyurls', FILTER_VALIDATE_BOOLEAN));
	$xml->addChild('permalink')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'permalink', FILTER_SANITIZE_URL)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$xml->addChild('theme', filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_STRING));
	$xml->addChild('maintenance', (string)filter_input(INPUT_POST, 'maintenance', FILTER_VALIDATE_BOOLEAN));
	$xml->addAttribute('revisionNumber', (int)filter_input(INPUT_POST, 'revision-number', FILTER_SANITIZE_NUMBER_INT) + 1);
	$xml->addAttribute('created', filter_input(INPUT_POST, 'created') ?: date('r'));
	$xml->addAttribute('modified', date('r'));
	$xml->addAttribute('user', $USR);
	$file = 'website.xml';
	createBak($file, GSDATAOTHERPATH, GSBACKUPSPATH . 'other/');
	exec_action('settings-website');
	redirect($referer . '?upd=' . (XMLsave($xml, GSDATAOTHERPATH . $file) ? 'settings-success' : 'settings-error'));
} elseif ($referer == 'settings.php' && $action == 'undo') {
	// Undo website settings
	if (undo('website.xml', GSDATAOTHERPATH, GSBACKUPSPATH . 'other/')) {
		generate_sitemap();
		redirect($referer . '?upd=settings-restored');
	} else {
		redirect($referer . '?upd=settings-restored-error');
	}
} elseif ($referer == 'settings.php' && $action == 'flushcache') {
	// Flush cache
	delete_cache();
	redirect($referer . '?upd=flushcache-success');
} elseif ($referer == 'theme.php' && $action == 'save') {
	// Change website theme
	$theme = filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_STRING);
	if (!$theme) die('Wrong data submitted');
	$file = 'website.xml';
	if (!file_exists(GSDATAOTHERPATH . $file)) die('Website data file not exists!');
	$xml = getXML(GSDATAOTHERPATH . $file, 0);
	$xml->theme = $theme;
	$xml->attributes()->revisionNumber = (int)$xml->attributes()->revisionNumber + 1;
	$xml->attributes()->modified = date('r');
	$xml->attributes()->user = $USR;
	createBak($file, GSDATAOTHERPATH, GSBACKUPSPATH . 'other/');
	redirect($referer . '?upd=' . (XMLsave($xml, GSDATAOTHERPATH . $file) ? 'theme-success' : 'theme-error'));
} elseif ($referer == 'user.php' && $action == 'save') {
	// Change user settings
	$user = filter_input(INPUT_POST, 'user', FILTER_DEFAULT);
	$file = $user . '.xml';
	if (!file_exists(GSUSERSPATH . $file)) die('User ' . $user . ' does not exist!');
	$password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
	$password2 = filter_input(INPUT_POST, 'password-confirm', FILTER_DEFAULT);
	if ($password != '' && $password != $password2) redirect($referer . '?upd=user-password-mismatch');
	$xml_old = getXML(GSUSERSPATH . $file);
	$password = ($password != '' && $password == $password2) ? passhash($password) : (string)$xml_old->password;
	$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><item></item>');
	$xml->addChild('user', $user);
	$xml->addChild('name')->addCData(filter_var(trim(xss_clean(filter_input(INPUT_POST, 'name'))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$xml->addChild('description')->addCData(filter_var(trim(xss_clean(filter_input(INPUT_POST, 'description'))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
	$xml->addChild('email')->addCData(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
	$xml->addChild('password', $password);
	$xml->addChild('timezone', filter_input(INPUT_POST, 'timezone', FILTER_SANITIZE_STRING));
	$xml->addChild('lang', filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_STRING));
	$xml->addChild('enableHTMLEditor', (string)filter_input(INPUT_POST, 'enable-html-editor', FILTER_VALIDATE_BOOLEAN));
	$xml->addChild('enableCodeEditor', (string)filter_input(INPUT_POST, 'enable-code-editor', FILTER_VALIDATE_BOOLEAN));
	$xml->addChild('accessFrontMaintenance', (string)filter_input(INPUT_POST, 'access-front-maintenance', FILTER_VALIDATE_BOOLEAN));
	$xml->addAttribute('revisionNumber', (int)$xml_old->attributes()->revisionNumber + 1);
	$xml->addAttribute('created', (string)$xml_old->attributes()->created ?: date('r'));
	$xml->addAttribute('modified', date('r'));
	$xml->addAttribute('user', $USR);
	createBak($file, GSUSERSPATH, GSBACKUSERSPATH);
	if (XMLsave($xml, GSUSERSPATH . $file)) {
		if (file_exists(GSUSERSPATH . $file. '.reset')) unlink(GSUSERSPATH . $file . '.reset');
		redirect($referer . '?upd=user-success');
	} else {
		redirect($referer . '?upd=user-error');
	}
} elseif ($referer == 'user.php' && $action == 'undo') {
	// Undo user settings
	if (undo(_id($USR) .'.xml', GSDATAOTHERPATH, GSBACKUPSPATH . 'other/')) {
		redirect($referer . '?upd=user-restored');
	} else {
		redirect($referer . '?upd=user-restored-error');
	}
} else {
	redirect('pages.php');
}