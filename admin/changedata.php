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
if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != parse_url($SITEURL, PHP_URL_HOST)) {
	die('Invalid Referer Domain');
}

$referer = basename(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH));
$actions = array(
	'edit.php' => array('save'),
	'menu-manager.php' => array('save'),
	'components.php' => array('save'),
);

// check referer page
if (!isset($actions[$referer])) {
	die('Wrong Referer Page');
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

// check action
if ($action == '' || !in_array($action, $actions[$referer])) {
	die('Wrong action');
}

// check for csrf
// Create nonce in forms: get_nonce('action-name', pathinfo(__FILE__, PATHINFO_BASENAME));
if (!defined('GSNOCSRF') || GSNOCSRF == false) {
	$nonce = isset($_POST['nonce']) ? trim($_POST['nonce']) : '';
	if ($nonce == '' || !check_nonce($nonce, $action, $referer)) {
		die('CSRF detected');
	}
}

login_cookie_check();

// Save page data
if ($referer == 'edit.php' && $action == 'save') {

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
			$bakfile = GSBACKUPSPATH . 'pages/' . $url . '.bak.xml';
			copy($file, $bakfile);
		}

		$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><item></item>');
		$xml->addChild('pubDate', date('r'));
		$xml->addChild('creDate', filter_input(INPUT_POST, 'post-creDate', FILTER_SANITIZE_STRING) ?: date('r'));
		$xml->addChild('title')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-title')))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('url', $url);
		$xml->addChild('meta')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-metak')))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('metad')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-metad')))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('menu')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-menu')))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
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
		$xml->addChild('lastAuthor', $USR);
		$xml->addChild('lang')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-lang', FILTER_SANITIZE_STRING)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addChild('permalink')->addCData(filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_POST, 'post-permalink', FILTER_SANITIZE_URL)))), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		$xml->addAttribute('autoOpenMetadata', (string)filter_input(INPUT_POST, 'autoopen-metadata', FILTER_VALIDATE_BOOLEAN));
		$xml->addAttribute('autoOpenComponent', (string)filter_input(INPUT_POST, 'autoopen-component', FILTER_VALIDATE_BOOLEAN));
		$xml->addAttribute('disableHTMLEditor', (string)filter_input(INPUT_POST, 'disable-html-editor', FILTER_VALIDATE_BOOLEAN));
		$xml->addAttribute('disableCodeEditor', (string)filter_input(INPUT_POST, 'disable-code-editor', FILTER_VALIDATE_BOOLEAN));

		exec_action('changedata-save');
		if (isset($_POST['autosave']) && $_POST['autosave'] == 'true' && $autoSaveDraft == true) {
			$status = XMLsave($xml, GSAUTOSAVEPATH . $url);
		} else {
			$status = XMLsave($xml, $file);
			if ($status) set_site_last_update();
		}

		//ending actions
		exec_action('changedata-aftersave');
		generate_sitemap();

		// redirect user back to edit page 
		if (isset($_POST['autosave']) && $_POST['autosave'] == 'true') {
			echo $status ? 'OK' : 'ERROR';
		} else {
			if(!$status) redirect($referer . '?id=' . $url . '&upd=edit-error&type=edit'); 

			if ($_POST['redirectto'] != '') {
				$redirect_url = $_POST['redirectto']; // @todo sanitize redirects, not sure what this is for, js sets pages.php always?
			} else {
				$redirect_url = 'edit.php';
			}
			
			if (isset($existingurl)) {
				if ($url == $existingurl) {
					// redirect save new file
					redirect($redirect_url . '?id=' . $url . '&upd=edit-success&type=edit');
				} else {
					// redirect new slug, undo for old slug
					redirect($redirect_url . '?id=' . $url . '&old=' . $existingurl . '&upd=edit-success&type=edit');
				}
			}	
			else {
				// redirect new slug
				redirect($redirect_url . '?id=' . $url . '&upd=edit-success&type=new'); 
			}
		}
	}
}

// Save page priority order
if ($referer == 'menu-manager.php' && $action == 'save') {
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
					copy($file, GSBACKUPSPATH . 'pages/' . $slug. '.bak.xml');
					XMLsave($data, $file);
				}
			}
			$priority++;
		}
		create_pagesxml('true');
		set_site_last_update();
		redirect($referer . '?upd=menu-success');
	} else {
		redirect($referer . '?upd=menu-error');
	}
}

// Save components
if ($referer == 'components.php' && $action == 'save') {
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
		$path = GSDATAOTHERPATH;
		$bakpath = GSBACKUPSPATH . 'other/';
		createBak($file, $path, $bakpath);

		exec_action('component-save');
		if (XMLsave($xml, $path . $file)) {
			redirect($referer . '?upd=comp-success');
		} else {
			redirect($referer . '?upd=comp-error');
		}
}

redirect('pages.php');
