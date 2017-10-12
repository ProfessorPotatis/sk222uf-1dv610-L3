# Login_1DV610
Interface repository for 1DV610 assignment 2.

## Create a mySQL database
Add table namned Users.  
The table should contain three columns.  
First column: username, varchar(20), utf8mb4_unicode_ci.  
Second column: password, varchar(255), utf8mb4_unicode_ci.  
Third column: cookie, varchar(255), utf8mb4_unicode_ci.

## Create a DBConfig.php file
Save in model folder. Add the information for your mySQL database.  
```php
<?php

$db_host = 'localhost';
$db_user = 'username';
$db_password = 'password';
$db_name = 'dbname';
```

## Play "Dinosaur Move Boxes"
When logged in to the system:  
1. Click link "Play game".
2. Follow the instructions on the page.
