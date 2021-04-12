# Assessment 7

## Required Setup

To make moving the app between computers easier, a config file is used to set the site root. The site root variable will be inserted before filenames so that relative files such as CSS and JS can be accessed correctly if the site root changes.

To do this, find `config.php.example` within the `includes` folder and make a copy called `config.php`. Then, change the `$site_root` variable as needed. config.php will not push to git as there's potential for it to contain sensitive information.

### Editing the CSS

The website uses Bootstrap.sass which allows us to modify bootstrap paramaters to style Bootstrap how we like. Sass must be compiled down to CSS for use.
First, install the [Live Sass Compiler](https://marketplace.visualstudio.com/items?itemName=ritwickdey.live-sass) plugin for VScode. Then, click the new "Watch Sass" button that will appear at the bottom of the window. Then, whenever you make changes to theme.scss, it will automatically recompile new CSS.

## Folder Structure

- admin - All code for the admin panel
- includes - php parts that reused within the project
  - db.php - The database config
  - layouts - the header and footer
- uploads - All files uploaded through the ACP should be placed here
- static - static JS or CSS

## Making Database Queries

the DB config **MUST** be included on each page where you wish to make a database Query. This is to ensure no extra code is processed in pages that do not need it. We will be using the **Object Oriented** version of mysqli (shown on linked page below).

- Documentation on CRUD functions can be found [here](https://www.tutorialrepublic.com/php-tutorial/php-mysql-crud-application.php)

To do this, use `require_once()` to require the `includes/db.php` file. This will expose the `$mysqli` variable to make queries.

### Query Examples

#### Read Data

```php
require_once('./includes/db.php');

// Prepare a select statement
$sql = "SELECT * FROM employees WHERE id = :id";

if($stmt = $pdo->prepare($sql)){

  // Bind a variable to the :id param
  $stmt->bindParam(":id", $param_id);

  // Set value of the bound varaible
  $param_id = trim($_GET["id"]);

  // Attempt to execute the prepared statement
  if($stmt->execute()){

    /*
    - - - - -
    Handle the results here
    - - - - -
    */

  } else{
    //Runs if the statement fails to execture
      echo "Oops! Something went wrong. Please try again later.";
  }
}
```
