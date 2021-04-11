# Assessment 7

## Required Setup

To make moving the app between computers easier, a config file is used to set the site root. The site root will be inserted after the trailing slash after the server name (eg. localhost), so that relative files such as CSS and JS can be accessed correctly when the root changes.

To do this, find _config.php.example_ within the _includes_ folder and make a copy called _config.php_. Then, change the _$site_root_ variable as needed.

## Folder Structure

- admin - All code for the admin panel
- includes - php parts that reused within the project
  - db.php - The database config
  - layouts - the header and footer
- uploads - All files uploaded through the ACP should be placed here
- static - static JS or CSS
