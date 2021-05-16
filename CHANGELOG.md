# v
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
    * Added page component feature
    * Added `get_page_component()` theme function to eval page component code stored in `component` field of the current page
    * Added `getPageComponent` cache function to eval page component code of the requested page
    * Added `returnPageComponent` cache function to return component code of the requested page in plain text
    * Added option to enable/disable code editor from settings page
    * Added option to enable/disable components
    * Added support for special templates for `403` and `404` errors handling pages
    * Added **Maintenance Mode** to preventing visitors from using the site. This mode is enabled by default after installation of CMS

1. [](#improved)
    * Updated theme function `get_navigation()` to add `aria-role="page"` to current page in menu
    * Minor GUI elements changes and i18n
    * Russian transliteration based on [Yandex scheme](https://yandex.ru/support/nmaps/app_transliteration.html)
    * Freed `404` slug for usual page
    * `GSNOHIGHLIGHT` option is outdated. Option stored in user field `CODEEDITOR`
    * Disable caching of the admin stylesheet in debug mode

1. [](#bugfix)