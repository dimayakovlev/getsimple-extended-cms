<?php if (!defined('IN_GS')) { die('you cannot load this page directly.'); }
/**
 * Theme Functions
 *
 * These functions are used within the front-end of a GetSimple Extended installation
 *
 * @link https://github.com/dimayakovlev/getsimple-extended-cms/wiki/Themes
 *
 * @package GetSimple Extended
 * @subpackage Theme-Functions
 */

/**
 * Get Page Author
 *
 * @since 3.5.0
 * @uses $data_index
 * @uses strip_decode
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns page author based on param $echo
 */
function get_page_author($echo = true) {
	global $data_index;
	$author = strip_decode($data_index->author);
	if ($echo) {
		echo $author;
	} else {
		return $author;
	}
}

/**
 * Get Page Publisher
 *
 * @since 3.5.0
 * @uses $data_index
 * @uses strip_decode
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns page publisher based on param $echo
 */
function get_page_publisher($echo = true) {
	global $data_index;
	$publisher = strip_decode($data_index->publisher);
	if ($echo) {
		echo $publisher;
	} else {
		return $publisher;
	}
}

/**
 * Get Page Content
 *
 * @since 1.0
 * @since 3.5.0 Don't change global $content. Support for dynamic content pages
 * @global $content
 * @global $data_index
 * @uses exec_action()
 * @uses exec_filter()
 * @uses strip_decode()
 * @uses getDef()
 * @uses get_page_component()
 *
 * @return null Echo page content
 */
function get_page_content() {
	global $content;
	global $data_index;
	exec_action('content-top');
	$content_e = exec_filter('content', strip_decode($content));
	if (getDef('GSCONTENTSTRIP', true)) $content_e = strip_content($content_e);
	echo $content_e;
	exec_action('content-bottom');
}

/**
 * Get Page Excerpt
 *
 * @since 2.0
 * @uses $content
 * @uses exec_filter
 * @uses strip_decode
 *
 * @param string $n Optional, default is 200.
 * @param bool $striphtml Optional, default false, true will strip html from $content
 * @param string $ellipsis Optional, Default '…', specify an ellipsis
 * @return string Echos.
 */
function get_page_excerpt($len = 200, $striphtml = true, $ellipsis = '…') {
	global $content;
	if ($len < 1) return '';
	$content_e = strip_decode($content);
	$content_e = exec_filter('content', $content_e);
	if (getDef('GSCONTENTSTRIP',true)) $content_e = strip_content($content_e);
	echo getExcerpt($content_e, $len, $striphtml, $ellipsis);
}

/**
 * Get Page Field
 *
 * This will return requested field value of a particular page
 *
 * @since 3.5.0
 * @uses $data_index
 * @uses strip_decode
 *
 * @param string $field Page field name
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_page_field($field, $echo = true) {
	global $data_index;
	if ($echo) {
		echo strip_decode($data_index->$field);
	} else {
		return strip_decode($data_index->$field);
	}
}

/**
 * Get Page Language
 * 
 * This will return or echo the page language
 * 
 * @since 3.5.0
 * 
 * @global $data_index
 * @uses strip_decode
 * 
 * @param $echo Optional, default is true. False will return value
 * @return string|null Echos or return based on param $echo
 */
function get_page_lang($echo = true) {
	global $data_index;
	if ($echo) {
		echo strip_decode($data_index->lang);
	} else {
		return strip_decode($data_index->lang);
	}
}

/**
 * Get Page Image
 * 
 * This will return or echo url of the page image
 * 
 * @since 3.5.0
 * 
 * @global $data_index
 * @uses strip_decode
 * @param $echo Optional, default is true. False will return value
 * @return string|null Echos or return based on param $echo
 */
function get_page_image($echo = true) {
	global $data_index;
	if ($echo) {
		echo strip_decode($data_index->image);
	} else {
		return strip_decode($data_index->image);
	}
}

/**
 * Get Page Meta Keywords
 *
 * @since 2.0
 * @uses $metak
 * @uses strip_decode
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_meta_keywords($echo = true) {
	global $metak;
	$myVar = encode_quotes(strip_decode($metak));
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Meta Description
 *
 * @since 2.0
 * @uses $metad
 * @uses strip_decode
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_meta_desc($echo = true) {
	global $metad;
	$myVar = encode_quotes(strip_decode($metad));
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Title
 *
 * @since 1.0
 * @uses $title
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_title($echo = true) {
	global $title;
	if ($echo) {
		echo strip_decode($title);
	} else {
		return strip_decode($title);
	}
}

/**
 * Get Page Clean Title
 *
 * This will remove all HTML from the title before returning
 *
 * @since 1.0
 * @uses $title
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_page_clean_title($echo = true) {
	global $title;
	$myVar = strip_tags(strip_decode($title));
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}

/**
 * Get Page Slug
 *
 * This will return the slug value of a particular page
 *
 * @since 1.0
 * @uses $url
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_page_slug($echo = true) {
	global $url;
	if ($echo) {
		echo $url;
	} else {
		return $url;
	}
}

/**
 * Get Page Parent Slug
 *
 * This will return the slug value of a particular page's parent
 *
 * @since 1.0
 * @uses $parent
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_parent($echo = true) {
	global $parent;
	if ($echo) {
		echo $parent;
	} else {
		return $parent;
	}
}

/**
 * Get Page Date
 *
 * This will return the page's updated date/timestamp
 *
 * @since 1.0
 * @global $date
 * @global $TIMEZONE
 *
 * @param string $i Optional, default is "l, F jS, Y - g:i A"
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_page_date($i = 'l, F jS, Y - g:i A', $echo = true) {
	global $date;
	global $TIMEZONE;
	if ($TIMEZONE != '' && function_exists('date_default_timezone_set')) date_default_timezone_set($TIMEZONE);
	if ($echo) {
		echo date($i, strtotime($date));
	} else {
		return date($i, strtotime($date));
	}
}

/**
 * Get Page Full URL
 *
 * This will return the full url
 *
 * @since 1.0
 * @since 3.5.0 Use updated function find_url()
 * @uses $url
 * @uses find_url
 *
 * @param bool $echo Optional, default is false. True will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_page_url($echo = false) {
	global $url;
	if ($echo) {
		echo find_url($url);
	} else {
		return find_url($url);
	}
}

/**
 * Get Page Header HTML
 *
 * This will return header html for a particular page. This will include the 
 * meta desriptions & keywords, canonical and title tags
 *
 * @since 1.0
 * @uses exec_action
 * @uses get_page_url
 * @uses strip_quotes
 * @uses get_page_meta_desc
 * @uses get_page_meta_keywords
 * @uses $metad
 * @uses $title
 * @uses $content
 * @uses $site_full_name from configuration.php
 * @uses GSADMININCPATH
 *
 * @return string HTML for template header
 */
function get_header($full = true) {
	global $metad;
	global $title;
	global $content;
	include(GSADMININCPATH.'configuration.php');
	// meta description
	if ($metad != '') {
		$desc = get_page_meta_desc(false);
	} elseif (getDef('GSAUTOMETAD', true)) {
		// use content excerpt, NOT filtered
		$desc = strip_decode($content);
		if (getDef('GSCONTENTSTRIP', true)) $desc = strip_content($desc);
		$desc = cleanHtml($desc, array('style', 'script')); // remove unwanted elements that strip_tags fails to remove
		$desc = getExcerpt($desc, 160); // grab 160 chars
		$desc = strip_whitespace($desc); // remove newlines, tab chars
		$desc = encode_quotes($desc);
		$desc = trim($desc);
	}

	if (!empty($desc)) echo '<meta name="description" content="' . $desc . '" />' . "\n";

	// meta keywords
	$keywords = get_page_meta_keywords(false);
	if ($keywords != '') echo '<meta name="keywords" content="' . $keywords . '" />' . "\n";

	if ($full) {
		echo '<link rel="canonical" href="' . get_page_url(false) . '" />' . "\n";
	}

	// script queue
	get_scripts_frontend();

	exec_action('theme-header');
}

/**
 * Get Page Footer HTML
 *
 * This will return footer html for a particular page. Right now
 * this function only executes a plugin hook so developers can hook into
 * the bottom of a site's template.
 *
 * @since 2.0
 * @uses exec_action
 *
 * @return string HTML for template header
 */
function get_footer() {
	get_scripts_frontend(true);
	exec_action('theme-footer');
}

/**
 * Get Site URL
 *
 * This will return the site's full base URL
 * This is the value set in the control panel
 *
 * @since 1.0
 * @uses $SITEURL
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_url($echo = true) {
	global $SITEURL;
	if ($echo) {
		echo $SITEURL;
	} else {
		return $SITEURL;
	}
}

/**
 * Get Site Language
 * 
 * This will return or echo the site language
 * This is the value set in the control panel
 * 
 * @since 3.5.0
 * 
 * @global $dataw
 * @param $echo Optional, default is true. False will return value
 * @return string|null Echos or return based on param $echo
 */
function get_site_lang($echo = true) {
	global $dataw;
	if ($echo) {
		echo $dataw->lang;
	} else {
		return (string)$dataw->lang;
	}
}

/**
 * Get Language
 * 
 * This will return or echo language
 * 
 * @since 3.5.0
 * 
 * @global $data_index
 * @global $dataw
 * @param bool $echo Optional, default is true. False will return value
 * @param string $lang Optional, default is en. Fallback language code, used if language for page or website was not setted
 * @return string|null
 */
function get_lang($echo = true, $lang = 'en') {
	global $data_index;
	global $dataw;
	$value = (string)$data_index->lang;
	if ($value == '') {
		$value = (string)$dataw->lang;
		if ($value == '') {
			$value = (string)$lang;
		}
	}
	if ($echo) {
		echo $value;
	} else {
		return $value;
	}
}

/**
 * Get Theme URL
 *
 * This will return the current active theme's full URL 
 *
 * @since 1.0
 * @uses $SITEURL
 * @uses $TEMPLATE
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_theme_url($echo = true) {
	global $SITEURL;
	global $TEMPLATE;
	$value = trim($SITEURL . 'theme/' . $TEMPLATE);
	if ($echo) {
		echo $value;
	} else {
		return $value;
	}
}

/**
 * Get Site's Name
 *
 * This will return the value set in the control panel
 *
 * @since 1.0
 * @global $SITENAME
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_name($echo = true) {
	global $SITENAME;
	if ($echo) {
		echo cl($SITENAME);
	} else {
		return cl($SITENAME);
	}
}

/**
 * Get Site's Description
 *
 * This will return the value set in the control panel
 *
 * @since 3.5.0
 * @global $dataw
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return string Echos or returns based on param $echo
 */
function get_site_description($echo = true) {
	global $dataw;
	if ($echo) {
		echo cl($dataw->description);
	} else {
		return cl($dataw->description);
	}
}

/**
 * Get Administrator's Email Address
 * 
 * This will return the value set in the control panel
 * 
 * @depreciated as of 3.0
 *
 * @since 1.0
 * @uses $EMAIL
 *
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */
function get_site_email($echo = true) {
	global $EMAIL;
	$myVar = trim(stripslashes($EMAIL));
	if ($echo) {
		echo $myVar;
	} else {
		return $myVar;
	}
}


/**
 * Get Site Credits
 *
 * This will return HTML that displays 'Powered by GetSimple X.XX'
 * It will always be nice if developers left this in their templates 
 * to help promote GetSimple. 
 *
 * @since 1.0
 * @uses $site_link_back_url from configuration.php
 * @uses $site_full_name from configuration.php
 * @uses GSVERSION
 * @uses GSADMININCPATH
 *
 * @param string $text Optional, default is 'Powered by'
 * @return string 
 */
function get_site_credits($text ='Powered by ') {
	include(GSADMININCPATH . 'configuration.php');
	$site_credit_link = '<a href="' . $site_link_back_url . '" target="_blank" >' . $text . ' ' . $site_full_name . '</a>';
	echo stripslashes($site_credit_link);
}

/**
 * Menu Data
 *
 * This will return data to be used in custom navigation functions
 *
 * @since 2.0
 * @since 3.5.0 Use updated function find_url()
 * @uses GSDATAPAGESPATH
 * @uses find_url
 * @uses getXML
 * @uses subval_sort
 *
 * @param bool $xml Optional, default is false. 
 *				True will return value in XML format. False will return an array
 * @return array|string Type 'string' in this case will be XML 
 */
function menu_data($id = null,$xml=false) {
    $menu_extract = array();

    global $pagesArray; 
    $pagesSorted = subval_sort($pagesArray,'menuOrder');
    if (count($pagesSorted) != 0) { 
      $count = 0;
      if (!$xml){
        foreach ($pagesSorted as $page) {
          $text = (string)$page['menu'];
          $pri = (string)$page['menuOrder'];
          $parent = (string)$page['parent'];
          $title = (string)$page['title'];
          $slug = (string)$page['url'];
          $menuStatus = (string)$page['menuStatus'];
          $private = (string)$page['private'];
					$pubDate = (string)$page['pubDate'];
          
          $url = find_url($slug);
          
          $specific = array("slug"=>$slug,"url"=>$url,"parent_slug"=>$parent,"title"=>$title,"menu_priority"=>$pri,"menu_text"=>$text,"menu_status"=>$menuStatus,"private"=>$private,"pub_date"=>$pubDate);
          
          if ($id == $slug) { 
              return $specific; 
              exit; 
          } else {
              $menu_extract[] = $specific;
          }
        }
        return $menu_extract;
      } else {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><channel>';    
	        foreach ($pagesSorted as $page) {
            $text = $page['menu'];
            $pri = $page['menuOrder'];
            $parent = $page['parent'];
            $title = $page['title'];
            $slug = $page['url'];
            $pubDate = $page['pubDate'];
            $menuStatus = $page['menuStatus'];
            $private = $page['private'];
           	
            $url = find_url($slug);
            
            $xml.="<item>";
            $xml.="<slug><![CDATA[".$slug."]]></slug>";
            $xml.="<pubDate><![CDATA[".$pubDate."]]></pubDate>";
            $xml.="<url><![CDATA[".$url."]]></url>";
            $xml.="<parent><![CDATA[".$parent."]]></parent>";
            $xml.="<title><![CDATA[".$title."]]></title>";
            $xml.="<menuOrder><![CDATA[".$pri."]]></menuOrder>";
            $xml.="<menu><![CDATA[".$text."]]></menu>";
            $xml.="<menuStatus><![CDATA[".$menuStatus."]]></menuStatus>";
            $xml.="<private><![CDATA[".$private."]]></private>";
            $xml.="</item>";
	        }
	        $xml.="</channel>";
	        return $xml;
        }
    }
}

/**
 * Get Component
 *
 * This will return the component requested.
 * Components are parsed for PHP within them.
 *
 * @since 1.0
 * @since 3.5.0 Added parameter $check to check if component enabled
 * @global $components
 * 
 * @uses GSDATAOTHERPATH
 * @uses getXML
 *
 * @param string $id This is the ID of the component you want to display
 * @param bool $check Check if component enabled
 * @return mixed Return result of evaluation of component code or null
 */
function get_component($id, $check = true) {
	global $components;

	if (!$components) {
		if (file_exists(GSDATAOTHERPATH . 'components.xml')) {
			$data = getXML(GSDATAOTHERPATH . 'components.xml');
			$components = $data->children();
		} else {
			$components = array();
		}
	}

	if (count($components) > 0) {
		foreach ($components as $component) {
			if ($id == $component->slug) {
				if ($check == false || ($check == true && $component->enabled == '1')) {
					eval('?>' . strip_decode($component->value) . '<?php ');
				}
				//break;
			}
		}
	}
}

/**
 * Get Main Navigation
 *
 * This will return unordered list of main navigation
 * This function uses the menu options listed within the 'Edit Page' control panel screen
 *
 * @since 1.0
 * @since 3.5.0 Update code
 * @uses GSDATAOTHERPATH
 * @uses getXML
 * @uses subval_sort
 * @uses find_url
 * @uses strip_quotes
 * @uses exec_filter
 *
 * @param string $currentpage This is the ID of the current page the visitor is on
 * @param string $prefix Prefix that gets added to the parent and slug classnames
 * @param bool $echo Optional, default is true. False will 'return' value
 * @return null|string Echos or returns based on param $echo
 */	
function get_navigation($currentpage = '', $prefix = '', $echo = true) {
	global $pagesArray, $id;
	if ($currentpage == '') $currentpage = $id;
	$pagesSorted = subval_sort($pagesArray, 'menuOrder');
	$menu = '';
	if (count($pagesSorted) > 0) {
		foreach ($pagesSorted as $page) {
			if ($page['menuStatus'] == '' || $page['private'] == '2') continue;
			$class = (($page['parent'] != '') ? $prefix . $page['parent'] . ' ' : '') . $prefix . $page['url'];
			$ariaRole = '';
			if ($currentpage == $page['url']) {
				$class .= ' current active';
				$ariaRole = ' aria-current="page"';
			}
			$menu .= '<li class="' . $class . '"><a' . $ariaRole . ' href="' . find_url($page['url']) . '" title="' . encode_quotes(cl($page['title'])) . '">' . strip_decode($page['menu'] ?: $page['title']) . '</a></li>';
		}
	}
	if (!$echo) return exec_filter('menuitems', $menu);
	echo exec_filter('menuitems', $menu);
}

/**
 * Check if a user is logged in
 * 
 * This will return true if user is logged in
 *
 * @since 3.2
 * @since 3.5.0 Check if $USR is not emptry string
 * 
 * @global $USR
 * @uses get_cookie();
 *
 * @return bool Return true if user is logged in
 */
function is_logged_in() {
	global $USR;
	return (isset($USR) && $USR != '' && $USR == get_cookie('GS_ADMIN_USERNAME'));
}

/**
 * @depreciated as of 2.04
 */
function return_page_title() {
	return get_page_title(FALSE);
}
/**
 * @depreciated as of 2.04
 */
function return_parent() {
	return get_parent(FALSE);
}
/**
 * @depreciated as of 2.04
 */
function return_page_slug() {
  return get_page_slug(FALSE);
}
/**
 * @depreciated as of 2.04
 */
function return_site_ver() {
	return get_site_version(FALSE);
}
/**
 * @depreciated as of 2.03
 */
if(!function_exists('set_contact_page')) {
	function set_contact_page() {
		#removed functionality
	}
}
