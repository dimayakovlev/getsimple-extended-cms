<?php
/**
 * Index
 *
 * Where it all starts
 *
 * @package GetSimple Extended
 * @subpackage FrontEnd
 */


/* pre-common setup, load gsconfig and get GSADMIN path */

/* GSCONFIG definitions */
if (!defined('GSFRONT')) define('GSFRONT', 1);
if (!defined('GSBACK')) define('GSBACK', 2);
if (!defined('GSBOTH')) define('GSBOTH', 3);

# Check and load gsconfig
if (file_exists('gsconfig.php')) require_once('gsconfig.php');

# Apply GSADMIN env
$GSADMIN = defined('GSADMIN') ? GSADMIN : 'admin';

# setup paths 
# @todo wtf are these for ?
$admin_relative = $GSADMIN . '/inc/';
$lang_relative = $GSADMIN . '/';

$load['plugin'] = true;
$base = true;

/* end */

# Include common.php
include($GSADMIN . '/inc/common.php');

# Hook to load page Cache
exec_action('index-header');

# get page id (url slug) that is being passed via .htaccess mod_rewrite
$id = isset($_GET['id']) ? lowercase(str_replace(array('..', '/'), '', $_GET['id'])) : 'index';

// filter to modify page id request
$id = exec_filter('indexid', $id);
 // $_GET['id'] = $id; // support for plugins that are checking get?

$GSCANONICAL = getDef('GSCANONICAL', true);

// define page
if ($dataw->maintenance == '1' && (!is_logged_in() || $datau->accessFrontMaintenance != '1')) {
	// apply page data if maintance mode enabled
	$data_index = getXml(GSDATAOTHERPATH . '503.xml');
	if ($data_index) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');
		$GSCANONICAL = false;
	} else {
		redirect('503');
	}
} elseif (isset($pagesArray[$id])) {
	// apply page data if page id exists
	$data_index = getXml(GSDATAPAGESPATH . $id . '.xml');
} else {
	$data_index = null;
}

// filter to modify data_index obj
$data_index = exec_filter('data_index', $data_index);

// page not found handling
if (!$data_index) {
	if (file_exists(GSDATAOTHERPATH . '404.xml')) {
		// default 404
		$data_index = getXml(GSDATAOTHERPATH . '404.xml');
		$GSCANONICAL = false;
	} else {
		// fail over
		redirect('404');
	}
	exec_action('error-404');
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
}

// if page is private, check user
if ($data_index->private == 'Y' && !is_logged_in()) {
	if (file_exists(GSDATAOTHERPATH . '403.xml')) {
		// default 403
		$data_index = getXml(GSDATAOTHERPATH . '403.xml');
		$GSCANONICAL = false;
	} else {
		// fail over
		redirect('403');
	}
	exec_action('error-403');
	header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
}

$title         = $data_index->title;
$date          = $data_index->pubDate;
$metak         = $data_index->meta;
$metad         = $data_index->metad;
$url           = $data_index->url;
$content       = $data_index->content;
$parent        = $data_index->parent;
$template_file = $data_index->template;
$private       = $data_index->private;

// after fields from dataindex, can modify globals here or do whatever by checking them
exec_action('index-post-dataindex');

# check for correctly formed url
if ($GSCANONICAL == true) {
	if (strpos($_SERVER['REQUEST_URI'], find_url($url, false)) !== 0) {
		redirect(find_url($url, true));
	}
}

# include the functions.php page if it exists within the theme
if (file_exists(GSTHEMESPATH . $TEMPLATE . '/functions.php')) {
	include(GSTHEMESPATH . $TEMPLATE . '/functions.php');
}

# call pretemplate Hook
exec_action('index-pretemplate');

# include the template and template file set within theme.php and each page
if ((!file_exists(GSTHEMESPATH . $TEMPLATE . '/' . $template_file)) || ($template_file == '')) {
	$template_file = 'template.php';
}
include(GSTHEMESPATH . $TEMPLATE . '/' . $template_file);

# call posttemplate Hook
exec_action('index-posttemplate');
