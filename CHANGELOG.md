# v
## mm/dd/yyyy

1. [](#new)
    * Added `creDate` page field to store date of page creation
    * Added `lastAuthor` page field to store author of last page editing
    * Added `SITEDESC` website field to store website description
    * Added `get_site_description()` theme function to get value from `SITEDESC` website field
    * Added `DESC` user field to store user description (e.g. user biography) for public display
    * Added code highlight to components
    * Added `get_page_field()` theme function to get value from requested field for current page
    * Added `get_page_author()` theme function to get value from `author` field for current page
    * Added handling private pages respond with status code `403 Forbidden`

1. [](#improved)
    * Updated theme function `get_navigation()` to add `aria-role="page"` to current page in menu
    * Minor GUI elements changes and i18n
    * Russian transliteration based on [Yandex scheme](https://yandex.ru/support/nmaps/app_transliteration.html)

1. [](#bugfix)