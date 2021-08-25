<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File:  caching_functions.php
* @Package: GetSimple Extended
* @since 3.1
* @Action:  Plugin to create pages.xml and new functions
*
*****************************************************/

$pagesArray = array();

add_action('header', 'getPagesXmlValues', array(get_filename_id() != 'pages'));  // make $pagesArray available to the back
add_action('page-delete', 'create_pagesxml', array(true));         // Create pages.array if page deleted
add_action('page-restored', 'create_pagesxml', array(true));        // Create pages.array if page undo
add_action('changedata-aftersave', 'create_pagesxml', array(true));     // Create pages.array if page is updated

/**
 * Get Page Component
 *
 * Retrieve and eval the component code of the requested page. 
 * As the Page component is not cached the page file is readed.
 *
 * @since 3.5.0
 * 
 * @uses GSDATAPAGESPATH
 * @uses strip_decode()
 * @uses returnPageField()
 * @uses getDef()
 *
 * @param string $page Slug of the page to retrive component code
 * @param bool $check Check if page component enabled
 * @return mixed Return result of evaluation of page component code or null
 */
function getPageComponent($page, $check = true) {
	if (!getDef('GSPAGECOMPONENT', true) || ($check && (bool)returnPageField($page, 'componentEnabled') == false)) return null;
	$file = GSDATAPAGESPATH . $page . '.xml';
	if (!file_exists($file)) return null;
	$xml = simplexml_load_file($file);
	if (!$xml) return null;
	$component = stripslashes(htmlspecialchars_decode($xml->component, ENT_QUOTES));
	if ($component) {
		eval('?>' . strip_decode($component) . '<?php ');
	}
}

/**
 * Get Page Content
 *
 * Retrieve and display the content of the requested page.
 * As the Content is not cahed the file is read in.
 *
 * @since 2.0
 * @since 3.5.0 Remove parameter $field. Add parameter $filter
 * @param string $page Slug of the page to retrieve content
 * @param bool $filter Optional, default is true. If true content filtered with content filter
 * @return null Echo content of the page
 */
function getPageContent($page, $filter = true) {
	$thisfile = file_get_contents(GSDATAPAGESPATH . $page . '.xml');
	$data = simplexml_load_string($thisfile);
	$content = stripslashes(htmlspecialchars_decode($data->content, ENT_QUOTES));
	if ($filter === true) $content = exec_filter('content', $content);
	echo $content;
}

/**
 * Get Page Field
 *
 * Retrieve and display the requested field from the given page. 
 *
 * @since 3.1
 * @since 3.5.0 Removed check for global variable $pagesArray
 * @param $page - slug of the page to retrieve content
 * @param $field - the Field to display
 */
function getPageField($page, $field) {
	global $pagesArray;
	if ($field == 'content') {
		getPageContent($page);
	} else {
		if (array_key_exists($field, $pagesArray[(string)$page])) {
			echo strip_decode($pagesArray[(string)$page][(string)$field]);
		} else {
			getPageContent($page, $field);
		}
	}
}

/**
 * Echo Page Field
 *
 * Retrieve and display the requested field from the given page. 
 *
 * @since 3.1
 * 
 * @uses getPageField
 * 
 * @param $page - slug of the page to retrieve content
 * @param $field - the Field to display
 */
function echoPageField($page, $field) {
	getPageField($page,$field);
}

/**
 * Return Page Component
 *
 * Retrieve and return the component code of the requested page as text. 
 * As the Page component is not cached the page file is readed.
 *
 * @since 3.5.0
 * 
 * @uses GSDATAPAGESPATH
 *
 * @param $page Slug of the page to retrive component code
 * @return string Return page component code in plain text
 */
function returnPageComponent($page) {
	$thisfile = file_get_contents(GSDATAPAGESPATH . $page . '.xml');
	$data = simplexml_load_string($thisfile);
	if (!$data) return '';
	$component = stripslashes($data->component);
	return (string)$component;
}

/**
 * Return Page Content
 *
 * Return the content of the requested page. 
 * As the Content is not cahed the file is read in.
 *
 * @since 3.1
 * @param string $page Slug of the page to retrieve content
 * @param string $field Name of field. Default is content
 * @param bool $raw If true return raw xml. Default is false
 * @param bool $nofilter If true skip content filter execution. Default is false
 * 
 * @return string Content of the requested page
 */
function returnPageContent(string $page, $field = 'content', $raw = false, $nofilter = false) {
	$thisfile = file_get_contents(GSDATAPAGESPATH . $page . '.xml');
	$data = simplexml_load_string($thisfile);
	if (!$data) return '';
	$content = $data->$field;
	if (!$raw) $content = stripslashes(htmlspecialchars_decode($content, ENT_QUOTES));
	if ($field == 'content' and !$nofilter) {
		$content = exec_filter('content', $content);
	}
	return $content;
}

/**
 * Get Page Field
 *
 * Retrieve and display the requested field from the given page. 
 * If the field is "content" it will call returnPageContent()
 *
 * @since 3.1
 * @since 3.5.0 Removed check for global variable $pagesArray
 * 
 * @global $pagesArray
 * 
 * @uses returnPageContent
 * @uses getPagesXmlValues
 * 
 * @param string $page Slug of the page to retrieve field
 * @param string $field Field name to return value
 */
function returnPageField(string $page, string $field) {
	global $pagesArray;
	if ($field == 'content') return returnPageContent($page);
	return isset($pagesArray[$page][$field]) ? strip_decode($pagesArray[$page][$field]) : '';
}

/**
 * Get Page Children
 *
 * Return an Array of pages that are children of the requested page/slug
 *
 * @since 3.1
 * @since 3.5.0 Removed check for global variable $pagesArray
 * 
 * @global $pagesArray
 * 
 * @param $page Slug of the page to retrieve content
 * 
 * @return array Array of slug names
 */
function getChildren($page) {
	global $pagesArray;
	$returnArray = array();
	foreach ($pagesArray as $key => $value) {
		if ($pagesArray[$key]['parent'] == $page) {
			$returnArray[] = $key;
		}
	}
	return $returnArray;
}

/**
 * Get Page Children with Multi Fields
 *
 * Return an array of pages that are children of the requested page with optional fields
 *
 * @since 3.1
 * @since 3.5.0 Return associative array with children slugs as keys
 * 
 * @global $pagesArray
 * 
 * @param string $page Slug of the page to retrieve children
 * @param array $options Array of optional fields to return
 * 
 * @return array Array of children slugs as keys and values as array of values of optional fields
 */
function getChildrenMulti(string $page, $options = array()) {
	global $pagesArray;
	$returnArray = array();
	foreach ($pagesArray as $key => $value) {
		if ($value['parent'] == $page) {
			$returnArray[$key] = array();
			foreach ($options as $option) {
				$returnArray[$key][$option] = isset($value[$option]) ? $value[$option] : '';
			}
		}
	}
	return $returnArray;
}

/**
 * Get Parent Page
 * 
 * Return slug of the parent page of the requested page
 * 
 * @since 3.5.0
 * 
 * @global $pagesArray
 * 
 * @param string $page Slug of the page retrieve parent page slug
 * 
 * @return string Slug of the parent page. If page has no parent returns empty string
 */
function getParent(string $page) {
	global $pagesArray;
	if (isset($pagesArray[$page])) {
		return (string)$pagesArray[$page]['parent'];
	}
	return '';
}

/**
 * Get Parents Pages
 * 
 * Return an array of pages that are parents of the requested page
 * 
 * @since 3.5.0
 * 
 * @global $pagesArray
 * 
 * @param string $page Slug of the page retrive parents pages slugs
 * @param bool $reverse Reverse order of parents. By default direct parent is the first
 * 
 * @return array Array of slug names
 */
function getParents(string $page, $reverse = false) {
	global $pagesArray;
	$parent = '';
	$parents = array();
	do {
		if (!isset($pagesArray[$page])) break;
		$parent = $pagesArray[$page]['parent'];
		if ($parent) {
			$parents[] = $parent;
			$page = $parent;
		}
	} while ($parent);
	if ($parents && $reverse == true) {
		$parents = array_reverse($parents);
	}
	return $parents;
}

/**
 * Get Parents Pages with Multi Fields
 * 
 * Return an array of pages that are parents of the requested page with optional fields
 * 
 * @since 3.5.0
 * 
 * @global $pagesArray
 * 
 * @param string $page Slug of the page retrive parents pages slugs
 * @param array $options Array of optional fields to return
 * @param $bool $reverse Reverse order of parents. By default direct parent is the first
 */
function getParentsMulti(string $page, $options = array(), $reverse = false) {
	global $pagesArray;
	$parent = '';
	$parents = array();
	do {
		if (!isset($pagesArray[$page])) break;
		$parent = $pagesArray[$page]['parent'];
		if ($parent) {
			$parents[$parent] = array();
			foreach ($options as $option) {
				$parents[$parent][$option] = isset($pagesArray[$parent][$option]) ? $pagesArray[$parent][$option] : '';
			}
			$page = $parent;
		}
	} while ($parent);
	if ($parents && $reverse == true) {
		$parents = array_reverse($parents);
	}
	return $parents;
}
/**
 * Get Cached Pages XML Values
 *
 * Loads the Cached XML data into the Array $pagesArray
 * If the file does not exist it is created the first time. 
 *
 * @since 3.1
 *
 * @global $pagesArray
 *
 * @uses GSDATAOTHERPATH
 * 
 * @param bool $chkcount
 */
function getPagesXmlValues($chkcount = false) {
  global $pagesArray;

	// debugLog(__FUNCTION__.": chkcount - " .(int)$chkcount);
	
	// if page cache not load load it
	if (!$pagesArray) {
		$pagesArray = array();
		$file = GSDATAOTHERPATH . 'pages.xml';
		if (file_exists($file)) {
			// load the xml file and setup the array. 
			// debugLog(__FUNCTION__.": load pages.xml");
			$thisfile = file_get_contents($file);
			$data = simplexml_load_string($thisfile);
			$pages = $data->item;
			foreach ($pages as $page) {
				$key = $page->url;
				$pagesArray[(string)$key] = array();
				foreach ($page->children() as $opt => $val) {
					$pagesArray[(string)$key][(string)$opt] = (string)$val;
				}
			}
		} else {
			// no page cache, regen and then load it
			// debugLog(__FUNCTION__.": pages.xml not exist");
			if (create_pagesxml(true)) getPagesXmlValues(false);
			return;
		}
	}

	// if checking cache sync, regen cache if pages differ.
	if ($chkcount == true) {
		$path = GSDATAPAGESPATH;
		$dir_handle = @opendir($path) or die("getPageXmlValues: Unable to open $path");
		$filenames = array();
		while ($filename = readdir($dir_handle)) {
			$ext = substr($filename, strrpos($filename, '.') + 1);
			if ($ext == 'xml') {
				$filenames[] = $filename;
			}
		}
		if (count($pagesArray) != count($filenames)) {
			// debugLog(__FUNCTION__.": count differs regen pages.xml");
			if (create_pagesxml(true)) getPagesXmlValues(false);
		}
	}
}

/**
 * Create the Cached Pages XML file
 *
 * Reads in each page of the site and creates a single XML file called 
 * data/pages/pages.array 
 *
 * @since 3.1
 * 
 * @global $pagesArray
 * @global $USR
 * 
 * @uses GSDATAOTHERPATH
 * @uses GSDATAPAGESPATH
 * @uses SimpleXMLExtended
 * @uses XMLsave
 * @uses debugLog
 * @uses exec_filter
 * @uses exec_action
 * 
 * @param mixed $flag
 * 
 * @return bool|null Return boolean result of XMLsave function or null
 */
function create_pagesxml($flag) {
	global $pagesArray;
	global $USR;

	$success = '';

	// debugLog("create_pagesxml: " . $flag);
	if ((isset($_GET['upd']) && $_GET['upd'] == 'edit-success') || $flag === true || $flag == 'true') {
		$pagesArray = array();
		// debugLog("create_pagesxml proceeding");
		$menu = '';
		$filem = GSDATAOTHERPATH . 'pages.xml';

		$path = GSDATAPAGESPATH;
		$dir_handle = @opendir($path) or die("create_pagesxml: Unable to open $path");
		$filenames = array();
		while ($filename = readdir($dir_handle)) {
			$ext = substr($filename, strrpos($filename, '.') + 1);
			if ($ext == 'xml') {
				$filenames[] = $filename;
			}
		}
		
		$count = 0;
		$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><channel></channel>');
		if (count($filenames) != 0) {
			foreach ($filenames as $file) {
				if ($file == "." || $file == ".." || is_dir(GSDATAPAGESPATH . $file) || $file == ".htaccess") {
					// not a page data file
				} else {
					$thisfile = file_get_contents($path . $file);
					$data = simplexml_load_string($thisfile);
					
					if (!$data) {
						// handle corrupt page xml
						debugLog("page $file is corrupt");
						continue;
					}

					$count++;
					$id = $data->url;

					$pages = $xml->addChild('item');

					foreach ($data->children() as $item => $itemdata) {
						if ($item != 'content' && $item != 'component') {
							if (in_array($item, array('title', 'meta', 'metad', 'menu', 'permalink', 'lang'))) {
								$pages->addChild($item)->addCData($itemdata);
							} else {
								$pages->addChild($item, $itemdata);
							}
							$pagesArray[(string)$id][$item]=(string)$itemdata;
						}
					}

					$pages->addChild('slug', $id);
					$pagesArray[(string)$id]['slug'] = (string)$id;
					$pages->addChild('filename', $file);
					$pagesArray[(string)$id]['filename'] = $file;
					
				} // else
			} // end foreach
		} // endif
		if ($flag === true || $flag == 'true') {

			// Plugin Authors should add custom fields etc.. here
			$xml = exec_filter('pagecache', $xml);

			// sanity check in case the filter does not come back properly or returns null
			if ($xml) {
				$xml->addAttribute('created', date('r'));
				$xml->addAttribute('user', $USR);
				$success = XMLsave($xml, $filem);
			}
			// debugLog("create_pagesxml saved: ". $success);
			exec_action('pagecache-aftersave');
			return $success;
		}
	}
}

/**
 * Private Page
 * 
 * Check if requested page is private
 * 
 * @since 3.5.0
 * 
 * @global $pagesArray
 * 
 * @param string $page Slug of the page
 * 
 * @return bool Return true if page is private
 */
function isPagePrivate(string $page) {
	global $pagesArray;
	return (isset($pagesArray[$page]) && $pagesArray[$page]['private'] != '') ? true : false;
}
