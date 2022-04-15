<?php
/**
 * Edit Backups
 *
 * View the current backup of a given page
 *
 * @package GetSimple Extended
 * @subpackage Backups
 */

declare(strict_types=1);

# setup
$load['plugin'] = true;
include('inc/common.php');
$userid = login_cookie_check();

$id = (string)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_URL);
$p = (string)filter_input(INPUT_GET, 'p');

if ($id == '' || $p == '') redirect('backups.php?upd=bak-err');
// check if $id is allowed system page ID to process
if (($p == 'delete-system' || $p == 'restore-system' || $p == 'view-system') && !in_array($id, getSystemPagesSlugs())) redirect('backups.php?upd=bak-err');
// check for csrf
if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
	if ($p == 'delete' || $p == 'delete-system' || $p == 'restore' || $p == 'restore-system') {
		$nonce = (string)filter_input(INPUT_GET, 'nonce');
		if (!check_nonce($nonce, $p, 'backup-edit.php')) die('CSRF detected!');
	}
}
if ($p == 'delete' || $p == 'delete-system') {
	$result = ($p == 'delete') ? delete_bak($id) : delete_system_bak($id);
	if ($result) {
		redirect('backups.php?upd=bak-success&id=' . $id);
	} else {
		redirect('backups.php?upd=bak-err&id=' . $id);
	}
} elseif ($p == 'restore') {
	if (isset($_GET['new'])) {
		updateSlugs($_GET['new'], $id);
		restore_bak($id);
		$existing = GSDATAPAGESPATH . $_GET['new'] . '.xml';
		$bakfile = GSBACKUPSPATH . 'pages/' . $_GET['new'] . '.bak.xml';
		if (!filepath_is_safe($existing, GSDATAPAGESPATH)) die();
		copy($existing, $bakfile);
		unlink($existing);
		redirect('edit.php?id=' . $id . '&old=' . $_GET['new'] . '&upd=edit-success&type=restore');
	} else {
		restore_bak($id);
		redirect('edit.php?id=' . $id . '&upd=edit-success&type=restore');
	}
} elseif ($p == 'restore-system') {
	if (restore_system_bak($id)) {
		redirect('edit-system.php?id=' . $id . '&upd=edit-success&type=restore');
	} else {
		redirect('backups.php?upd=bak-err&id=' . $id);
	}
} elseif ($p == 'view') {
	# get page url to display
	$file = $id . '.bak.xml';
	$path = GSBACKUPSPATH . 'pages/';
	if (!filepath_is_safe($path . $file, $path)) die();
	$data = getXML($path . $file);
	$title = stripslashes((string)$data->title);
	$pubDate = (string)$data->pubDate;
	$parent = (string)$data->parent;
	$metak = stripslashes((string)$data->meta);
	$metad = stripslashes((string)$data->metad);
	$url = (string)$data->url;
	$content = stripslashes((string)$data->content);
	$private = (string)$data->private;
	$template = (string)$data->template;
	$menu = stripslashes((string)$data->menu);
	$menuStatus = (string)$data->menuStatus;
	$menuOrder = $data->menuOrder;
	$link = find_url($url);
	$menuStatus = ($menuStatus == 'Y' || $menuStatus == '1') ? i18n_r('YES') : i18n_r('NO');
	$backupAction = array('delete' => 'delete', 'restore' => 'restore');
} elseif ($p == 'view-system') {
	$file = $id . '.xml.bak';
	$path = GSBACKUPSPATH . 'other/';
	if (!filepath_is_safe($path . $file, $path)) die();
	$data = getXML($path . $file);
	$title = stripslashes((string)$data->title);
	$pubDate = (string)$data->pubDate;
	$url = (string)$data->url;
	$template = (string)$data->template;
	$content = stripslashes((string)$data->content);
	$backupAction = array('delete' => 'delete-system', 'restore' => 'restore-system');
} else {
	redirect('backups.php?upd=bak-err');
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('BAK_MANAGEMENT') . ' &raquo; ' . i18n_r('VIEWPAGE_TITLE'));

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
		<h3><?php echo ($p == 'view-system') ? i18n_r('BACKUP_OF_SYSTEM_PAGE') : i18n_r('BACKUP_OF'); ?> &lsquo;<em><?php echo $url; ?></em>&rsquo;</h3>
		<div class="edit-nav">
			<a href="backup-edit.php?p=<?php echo $backupAction['restore']; ?>&amp;id=<?php echo var_out($id); ?>&amp;nonce=<?php echo get_nonce($backupAction['restore'], 'backup-edit.php'); ?>" 
				accesskey="<?php echo find_accesskey(i18n_r('ASK_RESTORE'));?>"><?php i18n('ASK_RESTORE');?></a>
			<a href="backup-edit.php?p=<?php echo $backupAction['delete']; ?>&amp;id=<?php echo var_out($id); ?>&amp;nonce=<?php echo get_nonce($backupAction['delete'], 'backup-edit.php'); ?>" 
				title="<?php i18n('DELETEPAGE_TITLE'); ?>: <?php echo var_out($title); ?>?" 
				id="delback" 
				accesskey="<?php echo find_accesskey(i18n_r('ASK_DELETE'));?>" 
				class="delconfirm noajax"><?php i18n('ASK_DELETE');?></a>
		</div>

		<table class="simple highlight">
			<tr><td class="title"><?php i18n('PAGE_TITLE');?>:</td><td><strong><?php echo cl($title); ?></strong><?php if (isset($private) && $private) echo ' <span class="is-private">(' . (($private == '2') ? i18n_r('NOT_PUBLISHED_SUBTITLE') : i18n_r('PRIVATE_SUBTITLE')) . ')</span>'; ?></td></tr>
<?php if ($p == 'view') { ?>
			<tr><td class="title"><?php i18n('BACKUP_OF');?>:</td><td><a target="_blank" href="<?php echo $link; ?>"><?php echo $link; ?></a></td></tr>
			<tr><td class="title"><?php i18n('DATE');?>:</td><td><?php echo lngDate($pubDate); ?></td></tr>
			<tr><td class="title"><?php i18n('PARENT_PAGE'); ?>:</td><td><?php echo $parent; ?></td></tr>
			<tr><td class="title"><?php i18n('TEMPLATE'); ?>:</td><td><?php echo $template; ?></td></tr>
			<tr><td class="title"><?php i18n('TAG_KEYWORDS');?>:</td><td><?php echo $metak; ?></td></tr>
			<tr><td class="title"><?php i18n('META_DESC');?>:</td><td><?php echo $metad; ?></td></tr>
			<tr><td class="title"><?php i18n('ADD_TO_MENU');?>:</td><td><?php echo $menuStatus; ?></td></tr>
			<tr><td class="title"><?php i18n('MENU_TEXT');?>:</td><td><?php echo $menu; ?></td></tr>
			<tr><td class="title"><?php i18n('PRIORITY');?>:</td><td><?php echo $menuOrder; ?></td></tr>
<?php } else { ?>
			<tr><td class="title"><?php i18n('DATE');?>:</td><td><?php echo lngDate($pubDate); ?></td></tr>
			<tr><td class="title"><?php i18n('TEMPLATE'); ?>:</td><td><?php echo $template; ?></td></tr>
<?php }; ?>
		</table>
		<textarea id="codetext" readonly><?php echo $content; ?></textarea>
		</div>

		<?php if ((string)$datau->enableHTMLEditor == '1') { ?>
		<script type="text/javascript" src="template/js/ckeditor/ckeditor.js<?php echo getDef('GSCKETSTAMP', true) ? '?t=' . getDef('GSCKETSTAMP') : ''; ?>"></script>
		<script type="text/javascript">
			<?php if (getDef('GSCKETSTAMP', true)) echo "CKEDITOR.timestamp = '" . getDef("GSCKETSTAMP") . "';\n"; ?>
			var editor = CKEDITOR.replace('codetext', {
				skin : 'getsimple',
				language : '<?php echo $EDLANG; ?>',
				defaultLanguage : 'en',
				<?php if (file_exists(GSTHEMESPATH . $TEMPLATE . '/editor.css')) { ?>contentsCss: '<?php echo suggest_site_path(); ?>theme/<?php echo $TEMPLATE; ?>/editor.css',<?php } ?>
				entities : false,
				height: '<?php echo $EDHEIGHT; ?>',
				baseHref : '<?php echo $SITEURL; ?>',
				toolbar : [['Source']],
				removePlugins: 'image,link,elementspath,resize',
			});
			// set editor to read only mode
			editor.on('mode', function(ev) {
				if (ev.editor.mode == 'source') {
					$('#cke_contents_codetext .cke_source').attr('readonly', 'readonly');
				} else {
					var bodyElement = ev.editor.document.$.body;
					bodyElement.setAttribute('contenteditable', false);
				}
			});
		</script>
		<?php } ?>

	</div>

	<div id="sidebar">
		<?php include('template/sidebar-backups.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>
