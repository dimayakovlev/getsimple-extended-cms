# v3.5.0
## mm/dd/yyyy

1. [](#new)
    * Added `creDate` page field to store date of page creation
    * Added `lastAuthor` page field to store author of last page editing
    * Added `SITEDESCRIPTION` website field to store website description
    * Added `get_site_description()` theme function to get value from `SITEDESCRIPTION` website field
    * Added `DESCRIPTION` user field to store user description (e.g. user biography) for public display
    * Added code highlight to components
    * Added `get_page_field()` theme function to get value from requested field for current page
    * Added `get_page_author()` theme function to get value from `author` field for current page
    * Added handling private pages respond with status code `403 Forbidden`
    * Added `DATECREATED` and `DATEMODIFIED` user fields
    * Added `DATECREATED` and `DATEMODIFIED` website fields
    * Added handling of the time of the last website update
    * Added **Page Component** feature
    * Added `get_page_component()` theme function to eval page component code stored in `component` field of the current page
    * Added `getPageComponent()` cache function to eval page component code of the requested page
    * Added `returnPageComponent()` cache function to return component code of the requested page in plain text
    * Added option to enable/disable code editor from settings page
    * Added option to enable/disable components
    * Added support for special templates for `403` and `404` errors handling pages
    * Added **Maintenance Mode** to preventing visitors from using the site. This mode is enabled by default after installation of CMS
    * Added settings for website content default language and per page content language
    * Added `get_site_lang()` theme function to echo or return website language
    * Added `get_page_lang()` theme function to echo or return page language
    * Added `get_lang()` theme function to echo or return language of current page based on values setted for page, website or in fallback parameter. Use this function to set value of HTML lang attribute
    * Added new tokens for **Pretty URLs**: `%author%`, `%parents%`, `%lang%`, `%year%`, `%month%`, `%day%`
    * Added per page permalink structure
    * Added `getParents()` cache function to get parents of page
    * Added `getParentsMulti()` cache function to get parents of page with optional data
    * Added option to replace page content with page component output
    * Added save state and auto open metadata and component windows in page editor
    * Added option to disable HTML and Code Editors on page level
    * Added page image support
    * Added `get_page_image()` theme function to echo or return page image
    * Added toggle to display URLs of pages on Page Management

1. [](#improved)
    * Updated theme function `get_navigation()` to add `aria-role="page"` to current page in menu
    * Minor GUI elements changes and i18n
    * Russian transliteration based on [Yandex scheme](https://yandex.ru/support/nmaps/app_transliteration.html)
    * Freed `404` slug for usual page
    * `GSNOHIGHLIGHT` option is outdated. Option stored in user field `CODEEDITOR`
    * Disable caching of the admin stylesheet in debug mode
    * Basic `get_site_lang()` function renamed to `get_admin_lang()` and moved to template_functions.php
    * **Fancy URLs** renamed to **Pretty URLs**
    * Signature of function `find_url()` changed. Second parameter control absolute or relative URL. Function can build query string from array passed as third parameter
    * Signature of function `get_page_content()` changed. Added parameter to control replace page content with page component output
    * Save user name and date of last saving Components
    * Save revision number for pages
    * Signature of function `folder_items()` changed. Second parameter accept array with items names to exclude from count items in given path. If given path is not a folder function return null
    * Added function `isAlpha()` to check is alpha version installed
    * Change function `get_themes()` to get what it should according to the name
    * Added support for txt, svg, json and xml files in **Theme Editor**

1. [](#bugfix)
    * `index` page now processed with **Prerry URLs** as other pages. To preserve compatibility user can set custom permalink structure on `index` page to `/`
    * **Theme Edit** now work with theme files with not only lowercase extensions
