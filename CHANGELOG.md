# v
## mm/dd/yyyy

1. [](#new)
    * Added `creDate` page field to store date of page creation
    * Added `lastAuthor` page field to store author of last page editing
    * Added `SITEDESC` website field to store website description
    * Added `get_site_description()` theme function to get value from `SITEDESC` website field
    * Added `DESC` user field to store user description (e.g. user biography) for public display

1. [](#improved)
    * Updated theme function `get_navigation()` to add `aria-role="page"` to current page in menu
