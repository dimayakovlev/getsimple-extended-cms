<?php
/**
 * Edit Backups
 *
 * View the current backup of a given page
 *
 * @package GetSimple Extended
 * @subpackage Backups
 */

# setup
$load['plugin'] = true;
include('inc/common.php');
$userid = login_cookie_check();

# get page url to display
if ($_GET['id'] != '') {
	$id = $_GET['id'];
	$file = $id . '.bak.xml';
	$path = GSBACKUPSPATH . 'pages/';
	if(!filepath_is_safe($path.$file,$path)) die();
	$data = getXML($path . $file);
	$title = htmldecode($data->title);
	$pubDate = $data->pubDate;
	$parent = $data->parent;
	$metak = htmldecode($data->meta);
	$metad = htmldecode($data->metad);
	$url = $data->url;
	$content = htmldecode($data->content);
	$private = (string)$data->private;
	$template = $data->template;
	$menu = htmldecode($data->menu);
	$menuStatus = $data->menuStatus;
	$menuOrder = $data->menuOrder;
	$pageType = (int)$data->type;
} else {
	redirect('backups.php?upd=bak-err');
}

$menuStatus = ($menuStatus == '') ? i18n_r('NO') : i18n_r('YES');

// are we going to do anything with this backup?
if ($_GET['p'] != '') {
	$p = $_GET['p'];
} else {
	redirect('backups.php?upd=bak-err');
}

if ($p == 'delete') {
	// check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = $_GET['nonce'];
		if(!check_nonce($nonce, 'delete', 'backup-edit.php')) die('CSRF detected!');
	}
	delete_bak($id);
	redirect('backups.php?upd=bak-success&id=' . $id);
} elseif ($p == 'restore') {
	// check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false) ) {
		$nonce = $_GET['nonce'];
		if(!check_nonce($nonce, 'restore', 'backup-edit.php')) die('CSRF detected!');
	}
	if (isset($_GET['new'])) {
		updateSlugs($_GET['new'], $id);
		restore_bak($id);
		$existing = GSDATAPAGESPATH . $_GET['new'] . '.xml';
		$bakfile = GSBACKUPSPATH . 'pages/' . $_GET['new'] . '.bak.xml';
		if(!filepath_is_safe($existing, GSDATAPAGESPATH)) die();
		copy($existing, $bakfile);
		unlink($existing);
		redirect('edit.php?id=' . $id . '&old=' . $_GET['new'] . '&upd=edit-success&type=restore');
	} else {
		restore_bak($id);
		redirect('edit.php?id=' . $id . '&upd=edit-success&type=restore');
	}
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('BAK_MANAGEMENT') . ' &raquo; ' . i18n_r('VIEWPAGE_TITLE')); 

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
		<h3 class="floated"><?php i18n('BACKUP_OF');?> &lsquo;<em><?php echo $url; ?></em>&rsquo;</h3>

		<div class="edit-nav">
			<a href="backup-edit.php?p=restore&amp;id=<?php echo var_out($id); ?>&amp;nonce=<?php echo get_nonce("restore", "backup-edit.php"); ?>" 
				accesskey="<?php echo find_accesskey(i18n_r('ASK_RESTORE'));?>" ><?php i18n('ASK_RESTORE');?></a>
			<a href="backup-edit.php?p=delete&amp;id=<?php echo var_out($id); ?>&amp;nonce=<?php echo get_nonce("delete", "backup-edit.php"); ?>" 
				title="<?php i18n('DELETEPAGE_TITLE'); ?>: <?php echo var_out($title); ?>?" 
				id="delback" 
				accesskey="<?php echo find_accesskey(i18n_r('ASK_DELETE'));?>" 
				class="delconfirm noajax" ><?php i18n('ASK_DELETE');?></a>
			<div class="clear"></div>
		</div>

		<table class="simple highlight">
		<tr><td class="title"><?php i18n('PAGE_TITLE');?>:</td><td><strong><?php echo cl($title); ?></strong><?php if ($private) echo ' <span class="is-private">(' . (($private == '2') ? i18n_r('NOT_PUBLISHED_SUBTITLE') : i18n_r('PRIVATE_SUBTITLE')) . ')</span>'; if ($pageType == 1) echo ' <span class="attention">(' . i18n_r('PAGE_TYPE_DYNAMIC_SUBTITLE') . ')</span>'; ?></td></tr>
		<tr><td class="title"><?php i18n('BACKUP_OF');?>:</td><td>
			<?php
			if (isset($id)) {
				$link = find_url($url);
				echo '<a target="_blank" href="' . $link . '">' . $link . '</a>';
			}
			?>
		</td></tr>
		<tr><td class="title" ><?php i18n('DATE');?>:</td><td><?php echo lngDate($pubDate); ?></td></tr>
		<tr><td class="title" ><?php i18n('TAG_KEYWORDS');?>:</td><td><em><?php echo $metak; ?></em></td></tr>
		<tr><td class="title" ><?php i18n('META_DESC');?>:</td><td><em><?php echo $metad; ?></em></td></tr>
		<tr><td class="title" ><?php i18n('MENU_TEXT');?>:</td><td><?php echo $menu; ?></td></tr>
		<tr><td class="title" ><?php i18n('PRIORITY');?>:</td><td><?php echo $menuOrder; ?></td></tr>
		<tr><td class="title" ><?php i18n('ADD_TO_MENU');?></td><td><?php echo $menuStatus; ?></td></tr>
		</table>

		<textarea id="codetext" wrap='off' style="background:#f4f4f4;padding:4px;width:635px;color:#444;border:1px solid #666;" readonly><?php echo strip_decode($content); ?></textarea>

		</div>

	</div>

	<div id="sidebar">
		<?php include('template/sidebar-backups.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>
