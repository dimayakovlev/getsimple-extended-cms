<?php
/**
 * Page Edit
 *
 * Edit or create new pages for the website.
 *
 * @package GetSimple Extended
 * @subpackage Page-Edit
 */

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('inc/common.php');

// Variable settings
$userid = login_cookie_check();

// Get passed variables
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_URL);
$uri = filter_input(INPUT_GET, 'uri', FILTER_SANITIZE_URL);
$ptype = filter_input(INPUT_GET, 'type');
$nonce = filter_input(INPUT_GET, 'nonce');
$path  = GSDATAPAGESPATH;

$HTMLEDITOR = (string)$datau->enableHTMLEditor;

// Page variables reset
$theme_templates = '';
$parents_list = '';
$keytags = '';
$parent = '';
$template = '';
$menuStatus = '';
$private = '';
$menu = '';
$content = '';
$author = $USR;
$publisher = '';
$title = '';
$url = '';
$metak = '';
$metad = '';
$creDate = '';
$lang = '';
$permalink = '';

if ($id) {
	// get saved page data
	$file = $id . '.xml';
	if (!file_exists($path . $file)) redirect('pages.php?error=' . urlencode(i18n_r('PAGE_NOTEXIST')));
	$data_edit = getXML($path . $file);
	$title = stripslashes($data_edit->title);
	$pubDate = (string)$data_edit->pubDate;
	$metak = stripslashes($data_edit->meta);
	$metad = stripslashes($data_edit->metad);
	$url = (string)$data_edit->url;
	$content = stripslashes($data_edit->content);
	$template = (string)$data_edit->template;
	$parent = (string)$data_edit->parent;
	$author = (string)$data_edit->author;
	$menu = stripslashes($data_edit->menu);
	$private = (string)$data_edit->private;
	$menuStatus = (string)$data_edit->menuStatus;
	$menuOrder = (string)$data_edit->menuOrder;
	$lang = stripslashes($data_edit->lang);
	$buttonname = i18n_r('BTN_SAVEUPDATES');
	$creDate = (string)$data_edit->creDate ?: $pubDate;
	$publisher = (string)$data_edit->publisher ?: $author;
	$permalink = (string)$data_edit->permalink;
	$image = (string)$data_edit->image;
	$attributes['auto-open-metadata'] = ($data_edit->attributes()->autoOpenMetadata == '1');
	$attributes['disable-editor'] = ($data_edit->attributes()->disableEditor == '1');
	$attributes['revision-number'] = (string)$data_edit->attributes()->revisionNumber ?: '0';
} else {
	// prefill fields is provided
	$title = filter_var(trim(xss_clean(filter_input(INPUT_GET, 'title') ?: '')), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$template =  filter_input(INPUT_GET, 'template', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$parent = filter_input(INPUT_GET, 'parent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$menu = filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_GET, 'menu') ?: ''))), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$private  =  isset($_GET['private']) ? var_out($_GET['private']) : '';
	$menuStatus =  isset($_GET['menuStatus']) ? var_out($_GET['menuStatus']) : '';
	$menuOrder = filter_input(INPUT_GET, 'menuOrder', FILTER_SANITIZE_NUMBER_INT);
	$lang = filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_GET, 'lang') ?: ''))), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$permalink = filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_GET, 'permalink', FILTER_SANITIZE_URL) ?: ''))), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$image = filter_var(trim(strip_tags(xss_clean(filter_input(INPUT_GET, 'image', FILTER_SANITIZE_URL) ?: ''))), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$buttonname = i18n_r('BTN_SAVEPAGE');
	$attributes['auto-open-metadata'] = filter_input(INPUT_GET, 'autoOpenMetadata', FILTER_VALIDATE_BOOLEAN);
	$attributes['disable-editor'] = filter_input(INPUT_GET, 'disableEditor', FILTER_VALIDATE_BOOLEAN);
	$attributes['revision-number'] = '0';
}

// MAKE SELECT BOX OF AVAILABLE TEMPLATES
if ($template == '') $template = 'template.php';

$themes_path = GSTHEMESPATH . $TEMPLATE;
$themes_handle = opendir($themes_path) or die('Unable to open ' . GSTHEMESPATH);
$templates = array();
while ($file = readdir($themes_handle)) {
	if (isFile($file, $themes_path, 'php')) {
		if ($file != 'functions.php' && substr(strtolower($file), -8) != '.inc.php' && substr($file, 0, 1) !== '.') $templates[] = $file;
	}
}

sort($templates);

foreach ($templates as $file) {
	$sel = ($template == $file) ? 'selected' : '';
	$templatename = ($file == 'template.php') ? i18n_r('DEFAULT_TEMPLATE') : $file;
	$theme_templates .= '<option ' . $sel.' value="' . $file . '">' . $templatename . '</option>';
}

// SETUP CHECKBOXES
$sel_m = ($menuStatus != '') ? 'checked' : '';
if ($menu == '') $menu = $title;

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('EDIT') . ' ' . $title);

?>

<?php include('template/include-nav.php'); ?>

<div class="bodycontent">

	<div id="maincontent">
		<div class="main">

		<h3><?php if (isset($data_edit)) { i18n('PAGE_EDIT_MODE'); } else { i18n('CREATE_NEW_PAGE'); } ?></h3>

		<!-- pill edit navigation -->
		<div class="edit-nav">
			<?php
				if (isset($id)) echo '<a href="', find_url($url), '" target="_blank" accesskey="', find_accesskey(i18n_r('VIEW')), '">', i18n_r('VIEW'), '</a>';
			?>
			<a href="#" id="metadata_toggle" accesskey="<?php echo find_accesskey(i18n_r('PAGE_OPTIONS'));?>" class="<?php if ($attributes['auto-open-metadata'] == true) { echo 'current'; } ?>"><?php i18n('PAGE_OPTIONS'); ?></a>
		</div>

		<form class="largeform" id="editform" action="changedata.php" method="post" accept-charset="utf-8">
			<input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce('save', 'edit.php'); ?>">
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
				<p id="post-private-wrap">
					<label for="post-private"<?php if ($private) echo ' class="is-private"'; ?>><?php i18n('KEEP_PRIVATE'); ?>:</label>
					<select id="post-private" name="post-private">
						<option value=""><?php i18n('NORMAL'); ?></option>
						<option value="1"<?php if ($private == 'Y' || $private == '1') echo ' selected'; ?>><?php echo ucwords(i18n_r('PRIVATE_SUBTITLE')); ?></option>
						<option value="2"<?php if ($private == '2') echo ' selected'; ?>><?php echo ucwords(i18n_r('NOT_PUBLISHED_SUBTITLE')); ?></option>
					</select>
				</p>
				<p>
					<label for="post-parent"><?php i18n('PARENT_PAGE'); ?>:</label>
					<select id="post-parent" name="post-parent">
					<?php
						getPagesXmlValues();
						$count = 0;
						foreach ($pagesArray as $page) {
							$sort = $page['parent'] != '' ? returnPageField($page['parent'], 'title') . $page['title'] : $page['title'];
							$page = array_merge($page, array('sort' => $sort));
							$pagesArray_tmp[$count] = $page;
							$count++;
						}
						// $pagesArray = $pagesArray_tmp;
						$pagesSorted = subval_sort($pagesArray_tmp,'sort');
						$ret = get_pages_menu_dropdown('', '', 0);
						$ret = str_replace('value="' . $id . '"', 'value="' . $id . '" disabled', $ret);

						// Create base option
						echo '<option '. ($parent == '' ? 'selected' : '') . ' value="">' . htmlspecialchars('< ' . i18n_r('NO_PARENT') . ' >') . '</option>';
						echo $ret;
					?>
					</select>
				</p>
				<p>
					<label for="post-template"><?php i18n('TEMPLATE'); ?>:</label>
					<select id="post-template" name="post-template"><?php echo $theme_templates; ?></select>
				</p>
				<p class="inline<?php echo $HTMLEDITOR != '1' ? ' disabled' : ''; ?>"><?php if ($HTMLEDITOR != '1') { ?><input id="disable-editor" name="disable-editor" type="hidden" value="<?php echo (string)$attributes['disable-editor']; ?>"><?php } ?>
					<input type="checkbox" id="disable-editor<?php echo $HTMLEDITOR != '1' ? '-1' : ''; ?>" name="disable-editor<?php echo $HTMLEDITOR != '1' ? '-1' : ''; ?>" value="1"<?php echo $attributes['disable-editor'] ? ' checked="checked"' : ''; echo $HTMLEDITOR != '1' ? ' disabled="disabled"' : ''; ?>> <label for="disable-editor"><?php i18n('PAGE_DISABLE_HTML_EDITOR'); ?></label>
				</p>
				<p class="inline post-menu">
					<input type="checkbox" id="post-menu-enable" name="post-menu-enable" value="1" <?php echo $sel_m; ?>> <label for="post-menu-enable"><?php i18n('ADD_TO_MENU'); ?></label><a href="navigation.php" class="viewlink" rel="facybox"><img src="template/images/search.png" id="tick" alt="<?php echo strip_tags(i18n_r('VIEW')); ?>"></a>
				</p>
				<div id="menu-items">
					<div>
						<label for="post-menu"><?php i18n('MENU_TEXT'); ?></label><input class="text" id="post-menu" name="post-menu" type="text" value="<?php echo $menu; ?>">
					</div>
					<div>
						<label for="post-menu-order"><?php i18n('PRIORITY'); ?></label><select class="text" id="post-menu-order" name="post-menu-order">
							<option value=""<?php echo $menuOrder == 0 ? ' selected' : ''; ?>>-</option>
							<?php
							$i = 1;
							while ($i <= 30) {
								echo '<option value="' . $i . '"' . ($menuOrder == $i ? ' selected' : '') . '>' . $i . '</option>';
								$i++;
							}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="rightsec">
				<p>
					<label for="post-id"><?php i18n('SLUG_URL'); ?>:</label>
					<input type="text" id="post-id" name="post-id" value="<?php echo $url; ?>" <?php echo ($url=='index' ? 'readonly="readonly"' : ''); ?>>
				</p>
				<p>
					<label for="post-permalink"><?php i18n('PERMALINK'); ?>:</label>
					<input type="text" id="post-permalink" name="post-permalink" value="<?php echo $permalink; ?>" placeholder="<?php echo $PERMALINK ? htmlspecialchars($PERMALINK, ENT_QUOTES) : '%parent%/%slug%/'; ?>">
				</p>
				<p>
					<label for="post-image"><?php i18n('LABEL_IMAGE'); ?>:<?php if ($image) { ?> <span class="right"><a href="<?php echo $image;?>" rel="facybox_i" target="_blank"><?php i18n('PREVIEW'); ?></a></span><?php } ?></label>
					<input id="post-image" name="post-image" type="text" value="<?php echo $image; ?>">
				</p>
				<p>
					<label for="post-lang"><?php i18n('LABEL_CONTENTLANG'); ?>:</label>
					<input id="post-lang" name="post-lang" type="text" value="<?php echo $lang; ?>" placeholder="<?php if ((string)$dataw->lang != '') { echo $dataw->lang; } else { echo substr($LANG, 0, 2); } ?>" pattern="[a-zA-Z]{2}">
					<?php if ($lang !='' && preg_match('/^[a-zA-Z]{2}$/', $lang) != 1) echo '<span class="attention">' . i18n_r('WARN_LANGINVALID') .'</span>'; ?>
				</p>
				<p>
					<label for="post-metak"><?php i18n('TAG_KEYWORDS'); ?>:</label>
					<input id="post-metak" name="post-metak" type="text" value="<?php echo $metak; ?>" />
				</p>
				<p>
					<label for="post-metad" class="clearfix"><?php i18n('META_DESC'); ?>: <span id="countdownwrap" class="right"><span id="countdown"></span> <?php i18n('REMAINING'); ?></span></label>
					<textarea class="text" id="post-metad" name="post-metad"><?php echo $metad; ?></textarea>
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
						<?php if ($url != '') { ?>
						<li><a href="pages.php?id=<?php echo $url; ?>&amp;action=clone&amp;nonce=<?php echo get_nonce('clone', 'pages.php'); ?>"><?php i18n('CLONE'); ?></a></li>
						<?php } ?>
						<li id="cancel-updates" class="alertme"><a href="pages.php?cancel"><?php i18n('CANCEL'); ?></a></li>
						<?php if ($url != 'index' && $url != '') { ?>
						<li class="alertme"><a href="deletefile.php?id=<?php echo $url; ?>&amp;nonce=<?php echo get_nonce('delete', 'deletefile.php'); ?>"><?php echo strip_tags(i18n_r('ASK_DELETE')); ?></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>

			<?php if ($url != '') { ?>
				<p class="backuplink"><?php
					if (isset($pubDate)) echo sprintf(i18n_r('LAST_SAVED'), '<em>' . ($publisher ?: '-') . '</em>', lngDate($pubDate));
					if (exists_bak($url)) echo ' &bull; <a href="backup-edit.php?p=view&amp;id=' . $url . '">' . i18n_r('BACKUP_AVAILABLE') . '</a>';
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

			CKEDITOR.instances["post-content"].on("instanceReady", InstanceReadyEvent);

			function InstanceReadyEvent(ev) {
				_this = this;

				this.document.on("keyup", function() {
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
			var yourText = null;
			var warnme = false;
			var pageisdirty = false;

			$('#cancel-updates').hide();

			window.onbeforeunload = function () {
				if (warnme || pageisdirty == true) {
					return "<?php i18n('UNSAVED_INFORMATION'); ?>";
				}
			}

			$('#editform').submit(function() {
				warnme = false;
				return checkTitle();
			});

			checkTitle = function() {
				if($.trim($("#post-title").val()).length == 0) {
					alert("<?php i18n('CANNOT_SAVE_EMPTY'); ?>");
					return false;
				}
			}

			jQuery(document).ready(function() {

			<?php if (defined('GSAUTOSAVE') && (int)GSAUTOSAVE != 0) { /* IF AUTOSAVE IS TURNED ON via GSCONFIG.PHP */ ?>

					$('#pagechangednotify').hide();
					$('#autosavenotify').show();
					$('#autosavenotify').html('Autosaving is <b>ON</b> (<?php echo (int)GSAUTOSAVE; ?> s)');

					function autoSaveIntvl(){
						// console.log('autoSaveIntvl called, isdirty:' + pageisdirty);
						if(pageisdirty == true) {
							autoSave();
							pageisdirty = false;
						}
					}

					function autoSave() {
						$('input[type=submit]').attr('disabled', 'disabled');

						// we are using ajax, so ckeditor wont copy data to our textarea for us, so we do it manually
						if (typeof(editor) != 'undefined') { $('#post-content').val(CKEDITOR.instances["post-content"].getData()); }

						var dataString = $("#editform").serialize();
						
						// not internalionalized or using GS date format!
						var currentTime = new Date();
						var hours = currentTime.getHours();
						var minutes = currentTime.getMinutes();
						if (minutes < 10) { minutes = "0" + minutes; }
						if (hours > 11) { daypart = "PM"; } else { daypart = "AM"; }
						if (hours > 12) { hours -= 12; }

						$.ajax({
							type: "POST",
							url: "changedata.php",
							data: dataString + '&autosave=true&submitted=true',
							success: function(msg) {
								if (msg.toString() == 'OK') {
									$('#autosavenotify').text("<?php i18n('AUTOSAVE_NOTIFY'); ?> " + hours + ":" + minutes + " " + daypart);
									$('#pagechangednotify').hide();
									$('#pagechangednotify').text('');
									$('input[type=submit]').attr('disabled', false);
									$('input[type=submit]').css('border-color','#ABABAB');
									warnme = false;
									$('#cancel-updates').hide();
								} else {
									pageisdirty = true;
									$('#autosavenotify').text("<?php i18n('AUTOSAVE_FAILED'); ?>");
								}
							}
						});
					}

					// We register title and slug changes with change() which only fires when you lose focus to prevent midchange saves.
					$('#post-title, #post-id').change(function() {
						$('#editform #post-content').trigger('change');
					});

					// We register all other form elements to detect changes of any type by using bind
					$('#editform input, #editform textarea, #editform select').not('#post-title').not('#post-id').bind('change keypress paste textInput input', function() {
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

	<div id="sidebar">
		<?php include('template/sidebar-pages.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>
