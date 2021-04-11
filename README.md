# Assessment 7

## Required Setup

To make moving the app between computers easier, a config file is used to set the site root. The site root variable will be inserted before filenames so that relative files such as CSS and JS can be accessed correctly if the site root changes.

To do this, find `config.php.example` within the `includes` folder and make a copy called `config.php`. Then, change the `$site_root` variable as needed. config.php will not push to git as there's potential for it to contain sensitive information.

## Folder Structure

- admin - All code for the admin panel
- includes - php parts that reused within the project
  - db.php - The database config
  - layouts - the header and footer
- uploads - All files uploaded through the ACP should be placed here
- static - static JS or CSS

## Making Database Queries

the DB config **MUST** be included on each page where you wish to make a database Query. This is to ensure no extra code is processed in pages that do not need it. We will be using the Object Oriented version of mysqli (shown on linked page below).

- Documentation on CRUD functions can be found [here](https://www.tutorialrepublic.com/php-tutorial/php-mysql-crud-application.php)

To do this, use `require_once()` to require the `includes/db.php` file. This will expose the `$mysqli` variable to make queries.

### Query Examples

#### Read Data

```php
require_once('./includes/db.php');

$sql = "SELECT * FROM homes WHERE id = ?;

if ($stmt = $mysqli->prepare($sql)){

  // Bind variables to the prepared statement as parameters (i = integer)
  $stmt->bind_param("i", $param_id);

  // Set parameters
   $param_id = trim($_GET["id"]);

  // Attempt to execute the prepared statement
  if($stmt->execute()){

   $result = $stmt->get_result();

   }
}
```
