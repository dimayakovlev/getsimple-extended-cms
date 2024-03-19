<?php
/**
 * System Page Edit
 *
 * Edit website system pages.
 *
 * @package GetSimple Extended
 * @subpackage System-Page-Edit
 */

declare(strict_types=1);

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('inc/common.php');

// Variable settings
$userid = login_cookie_check();

// Get passed variables
$id = (string)filter_input(INPUT_GET, 'id', FILTER_SANITIZE_URL);
$ptype = (string)filter_input(INPUT_GET, 'type');
$autosave = isset($_GET['autosave']);

if ($id == '' || !in_array($id, getSystemPagesSlugs())) redirect('pages.php');

$file = GSDATAOTHERPATH . ($autosave ? 'autosave/' : '') . $id . '.xml';
if (!file_exists($file)) redirect('pages.php?error=' . urlencode(i18n_r('PAGE_NOTEXIST')));

$data_edit = getXML($file);

if (!is_object($data_edit)) redirect('pages.php');

$HTMLEDITOR = (string)$datau->enableHTMLEditor;

// get saved page data
$title = stripslashes((string)$data_edit->title);
$pubDate = (string)$data_edit->pubDate;
$url = (string)$data_edit->url;
$content = stripslashes((string)$data_edit->content);
$template = (string)$data_edit->template;
$parent = (string)$data_edit->parent;
$author = (string)$data_edit->author ?: $USR;
$lang = stripslashes((string)$data_edit->lang);
$buttonname = i18n_r('BTN_SAVEUPDATES');
$creDate = (string)$data_edit->creDate ?: $pubDate;
$publisher = (string)$data_edit->publisher ?: $author;
$attributes['auto-open-metadata'] = ($data_edit->attributes()->autoOpenMetadata == '1');
$attributes['disable-editor'] = ($data_edit->attributes()->disableEditor == '1');
$attributes['revision-number'] = (string)$data_edit->attributes()->revisionNumber ?: '0';

$themes_path = GSTHEMESPATH . $TEMPLATE;
$themes_handle = opendir($themes_path) or die('Unable to open ' . GSTHEMESPATH);
$templates = array();
while ($file = readdir($themes_handle)) {
	if (isFile($file, $themes_path, 'php')) {
		if ($file != 'functions.php' && substr(strtolower($file), -8) != '.inc.php' && substr($file, 0, 1) !== '.') $templates[] = $file;
	}
}

sort($templates);

if ($template = '' || !in_array($template, $templates)) $template = 'template.php';

$theme_templates = '';
foreach ($templates as $file) {
	$sel = ($template == $file) ? 'selected' : '';
	$templatename = ($file == 'template.php') ? i18n_r('DEFAULT_TEMPLATE') : $file;
	$theme_templates .= '<option ' . $sel . ' value="' . $file . '">' . $templatename . '</option>';
}

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('EDIT') . ' ' . $title);

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">

		<h3><?php echo i18n_r('PAGE_EDIT_MODE'), ' ', $id; ?></h3>

		<!-- pill edit navigation -->
		<div class="edit-nav">
			<a href="#" id="metadata_toggle" accesskey="<?php echo find_accesskey(i18n_r('PAGE_OPTIONS'));?>" class="<?php if ($attributes['auto-open-metadata'] == true) { echo 'current'; } ?>"><?php i18n('PAGE_OPTIONS'); ?></a>
		</div>

		<form class="largeform" id="editform" action="changedata.php" method="post" accept-charset="utf-8">
			<input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce('save', 'edit-system.php'); ?>">
			<input id="author" name="post-author" type="hidden" value="<?php echo $author; ?>">
			<input id="creDate" name="post-creDate" type="hidden" value="<?php echo $creDate; ?>">
			<input id="action" name="action" type="hidden" value="save">
			<input id="auto-open-metadata" name="auto-open-metadata" type="hidden" value="<?php echo (string)$attributes['auto-open-metadata']; ?>">
			<input id="revision-number" name="revision-number" type="hidden" value="<?php echo $attributes['revision-number']; ?>">
			<!-- page title toggle screen -->
			<p class="widesec">
				<label for="post-title" style="display: none;"><?php i18n('PAGE_TITLE'); ?></label>
				<input class="text title" id="post-title" name="post-title" type="text" value="<?php echo $title; ?>" placeholder="<?php i18n('PAGE_TITLE'); ?>" required>
			</p>
			<!-- metadata toggle screen -->
			<div style="display: <?php echo ($attributes['auto-open-metadata'] == true) ? 'block' : 'none' ?>;" id="metadata_window">
			<div class="wrapper">
			<div class="leftsec">
				<p>
					<label for="post-template"><?php i18n('TEMPLATE'); ?>:</label>
					<select id="post-template" name="post-template"><?php echo $theme_templates; ?></select>
				</p>
				<p class="inline<?php echo $HTMLEDITOR != '1' ? ' disabled' : ''; ?>"><?php if ($HTMLEDITOR != '1') { ?><input id="disable-editor" name="disable-editor" type="hidden" value="<?php echo (string)$attributes['disable-editor']; ?>"><?php } ?>
					<input type="checkbox" id="disable-editor<?php echo $HTMLEDITOR != '1' ? '-1' : ''; ?>" name="disable-editor<?php echo $HTMLEDITOR != '1' ? '-1' : ''; ?>" value="1"<?php echo $attributes['disable-editor'] ? ' checked="checked"' : ''; echo $HTMLEDITOR != '1' ? ' disabled="disabled"' : ''; ?>> <label for="disable-editor"><?php i18n('PAGE_DISABLE_HTML_EDITOR'); ?></label>
				</p>
			</div>
			<div class="rightsec">
				<p>
					<label for="post-id"><?php i18n('SLUG_URL'); ?>:</label>
					<input type="text" id="post-id" name="post-id" value="<?php echo $url; ?>" readonly>
				</p>
				<p>
					<label for="post-lang"><?php i18n('LABEL_CONTENTLANG'); ?>:</label>
					<input id="post-lang" name="post-lang" type="text" value="<?php echo $lang; ?>" placeholder="<?php if ((string)$dataw->lang != '') { echo $dataw->lang; } else { echo substr($LANG, 0, 2); } ?>" pattern="[a-zA-Z]{2}">
					<?php if ($lang !='' && preg_match('/^[a-zA-Z]{2}$/', $lang) != 1) echo '<span class="attention">' . i18n_r('WARN_LANGINVALID') .'</span>'; ?>
				</p>
			</div>
			<?php exec_action('edit-extras'); ?>
			</div>
			</div><!-- / metadata toggle screen -->
			<!-- page body -->
			<p>
				<label for="post-content" style="display:none;"><?php i18n('LABEL_PAGEBODY'); ?></label>
				<textarea id="post-content" name="post-content"><?php echo $content; ?></textarea>
			</p>

			<?php exec_action('edit-content'); ?>

			<?php if (isset($data_edit)) echo '<input type="hidden" name="existing-url" value="' . $url . '">'; ?>

			<span class="editing"><?php echo i18n_r('EDITPAGE_TITLE') . ': ' . $title; ?></span>
			<div id="submit_line">
				<input type="hidden" name="redirectto" value="">
				<span><input id="page_submit" class="submit" type="submit" name="submitted" value="<?php echo $buttonname; ?>"></span>
				<div id="dropdown">
					<h6 class="dropdownaction"><?php i18n('ADDITIONAL_ACTIONS'); ?></h6>
					<ul class="dropdownmenu">
						<li id="save-close"><a href="#"><?php i18n('SAVE_AND_CLOSE'); ?></a></li>
						<li id="cancel-updates" class="alertme"><a href="pages.php?cancel"><?php i18n('CANCEL'); ?></a></li>
					</ul>
				</div>
			</div>

			<?php if ($url != '') { ?>
				<p class="backuplink"><?php
					if (isset($pubDate)) echo sprintf(i18n_r('LAST_SAVED'), '<em>' . ($publisher ?: '-') . '</em>', lngDate($pubDate));
					if (exists_system_bak($url)) echo ' &bull; <a href="backup-edit.php?p=view-system&amp;id=' . $url . '">' . i18n_r('BACKUP_AVAILABLE') . '</a>';
				?></p>
			<?php } ?>

		</form>

		<?php
			if (isset($EDTOOL)) $EDTOOL = returnJsArray($EDTOOL);
			if (isset($toolbar)) $toolbar = returnJsArray($toolbar); // handle plugins that corrupt this
			else if (strpos(trim($EDTOOL), '[[') !== 0 && strpos(trim($EDTOOL), '[') === 0) { $EDTOOL = '[$EDTOOL]'; }
			if(isset($toolbar) && strpos(trim($toolbar), '[[') !== 0 && strpos($toolbar, '[') === 0) { $toolbar = '[$toolbar]'; }
			$toolbar = isset($EDTOOL) ? ', toolbar: ' . trim($EDTOOL, ',') : '';
			$options = isset($EDOPTIONS) ? ',' . trim($EDOPTIONS, ',') : '';
		?>
		<?php if ($HTMLEDITOR == '1' && $attributes['disable-editor'] !== true) { ?>
		<script type="text/javascript" src="template/js/ckeditor/ckeditor.js<?php echo getDef('GSCKETSTAMP', true) ? '?t=' . getDef('GSCKETSTAMP') : ''; ?>"></script>
		<script type="text/javascript">
			<?php if (getDef('GSCKETSTAMP', true)) echo "CKEDITOR.timestamp = '" . getDef("GSCKETSTAMP") . "';\n"; ?>
			var editor = CKEDITOR.replace('post-content', {
				skin : 'getsimple',
				forcePasteAsPlainText : true,
				language : '<?php echo $EDLANG; ?>',
				defaultLanguage : 'en',
				<?php if (file_exists(GSTHEMESPATH . $TEMPLATE . '/editor.css')) { ?>contentsCss: '<?php echo suggest_site_path(); ?>theme/<?php echo $TEMPLATE; ?>/editor.css',<?php } ?>
				entities : false,
				// uiColor : '#FFFFFF',
				height: '<?php echo $EDHEIGHT; ?>',
				baseHref : '<?php echo $SITEURL; ?>',
				tabSpaces:10,
				filebrowserBrowseUrl : 'filebrowser.php?type=all',
				filebrowserImageBrowseUrl : 'filebrowser.php?type=images',
				filebrowserWindowWidth : '730',
				filebrowserWindowHeight : '500'
				<?php echo $toolbar; ?>
				<?php echo $options; ?>
			});

			CKEDITOR.instances['post-content'].on('instanceReady', InstanceReadyEvent);

			function InstanceReadyEvent(ev) {
				_this = this;

				this.document.on('keyup', function() {
					$('#editform #post-content').trigger('change');
					_this.resetDirty();
				});

				this.timer = setInterval(function() { trackChanges(_this) }, 500);
			}

			/**
			 * keep track of changes for editor
			 * until cke 4.2 is released with onchange event
			 */
			function trackChanges(editor) {
				// console.log('check changes');
				if (editor.checkDirty()) {
					$('#editform #post-content').trigger('change');
					editor.resetDirty();
				}
			};

			</script>

			<?php
				# CKEditor setup functions
				ckeditor_add_page_link();
				exec_action('html-editor-init');
			?>

		<?php } ?>

		<script type="text/javascript">
			/* Warning for unsaved Data */
			var warnme = false;
			var pageisdirty = false;

			$('#cancel-updates').hide();

			window.onbeforeunload = function () {
				if (warnme || pageisdirty == true) return "<?php i18n('UNSAVED_INFORMATION'); ?>";
			}

			$('#editform').submit(function() {
				warnme = false;
				return checkTitle();
			});

			checkTitle = function() {
				if ($.trim($("#post-title").val()).length == 0) {
					alert("<?php i18n('CANNOT_SAVE_EMPTY'); ?>");
					return false;
				}
			}

			jQuery(document).ready(function() {

			<?php if (defined('GSAUTOSAVE') && (int)GSAUTOSAVE > 0) { /* IF AUTOSAVE IS TURNED ON via GSCONFIG.PHP */ ?>

					$('#pagechangednotify').hide();
					$('#autosavenotify').show();
					$('#autosavenotify').html('Autosaving is <b>ON</b> (<?php echo (int)GSAUTOSAVE; ?> s)');

					function autoSaveIntvl() {
						//console.log('autoSaveIntvl called, isdirty:' + pageisdirty);
						if (pageisdirty == true) {
							autoSave();
							pageisdirty = false;
						}
					}

					function autoSave() {
						//$('input[type=submit]').attr('disabled', 'disabled');

						// we are using ajax, so ckeditor wont copy data to our textarea for us, so we do it manually
						if (typeof(editor) != 'undefined') { $('#post-content').val(CKEDITOR.instances['post-content'].getData()); }

						var dataString = $('#editform').serialize();

						$.ajax({
							type: 'POST',
							url: 'changedata.php',
							data: dataString + '&autosave=true&submitted=true',
							dataType: 'json',
							success: function(response) {
								if (response.message == 'OK') {
									$('#autosavenotify').text("<?php i18n('AUTOSAVE_NOTIFY'); ?> " + response.date);
									$('#pagechangednotify').hide();
									$('#pagechangednotify').text('');
									$('input[type=submit]').removeClass('warning');
									warnme = false;
									$('#cancel-updates').hide();
								} else {
									pageisdirty = true;
									$('#autosavenotify').text("<?php i18n('AUTOSAVE_FAILED'); ?> " + response.date);
									$('#pagechangednotify').show();
									$('#pagechangednotify').text("<?php i18n('PAGE_UNSAVED')?>");
									$('input[type=submit]').addClass('warning');
									$('#cancel-updates').show();
								}
							}
						});
					}

					// We register title changes with change() which only fires when you lose focus to prevent midchange saves.
					$('#post-title').change(function() {
						$('#editform #post-content').trigger('change');
					});

					// We register all other form elements to detect changes of any type by using bind
					$('#editform input, #editform textarea, #editform select').not('#post-title').bind('change keypress paste textInput input', function() {
						pageisdirty = true;
						warnme = true;
						autoSaveInd();
					});

					setInterval(autoSaveIntvl, <?php echo (int)GSAUTOSAVE * 1000; ?>);

				<?php } else { /* AUTOSAVE IS NOT TURNED ON */ ?>
					$('#editform').bind('change keypress paste focus textInput input', function() {
						warnme = true;
						pageisdirty = false;
						autoSaveInd();
					});
				<?php } ?>
					function autoSaveInd() {
						$('#pagechangednotify').show();
						$('#pagechangednotify').text("<?php i18n('PAGE_UNSAVED')?>");
						$('input[type=submit]').addClass('warning');
						$('#cancel-updates').show();
					}

			});
		</script>
	</div>
	</div><!-- end maincontent -->

	<div id="sidebar"><?php include('template/sidebar-pages.php'); ?></div>

</div>
<?php get_template('footer'); ?>
