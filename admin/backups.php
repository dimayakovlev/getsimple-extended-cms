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

// delete all backup files if the ?deleteall session parameter is set
if (isset($_GET['deleteall'])) {
	// check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false)) {
		$nonce = (string)filter_input(INPUT_GET, 'nonce');
		if (!check_nonce($nonce, 'deleteall')) die('CSRF detected!');
	}
	$filenames = getFiles($path);

	foreach ($filenames as $file) {
		if (file_exists($path . $file)) {
			if (isFile($file, $path, 'bak')) unlink($path . $file);
		}
	}

	$success = i18n_r('ER_FILE_DEL_SUC');
}


//display all page backups
$filenames = getFiles($path);
$count = 0;
$pagesArray_tmp = array();
$pagesSorted = array(); 

if (count($filenames) != 0) {
	foreach ($filenames as $file) {
		if (isFile($file, $path, 'bak')) {
			$data = getXML($path .$file);
			$pagesArray_tmp[$count]['title'] = html_entity_decode((string)$data->title, ENT_QUOTES, 'UTF-8');
			$pagesArray_tmp[$count]['url'] = (string)$data->url;
			$pagesArray_tmp[$count]['date'] = (string)$data->pubDate;
			$count++;
		}
	}
	$pagesSorted = subval_sort($pagesArray_tmp, 'title');
}

if (count($pagesSorted) != 0) {
	foreach ($pagesSorted as $page) {
		$counter++;
		$table .= '<tr id="tr-' . $page['url'] . '" >';

		if ($page['title'] == '') $page['title'] = '[No Title] &nbsp;&raquo;&nbsp; <em>' . $page['url'] . '</em>';

		$table .= '<td class="pagetitle"><a title="' . i18n_r('VIEWPAGE_TITLE') . ' ' . var_out($page['title']) . '" href="backup-edit.php?p=view&amp;id=' . $page['url'] . '">' . cl($page['title']) . '</a></td>';
		$table .= '<td class="date"><span>' . shtDate($page['date']) . '</span></td>';
		$table .= '<td class="delete"><a class="delconfirm" title="' . i18n_r('DELETEPAGE_TITLE') . ' ' . var_out($page['title']) . '?" href="backup-edit.php?p=delete&amp;id=' . $page['url'] . '&amp;nonce=' . get_nonce('delete', 'backup-edit.php') . '">&times;</a></td>';
		$table .= '</tr>';
	}
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('BAK_MANAGEMENT')); 

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
			<h3><?php i18n('PAGE_BACKUPS'); ?></h3>
			<?php if ($counter > 0) { ?>
				<div class="edit-nav"><a href="#" id="filtertable" accesskey="<?php echo find_accesskey(i18n_r('FILTER'));?>" ><?php i18n('FILTER'); ?></a> <a href="backups.php?deleteall&amp;nonce=<?php echo get_nonce('deleteall'); ?>" title="<?php i18n('DELETE_ALL_BAK');?>" accesskey="<?php echo find_accesskey(i18n_r('ASK_DELETE_ALL'));?>" class="confirmation"><?php i18n('ASK_DELETE_ALL'); ?></a></div>
				<div id="filter-search">
					<form><input type="text" autocomplete="off" class="text" id="q" placeholder="<?php echo strip_tags(lowercase(i18n_r('FILTER'))); ?>..."> &nbsp; <a href="pages.php" class="cancel"><?php i18n('CANCEL'); ?></a></form>
				</div>
				<table id="editpages" class="highlight paginate">
					<tr><th><?php i18n('PAGE_TITLE'); ?></th><th class="date"><?php i18n('DATE'); ?></th><th></th></tr>
					<?php echo $table; ?>
				</table>
			<?php } else { ?>
				<div class="clearfix" style="height:40px;"></div>
			<?php } ?>
		
			<p><em><?php echo i18n_r('TOTAL_BACKUPS'); ?>: <strong><span id="pg_counter"><?php echo $counter; ?></span></strong></em></p>
		</div>
	</div>

	<div id="sidebar">
		<?php include('template/sidebar-backups.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>