<?php
/**
 * Menu Manager
 *
 * Allows you to edit the current main menu hierarchy  
 *
 * @package GetSimple Extended
 * @subpackage Page-Edit
 */

# Setup
$load['plugin'] = true;
include('inc/common.php');
login_cookie_check();

# get pages
getPagesXmlValues();
$pagesSorted = subval_sort($pagesArray, 'menuOrder');

get_template('header', cl($SITENAME) . ' &raquo; ' . i18n_r('PAGE_MANAGEMENT') . ' &raquo; ' . str_replace(array('<em>', '</em>'), '', i18n_r('MENU_MANAGER')));

include('template/include-nav.php');

?>
<div class="bodycontent">

	<div id="maincontent">
		<div class="main">
			<h3><?php echo str_replace(array('<em>','</em>'), '', i18n_r('MENU_MANAGER')); ?></h3>
			<p><?php i18n('MENU_MANAGER_DESC'); ?></p>
			<?php
				if (count($pagesSorted) != 0) {
					echo '<form method="post" action="changedata.php">';
					echo '<ul id="menu-order">';
					foreach ($pagesSorted as $page) {
						if ($page['menuStatus'] == '') continue;
						if ($page['menuOrder'] == '') $page['menuOrder'] = 'N/A';
						if ($page['menu'] == '') $page['menu'] = $page['title'];
						echo '<li rel="' . $page['slug'] . '"><span class="order">#' . $page['menuOrder'] . '</span><span class="menu">' . $page['menu'] . '</span><span class="title">' . $page['title'] . '</span></li>';
					}
					echo '</ul>';
					echo '<input type="hidden" name="action" value="save">';
					echo '<input type="hidden" name="nonce" value="' . get_nonce('save', 'menu-manager.php') . '">';
					echo '<input type="hidden" name="menuOrder" value="">';
					echo '<p id="submit_line"><span><input class="submit" type="submit" value="' . i18n_r('SAVE_MENU_ORDER') . '"></span> ' . i18n_r('OR') . ' <a class="cancel" href="menu-manager.php?cancel">' . i18n_r('CANCEL') . '</a></p>';
					echo '</form>';
				} else {
					echo '<p>' . i18n_r('NO_MENU_PAGES') . '.</p>';
				}
			?>

			<script>
				$("#menu-order").sortable({
					cursor: 'move',
					placeholder: 'placeholder-menu',
					forcePlaceholderSize: true,
					update: function() {
						var order = '';
						$('#menu-order li').each(function(index) {
							order = order + ',' + $(this).attr('rel');
						});
						$('[name=menuOrder]').val(order);
					}
				});
				$("#menu-order").disableSelection();
			</script>

		</div>
	</div>

	<div id="sidebar">
		<?php include('template/sidebar-pages.php'); ?>
	</div>

</div>
<?php get_template('footer'); ?>
