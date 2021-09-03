<?php

/**
 * Display Available Themes
 * 
 * This file spits out a list of available themes to the control panel.
 * This is provided thru an ajax call.
 *
 * @package GetSimple Extended
 * @subpackage Available-Themes
 */

// Include common.php
include('common.php');
login_cookie_check();

// JSON output of pages for ckeditor select
if (isset($_REQUEST['list_pages_json'])) {
	include_once('plugin_functions.php');
	include_once('caching_functions.php');
	getPagesXmlValues();
	header('Content-type: application/json');
	echo list_pages_json();
	die();
}

// Make sure register globals don't make this hackable again.
if (isset($TEMPLATE)) unset($TEMPLATE);

/**
 * Sanitise first
 * @todo Maybe use Anti-XSS on this instead?
 */
if (isset($_GET['dir'])) {
	$TEMPLATE = '';
	$segments = explode('/', implode('/', explode('\\', $_GET['dir'])));
	foreach ($segments as $part) if ($part !== '..') $TEMPLATE .= $part . '/';
	$TEMPLATE = preg_replace('/\/+/', '/', $TEMPLATE);
	if (strlen($TEMPLATE) <= 0 || $TEMPLATE == '/') unset($TEMPLATE);
}

// Send back list of theme files from a certain directory for theme-edit.php
if (isset($TEMPLATE)) {
	$TEMPLATE_FILE = '';
	$template = '';
	$theme_templates = '';
	if ($template == '') $template = 'template.php';
	if(!filepath_is_safe(GSTHEMESPATH . $TEMPLATE, GSTHEMESPATH)) die();
	$templates = directoryToArray(GSTHEMESPATH . $TEMPLATE . '/', true);
	foreach ($templates as $file) {
		if (!in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('php', 'css', 'js', 'html', 'htm', 'txt', 'svg', 'json', 'xml'))) continue;
		$filename = pathinfo($file, PATHINFO_BASENAME);
		$filenamefull = substr(strstr($file, '/theme/' . $TEMPLATE . '/'), strlen('/theme/' . $TEMPLATE . '/'));
		$selected = ($TEMPLATE_FILE == $filename) ? 'selected ': '';
		$theme_templates .= '<option ' . $selected . 'value="' . $filenamefull . '">' . ($filename == 'template.php' ? i18n_r('DEFAULT_TEMPLATE') : $filenamefull) . '</option>';
	}
	echo $theme_templates;
}