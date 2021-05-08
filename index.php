<?php
/**
 * Index
 *
 * Where it all starts	
 *
 * @package GetSimple
 * @subpackage FrontEnd
 */


/* pre-common setup, load gsconfig and get GSADMIN path */

	/* GSCONFIG definitions */
	if(!defined('GSFRONT')) define('GSFRONT', 1);
	if(!defined('GSBACK')) define('GSBACK', 2);
	if(!defined('GSBOTH')) define('GSBOTH', 3);
	if(!defined('GSSTYLEWIDE')) define('GSSTYLEWIDE', 'wide'); // wide style sheet
	if(!defined('GSSTYLE_SBFIXED')) define('GSSTYLE_SBFIXED', 'sbfixed'); // fixed sidebar

	# Check and load gsconfig
	if (file_exists('gsconfig.php')) {
		require_once('gsconfig.php');
	}

	# Apply GSADMIN env
	if (defined('GSADMIN')) {
		$GSADMIN = GSADMIN;
	} else {
		$GSADMIN = 'admin';
	}

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
if (isset($_GET['id'])) { 
	$id = lowercase(str_replace(array('..', '/'), '', $_GET['id']));
} else {
	$id = "index";
}

// filter to modify page id request
$id = exec_filter('indexid', $id);
 // $_GET['id'] = $id; // support for plugins that are checking get?

// define page
// apply page data if page id exists
if (isset($pagesArray[$id])) {
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
	} else {
		// fail over
		redirect('404');
	}
	exec_action('error-404');
	header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
}

// if page is private, check user
if ($data_index->private == 'Y' && !is_logged_in()) {
	if (file_exists(GSDATAOTHERPATH . '403.xml')) {
		// default 403
		$data_index = getXml(GSDATAOTHERPATH . '403.xml');
	} else {
		// fail over
		redirect('403');
	}
	exec_action('error-403');
	header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
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
if (getDef('GSCANONICAL', true)) {
	if ($_SERVER['REQUEST_URI'] != find_url($url, $parent, 'relative')) {
		redirect(find_url($url, $parent));
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
