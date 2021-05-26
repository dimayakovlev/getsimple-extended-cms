<?php
/**
 * Configuration File
 *
 * @package GetSimple
 * @subpackage Config
 */

$site_full_name     = 'GetSimple Extended CMS';
$site_version_no    = '3.5.0a';
// $name_url_clean     = lowercase(str_replace(' ' , '-', $site_full_name));
// $ver_no_clean       = str_replace('.', '', $site_version_no);
$site_link_back_url = 'https://github.com/dimayakovlev/getsimple-extended-cms';

// cookie config
//$cookie_name        = lowercase($name_url_clean) . '_cookie_' . $ver_no_clean; // non-hashed name of cookie
$cookie_name        = lowercase(lowercase(str_replace(' ' , '-', $site_full_name))) . '_cookie_' . str_replace('.', '', $site_version_no); // non-hashed name of cookie
$cookie_login       = 'index.php'; // login redirect
$cookie_time        = '10800';     // in seconds, 3 hours
$cookie_path        = '/';         // cookie path
$cookie_domain      = null;        // cookie domain
$cookie_secure      = null;        // cookie secure only
$cookie_httponly    = true;        // cookie http only

$api_url            = 'http://get-simple.info/api/start/v3.php';
# $api_timeout        = 800; // time in ms defaults to 500
# $debugApi           = true;

$cookie_redirect = 'pages.php';

if (!defined('GSVERSION')) define('GSVERSION', $site_version_no);
