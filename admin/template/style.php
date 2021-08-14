<?php 
/**
 * Admin Stylesheet
 * 
 * @package GetSimple
 * @subpackage init
 */
header('Content-type: text/css');
$offset = 30000;
#header ('Cache-Control: max-age=' . $offset . ', must-revalidate');
#header ('Expires: ' . gmdate ("D, d M Y H:i:s", time() + $offset) . ' GMT');

# check to see if cache is available for this
$cacheme = !isset($_GET['nocache']);
$cachefile = '../../data/cache/stylesheet.txt';
if (file_exists($cachefile) && time() - 600 < filemtime($cachefile) && $cacheme) {
	echo file_get_contents($cachefile);
	exit;
}

if ($cacheme) {
	ob_start();
}

function compress($buffer) {
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer); /* remove comments */
  $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer); /* remove tabs, spaces, newlines, etc. */
  return $buffer;
}

if (file_exists('../../theme/admin.xml')) {
	#load admin theme xml file
	$theme = simplexml_load_string(file_get_contents('../../theme/admin.xml'));
	$primary_0 = trim($theme->primary->darkest);
	$primary_1 = trim($theme->primary->darker);
	$primary_2 = trim($theme->primary->dark);
	$primary_3 = trim($theme->primary->middle);
	$primary_4 = trim($theme->primary->light);
	$primary_5 = trim($theme->primary->lighter);
	$primary_6 = trim($theme->primary->lightest);
	$secondary_0 = trim($theme->secondary->darkest);
	$secondary_1 = trim($theme->secondary->lightest);
} else {
	# set default colors
	$primary_0 = '#0E1316'; # darkest
	$primary_1 = '#182227';
	$primary_2 = '#283840';
	$primary_3 = '#415A66';
	$primary_4 = '#618899';
	$primary_5 = '#E8EDF0';
	$primary_6 = '#AFC5CF'; # lightest
	$secondary_0 = '#9F2C04'; # darkest
	$secondary_1 = '#CF3805'; # lightest
}

include('css.php');

if ($cacheme) {
	file_put_contents($cachefile, compress(ob_get_contents()) . '/* Cached copy, generated ' . date('H:i') . " '" . $cachefile . "' */\n");
	chmod($cachefile, 0644);
	ob_end_flush();
}
