
![Globalvision](http://vr360.globalvision.ch/assets/images/gv_logo.png)
----------
VR360
===================

Changelogs

**2.0.0**
 - **Refactored whole system** with MVC supported
 - Upgraded to krpano version 1.19
 - Use Javascript object
 - Implemented SEO for tour view
 - Move data.json into database
 - Database optimized
 - UI improved
 - New session system
 - New configuration system
 - Email sending
 - Searching

**2.1.0**
- Automate testing
- UI improved
  - Upgraded FontAwesome 5.0
  - Improve search bar
  - Improve pagination: Do not show if only one page
  - Prevent close modal without pressing Close
  - Validate with ajax
  - JS validate onfly
- Performance improved
  - XML optimized before saving to file
  - Only execute Krpano if new file uploaded
  - Remove JSON. Everything stored directly into database
  - Implemented Joomla! database library
- Delete scenes, physical files when scenes & tours are deleted
- Bugs fixing
----------
How to install
- Setup database
- Create tables

~~~~
`
sql.gz
`
~~~~
- Download Krpano and extract to ./krpano directory
- Setup configuration & krpano license
