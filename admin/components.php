<?php
/**
 * Components
 *
 * Displays and creates static components
 *
 * @package GetSimple
 * @subpackage Components
 * @link http://get-simple.info/docs/what-are-components
 */
 
# setup inclusions
$load['plugin'] = true;
include('inc/common.php');

# variable settings
$userid 	= login_cookie_check();
$file 		= 'components.xml';
$path 		= GSDATAOTHERPATH;
$bakpath 	= GSBACKUPSPATH . 'other/';
$update 	= ''; $table = ''; $list='';

# check to see if form was submitted
if (isset($_POST['submitted'])) {

	// check for csrf
	if (!defined('GSNOCSRF') || (GSNOCSRF == false) ) {
		$nonce = $_POST['nonce'];
		if(!check_nonce($nonce, 'modify_components')) {
			die('CSRF detected!');
		}
	}

	# create backup file for undo
	createBak($file, $path, $bakpath);

	# start creation of top of components.xml file
	$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><channel></channel>');
	$components = array();
	if (isset($_POST['components'])) {
		foreach($_POST['components'] as $component) {
			if (!isset($component['title']) || trim($component['title']) == '') {
				$component['title'] = uniqid('Component ');
			} else {
				$component['title'] = safe_slash_html(trim($component['title']));
			}
			if (!isset($component['slug']) || trim($component['slug']) == '') {
				$slug = clean_url(to7bit(trim($component['title'])), 'UTF-8');
				if ($slug) {
					$component['slug'] = $slug;
				} else {
					$component['slug'] = uniqid('component-');
				}
			}
			if (isset($component['value'])) {
				$component['value'] = safe_slash_html($component['value']);
			} else {
				$component['value'] = '';
			}
			$component['enabled'] = isset($component['enabled']) ? $component['enabled'] : '';
			$components[] = $component;
		}
	}

	if ($components) {
		$components = subval_sort($components, 'title');
		foreach ($components as $component) {
			$item = $xml->addChild('item');
			$item->addChild('title')->addCData($component['title']);
			$item->addChild('slug', $component['slug']);
			$item->addChild('enabled', $component['enabled']);
			$item->addChild('value')->addCData($component['value']);
		}
	}

	exec_action('component-save');
	XMLsave($xml, $path . $file);
	redirect('components.php?upd=comp-success');
}

# if undo was invoked
if (isset($_GET['undo'])) {
	# check for csrf
	$nonce = $_GET['nonce'];
	if(!check_nonce($nonce, "undo")) {
		die("CSRF detected!");
	}
	# perform the undo
	undo($file, $path, $bakpath);
	redirect('components.php?upd=comp-restored');
}

# create components form html
$data = getXML($path . $file);
$components = $data->item;
$count= 0;
if (count($components) != 0) {
	foreach ($components as $component) {
		$checked = (isset($component->enabled) && $component->enabled == '1') ? ' checked ' : '';
		$table .= '<div class="compdiv" id="section-' . $count . '"><table class="comptable" ><tr><td><b title="' . i18n_r('DOUBLE_CLICK_EDIT').'" class="editable">' . stripslashes($component->title) . '</b></td>';
		$table .= '<td style="text-align:right;" ><code>&lt;?php get_component(<span class="compslugcode">\'' . $component->slug . '\'</span>); ?&gt;</code></td><td class="delete" >';
		$table .= '<a href="#" title="'.i18n_r('DELETE_COMPONENT') . ': ' . cl($component->title). '?" class="delcomponent" rel="' . $count . '" >&times;</a></td></tr><tr><td colspan="3" class="inline"><input type="checkbox" name="components[' . $count . '][enabled]" value="1"' . $checked . '>&nbsp;<label for="components[' . $count . '][enabled]">' . i18n_r('ENABLE_COMPONENT') . '</label></td></tr></table>';
		$table .= '<label for="components[' . $count . '][value]" style="display: none;">' . i18n_r('COMPONENT_CODE') . ':</label><textarea class="text" id="components[' . $count . '][value]" name="components[' . $count . '][value]">' . stripslashes($component->value) . '</textarea>';
		$table .= '<input type="hidden" class="compslug" name="components[' . $count . '][slug]" value="' . $component->slug . '" />';
		$table .= '<input type="hidden" class="comptitle" name="components[' . $count . '][title]" value="' . stripslashes($component->title) . '" />';
		$table .= '<input type="hidden" name="components[' . $count . '][id]" value="' . $count . '" />';
		exec_action('component-extras');
		$table .= '</div>';
		$count++;
	}
}
	# create list to show on sidebar for easy access
	$listc = ''; $submitclass = '';
	if($count > 1) {
		$item = 0;
		foreach($components as $component) {
			$listc .= '<a id="divlist-' . $item . '" href="#section-' . $item . '" class="component">' . $component->title . '</a>';
			$item++;
		}
	} elseif ($count == 0) {
		$submitclass = 'hidden';
	}
# register and queue CodeMirror files
if ($datau->CODEEDITOR == 1) {
	register_script('codemirror', $SITEURL . $GSADMIN . '/template/js/codemirror/lib/codemirror-compressed.js', '0.2.0', false);
	register_style('codemirror-css', $SITEURL . $GSADMIN . '/template/js/codemirror/lib/codemirror.css','screen', false);
	register_style('codemirror-theme', $SITEURL . $GSADMIN . '/template/js/codemirror/theme/default.css','screen', false);

	queue_script('codemirror', GSBACK);
	queue_style('codemirror-css', GSBACK);
	queue_style('codemirror-theme', GSBACK);
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('COMPONENTS'));

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent clearfix">

	<div id="maincontent">
	<div class="main">
	<h3 class="floated"><?php echo i18n('EDIT_COMPONENTS');?></h3>
	<div class="edit-nav" >
		<a href="#" id="addcomponent" accesskey="<?php echo find_accesskey(i18n_r('ADD_COMPONENT'));?>" ><?php i18n('ADD_COMPONENT');?></a>
		<div class="clear"></div>
	</div>

	<form class="manyinputs" action="<?php myself(); ?>" method="post" accept-charset="utf-8" >
		<input type="hidden" id="id" value="<?php echo $count; ?>" />
		<input type="hidden" id="nonce" name="nonce" value="<?php echo get_nonce("modify_components"); ?>" />

		<div id="divTxt"></div>
		<?php echo $table; ?>
		<?php
			if ($datau->CODEEDITOR == '1') {
		?>
		<style>
			.compdiv .CodeMirror, .compdiv .CodeMirror-scroll { height: <?php echo $EDHEIGHT; ?>; }
		</style>
		<script>
			GS.CodeMirror = new Array();
			GS.CodeMirror['enabled'] = true;
			GS.CodeMirror['options'] = {mode: 'application/x-httpd-php'};
			// Add Codemirror to all existed components
			document.querySelectorAll('.compdiv textarea').forEach(function(elem) {
				addCodeMirror(elem, GS.CodeMirror['options']);
			});
		</script>
		<?php
			}
		?>
		<script>
			GS.i18n['TITLE'] = "<?php i18n('TITLE'); ?>";
			GS.i18n['DELETE_COMPONENT'] = "<?php i18n('DELETE_COMPONENT'); ?>";
			GS.i18n['ENABLE_COMPONENT'] = "<?php i18n('ENABLE_COMPONENT'); ?>";
			GS.i18n['COMPONENT_CODE'] = "<?php i18n('COMPONENT_CODE'); ?>";
		</script>
		<p id="submit_line" class="<?php echo $submitclass; ?>">
			<span><input type="submit" class="submit" name="submitted" id="button" value="<?php i18n('SAVE_COMPONENTS');?>" /></span> &nbsp;&nbsp;<?php i18n('OR'); ?>&nbsp;&nbsp; <a class="cancel" href="components.php?cancel"><?php i18n('CANCEL'); ?></a>
		</p>
	</form>
	</div>
	</div>
	
	<div id="sidebar">
		<?php include('template/sidebar-theme.php'); ?>
		<?php if ($listc != '') { echo '<div class="compdivlist">' . $listc . '</div>'; } ?>
	</div>

</div>
<?php get_template('footer'); ?>