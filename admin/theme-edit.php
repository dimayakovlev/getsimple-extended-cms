<?php 
/**
 * Edit Theme
 *
 * Allows you to edit a theme file
 *
 * @package GetSimple Extended
 * @subpackage Theme
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

# variable settings
login_cookie_check();
$theme_options = '';
$template_file = '';
$template = $TEMPLATE;
$theme_templates = '';

# were changes submitted?
if (isset($_GET['t'])) {
	$_GET['t'] = strippath($_GET['t']);
	if ($_GET['t'] && is_dir(GSTHEMESPATH . $_GET['t'] . '/')) {
		$template = $_GET['t'];
	}
}
if (isset($_GET['f'])) {
	$_GET['f'] = $_GET['f'];
	if ($_GET['f'] && is_file(GSTHEMESPATH . $template . '/' . $_GET['f'])) {
		$template_file = $_GET['f'];
	}
}

# if no template is selected, use the default
if ($template_file == '') $template_file = 'template.php';

$themepath = GSTHEMESPATH . $template . DIRECTORY_SEPARATOR;
if (!filepath_is_safe($themepath . $template_file, GSTHEMESPATH, true)) die();

# check for form submission
if ((isset($_POST['submitsave']))) {
	# check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		if (!check_nonce(filter_input(INPUT_POST, 'nonce'), 'save')) die('CSRF detected!');
	}

	# save edited template file
	$SavedFile = $_POST['edited_file'];
	$FileContents = (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) ? stripslashes($_POST['content']) : $_POST['content'];
	$fh = fopen(GSTHEMESPATH . $SavedFile, 'w') or die("can't open file");
	fwrite($fh, $FileContents);
	fclose($fh);
	$success = sprintf(i18n_r('TEMPLATE_FILE'), $SavedFile);
}

# create themes dropdown
$themes_handle = opendir(GSTHEMESPATH);
while ($file = readdir($themes_handle)) {
	$curpath = GSTHEMESPATH . '/' . $file;
	if (is_dir($curpath) && $file != "." && $file != "..") {
		$theme_dir_array[] = $file;
		if (file_exists($curpath . '/template.php')) {
			$selected = ($template == $file) ? 'selected ' : '';
			$theme_options .= '<option ' . $selected . 'value="' . $file . '" >' . $file . '</option>';
		}
	}
}

# check to see how many themes are available
if (count($theme_dir_array) == 1) $theme_options = '';

$templates = directoryToArray(GSTHEMESPATH . $template . '/', true);
foreach ($templates as $file) {
	if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('php', 'css', 'js', 'html', 'htm', 'txt', 'svg', 'json', 'xml'))) {
		$filename = pathinfo($file, PATHINFO_BASENAME);
		$filenamefull = substr(strstr($file, '/theme/' . $template . '/'), strlen('/theme/' . $template . '/'));
		$selected = ($template_file == $filenamefull) ? 'selected ' : '';
		$theme_templates .= '<option '. $selected . 'value="' . $filenamefull . '">' . ($filename == 'template.php' ? i18n_r('DEFAULT_TEMPLATE') : $filenamefull) . '</option>';
	}
}

# register and queue CodeMirror files
if ($datau->enableCodeEditor == '1') {
	register_script('codemirror', $SITEURL . $GSADMIN . '/template/js/codemirror/lib/codemirror-compressed.js', '0.2.0', false);
	register_style('codemirror-css', $SITEURL . $GSADMIN . '/template/js/codemirror/lib/codemirror.css','screen', false);
	register_style('codemirror-theme', $SITEURL . $GSADMIN . '/template/js/codemirror/theme/default.css','screen', false);
	queue_script('codemirror', GSBACK);
	queue_style('codemirror-css', GSBACK);
	queue_style('codemirror-theme', GSBACK);
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('THEME_MANAGEMENT'));
?>
<?php include('template/include-nav.php'); ?>
<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
		<h3><?php i18n('EDIT_THEME'); ?></h3>
		<form id="theme-files-selector" action="<?php myself(); ?>" method="get" accept-charset="utf-8"><select class="text" name="t" id="theme-folder"><?php echo $theme_options; ?></select><select class="text" id="theme-files" name="f"><?php echo $theme_templates; ?></select><input class="submit" type="submit" name="s" value="<?php i18n('EDIT'); ?>"></form>

		<p><strong><?php i18n('EDITING_FILE'); ?>:</strong> <code><?php echo $SITEURL.'theme/'. tsl($template) .'<strong>'. $template_file; ?></strong></code></p>
		<?php $content = file_get_contents(GSTHEMESPATH . tsl($template) . $template_file); ?>

		<form action="<?php myself(); ?>?t=<?php echo $template; ?>&amp;f=<?php echo $template_file; ?>" method="post">
			<input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce("save"); ?>">
			<textarea name="content" id="codetext" class="text" wrap='off' ><?php echo htmlentities($content, ENT_QUOTES, 'UTF-8'); ?></textarea>
			<input type="hidden" value="<?php echo tsl($template) . $template_file; ?>" name="edited_file">
			<?php exec_action('theme-edit-extras'); ?>
			<p id="submit_line">
				<span><input class="submit" type="submit" name="submitsave" value="<?php i18n('BTN_SAVECHANGES'); ?>"></span> <?php i18n('OR'); ?> <a class="cancel" href="theme-edit.php?cancel"><?php i18n('CANCEL'); ?></a>
			</p>
		</form>
<?php
if ($datau->enableCodeEditor == 1) {
	switch (strtolower(pathinfo($template_file, PATHINFO_EXTENSION))) {
		case 'css':
			$mode = 'text/css';
			break;
		case 'js':
			$mode = 'text/javascript';
			break;
		case 'htm':
		case 'html':
			$mode = 'text/html';
			break;
		case 'txt':
			$mode = 'text/plain';
			break;
		case 'svg':
			$mode = 'image/svg+xml';
			break;
		case 'json':
			$mode = 'application/json';
			break;
		case 'xml':
			$mode = 'application/xml';
			break;
		default:
			$mode = 'application/x-httpd-php';
	}
?>
	<style>.CodeMirror, .CodeMirror-scroll { height: <?php echo $EDHEIGHT; ?>; }</style>
	<script>
		GS.CodeMirror = new Array();
		GS.CodeMirror['enabled'] = true;
		GS.CodeMirror['options'] = {mode: '<?php echo $mode;?>'};
		addCodeMirror(document.getElementById('codetext'), GS.CodeMirror['options']);
	</script>
<?php
}
?>
		</div>
	</div>
	<div id="sidebar"><?php include('template/sidebar-theme.php'); ?></div>
</div>
<?php get_template('footer'); ?>
