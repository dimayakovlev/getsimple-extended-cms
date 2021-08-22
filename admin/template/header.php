<?php if (!defined('IN_GS')) die('you cannot load this page directly.');
/**
 * Header Admin Template
 *
 * @package GetSimple Extended
 */

global $SITENAME, $SITEURL, $site_full_name;

$bodyclass = getDef('GSSTYLE') ? ' class="' . GSSTYLE . '"' : '';

if (get_filename_id() != 'index') exec_action('admin-pre-header');

?><!DOCTYPE html>
<html lang="<?php echo get_admin_lang(true); ?>">
<head>
	<meta charset="utf-8">
	<title><?php echo $title ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if (!isAuthPage()) { ?><meta name="generator" content="<?php echo $site_full_name; ?> - <?php echo GSVERSION; ?>">
	<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
	<link rel="author" href="humans.txt">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<?php } ?>
	<meta name="robots" content="noindex, nofollow">
	<link rel="stylesheet" type="text/css" href="template/style.php?<?php echo '&amp;v=' . GSVERSION . (isDebug() ? '&amp;nocache' : ''); ?>" media="screen">
	<!--[if IE 6]><link rel="stylesheet" type="text/css" href="template/ie6.css?v=<?php echo GSVERSION; ?>" media="screen"><![endif]-->
	<?php get_scripts_backend(); ?>
	<script type="text/javascript">
		// init gs namespace and i18n
		var GS = {};
		GS.i18n = new Array();
		GS.i18n['PLUGIN_UPDATED'] = '<?php i18n("PLUGIN_UPDATED"); ?>';
		GS.i18n['ERROR'] = '<?php i18n("ERROR"); ?>';
		GS.i18n['CLOSE'] = '<?php i18n("CLOSE"); ?>';
	</script>
	<script type="text/javascript" src="template/js/jquery.getsimple.js?v=<?php echo GSVERSION; ?>"></script>

	<!--[if lt IE 9]><script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js" ></script><![endif]-->
	<?php if (((get_filename_id() == 'upload') || (get_filename_id() == 'image')) && (!getDef('GSNOUPLOADIFY', true))) { ?>
	<script type="text/javascript" src="template/js/uploadify/jquery.uploadify.js?v=3.0"></script>
	<?php } ?>
	<?php if (get_filename_id() == 'image') { ?>
	<script type="text/javascript" src="template/js/jcrop/jquery.Jcrop.min.js"></script>
	<link rel="stylesheet" type="text/css" href="template/js/jcrop/jquery.Jcrop.css" media="screen">
	<?php } ?>
<?php
	# Plugin hook to allow insertion of stuff into the header
	if (!isAuthPage()) exec_action('header');
?>
</head>

<body <?php filename_id(); echo $bodyclass; ?>>
	<header class="header" id="header">
		<div class="wrapper">
<?php exec_action('header-body'); ?>
