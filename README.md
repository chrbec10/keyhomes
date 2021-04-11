# Assessment 7

## Required Setup

To make moving the app between computers easier, a config file is used to set the site root. The site root variable will be inserted before filenames so that relative files such as CSS and JS can be accessed correctly if the site root changes.

To do this, find `config.php.example` within the `includes` folder and make a copy called `config.php`. Then, change the `$site_root` variable as needed.

## Folder Structure

- admin - All code for the admin panel
- includes - php parts that reused within the project
  - db.php - The database config
  - layouts - the header and footer
- uploads - All files uploaded through the ACP should be placed here
- static - static JS or CSS
