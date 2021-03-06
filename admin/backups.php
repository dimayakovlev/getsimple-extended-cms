<?php
/**
 * All Backups
 *
 * Displays all available page backups.
 *
 * @package GetSimple Extended
 * @subpackage Backups
 * @link http://get-simple.info/docs/restore-page-backup
 */

declare(strict_types=1);

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('inc/common.php');

// Variable settings
login_cookie_check();
$path = GSBACKUPSPATH . 'pages/';
$counter = 0;
$table = '';

$pathSystemPages = GSBACKUPSPATH . 'other/';

// delete all backup files if the deleteall or deleteall-system parameter is set
if (isset($_GET['deleteall']) || isset($_GET['deleteall-system'])) {
	// check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = (string)filter_input(INPUT_GET, 'nonce');
		if (!check_nonce($nonce, isset($_GET['deleteall']) ? 'deleteall' : 'deleteall-system')) die('CSRF detected!');
	}
	if (isset($_GET['deleteall'])) {
		$filenames = getFiles($path);
		foreach ($filenames as $file) {
			if (file_exists($path . $file)) {
				if (isFile($file, $path, 'bak')) unlink($path . $file);
			}
		}
		$success = i18n_r('ER_FILE_DEL_SUC');
	}
	//Delete all backup files for system pages
	if (isset($_GET['deleteall-system'])) {
		foreach (getSystemPagesSlugs() as $file) {
			$file = $pathSystemPages . $file . '.xml.bak';
			if (is_file($file)) unlink($file);
		}
		$success = i18n_r('ER_FILE_DEL_SUC');
	}
}

//display all page backups
$filenames = getFiles($path);
$count = 0;
$pagesArray_tmp = array();
$pagesSorted = array();

if (count($filenames) != 0) {
	foreach ($filenames as $file) {
		if (isFile($file, $path, 'bak')) {
			$data = getXML($path . $file);
			$pagesArray_tmp[$count]['title'] = html_entity_decode((string)$data->title, ENT_QUOTES, 'UTF-8');
			$pagesArray_tmp[$count]['url'] = (string)$data->url;
			$pagesArray_tmp[$count]['date'] = (string)$data->pubDate;
			$count++;
		}
	}
	$pagesSorted = subval_sort($pagesArray_tmp, 'title');
}

if (count($pagesSorted) != 0) {
	$counter = 0;
	foreach ($pagesSorted as $page) {
		$counter++;
		$table .= '<tr id="tr-' . $page['url'] . '">';

		if ($page['title'] == '') $page['title'] = '[No Title] &nbsp;&raquo;&nbsp; <em>' . $page['url'] . '</em>';

		$table .= '<td class="pagetitle"><a title="' . i18n_r('VIEWPAGE_TITLE') . ': ' . var_out($page['title']) . '" href="backup-edit.php?p=view&amp;id=' . $page['url'] . '">' . cl($page['title']) . '</a></td>';
		$table .= '<td class="date"><span>' . shtDate($page['date']) . '</span></td>';
		$table .= '<td class="secondarylink"><a class="delconfirm" title="' . strip_tags(i18n_r('ASK_RESTORE')) . ': ' . var_out($page['title']) . '?" href="backup-edit.php?p=restore&id=' . $page['url'] . '&nonce=' . get_nonce('restore', 'backup-edit.php') . '">&#11119;&#xFE0E;</a></td>';
		$table .= '<td class="delete"><a class="delconfirm" title="' . i18n_r('DELETEPAGE_TITLE') . ': ' . var_out($page['title']) . '?" href="backup-edit.php?p=delete&amp;id=' . $page['url'] . '&amp;nonce=' . get_nonce('delete', 'backup-edit.php') . '">&times;</a></td>';
		$table .= '</tr>';
	}
}

$tableSystemPages = '';
$counterSystemPages = 0;
foreach(getSystemPagesSlugs() as $slug) {
	$file = $pathSystemPages . $slug . '.xml.bak';
	if (is_file($file)) {
		$counterSystemPages++;
		$data = getXML($file);
		$title = html_entity_decode((string)$data->title, ENT_QUOTES, 'UTF-8') ?: $slug;
		$date = (string)$data->pubDate;
		$tableSystemPages .= '<tr id="trs-' . $slug . '">';
		$tableSystemPages .= '<td class="pagetitle">' . $slug . ': <a title="' . i18n_r('VIEWPAGE_TITLE') . ': ' . $title . '" href="backup-edit.php?p=view-system&amp;id=' . $slug . '">' . $title . '</a></td>';
		$tableSystemPages .= '<td class="date"><span>' . shtDate($date) . '</span></td>';
		$tableSystemPages .= '<td class="secondarylink"><a class="delconfirm" title="' . strip_tags(i18n_r('ASK_RESTORE')) . ': ' . $title . '?" href="backup-edit.php?p=restore-system&id=' . $page['url'] . '&nonce=' . get_nonce('restore-system', 'backup-edit.php') . '">&#11119;&#xFE0E;</a></td>';
		$tableSystemPages .= '<td class="delete"><a class="delconfirm" title="' . i18n_r('DELETEPAGE_TITLE') . ': ' . $title . '?" href="backup-edit.php?p=delete-system&amp;id=' . $slug . '&amp;nonce=' . get_nonce('delete-system', 'backup-edit.php') . '">&times;</a></td>';
		$tableSystemPages .= '</tr>';
	}
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('BAK_MANAGEMENT'));

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
			<h3><?php i18n('PAGE_BACKUPS'); ?></h3>
			<?php
				if ($counter > 0) {
			?>
				<div class="edit-nav"><a href="#" id="filtertable" accesskey="<?php echo find_accesskey(i18n_r('FILTER'));?>" ><?php i18n('FILTER'); ?></a> <a href="backups.php?deleteall&amp;nonce=<?php echo get_nonce('deleteall'); ?>" title="<?php i18n('DELETE_ALL_BAK');?>" accesskey="<?php echo find_accesskey(i18n_r('ASK_DELETE_ALL'));?>" class="confirmation"><?php i18n('ASK_DELETE_ALL'); ?></a></div>
				<div id="filter-search">
					<form><input type="text" autocomplete="off" class="text" id="q" placeholder="<?php echo strip_tags(lowercase(i18n_r('FILTER'))); ?>&hellip;"> &nbsp; <a href="pages.php" class="cancel"><?php i18n('CANCEL'); ?></a></form>
				</div>
				<table id="editpages" class="highlight paginate">
					<tr><th><?php i18n('PAGE_TITLE'); ?></th><th class="date"><?php i18n('DATE'); ?></th><th></th><th></th></tr>
					<?php echo $table; ?>
				</table>
			<?php
				}
			?>
			<p><em><?php echo i18n_r('TOTAL_BACKUPS'); ?>: <strong><span id="pg_counter"><?php echo $counter; ?></span></strong></em></p>
			<div id="system">
				<h3><?php i18n('PAGE_SYSTEM_BACKUPS'); ?></h3>
			<?php
				if ($counterSystemPages > 0) {
			?>
				<div class="edit-nav"><a href="backups.php?deleteall-system&amp;nonce=<?php echo get_nonce('deleteall-system'); ?>" title="<?php i18n('DELETE_ALL_BAK');?>" accesskey="<?php echo find_accesskey(i18n_r('ASK_DELETE_ALL'));?>" class="confirmation"><?php i18n('ASK_DELETE_ALL'); ?></a></div>
				<table id="editsystempages" class="highlight paginate">
					<tr><th><?php i18n('PAGE_TITLE'); ?></th><th class="date"><?php i18n('DATE'); ?></th><th></th><th></th></tr>
					<?php echo $tableSystemPages; ?>
				</table>
			<?php
				}
			?>
				<p><em><?php echo i18n_r('TOTAL_BACKUPS'); ?>: <strong><span id="spg_counter"><?php echo $counterSystemPages; ?></span></strong></em></p>
			</div>
			
		</div>
	</div>

	<div id="sidebar">
		<?php include('template/sidebar-backups.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>