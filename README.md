# Login_1DV610
Interface repository for 1DV610 assignment L3. Requirements and Code Quality.  
Estimated score on assignment: 100%  
According to the auto tests: http://csquiz.lnu.se:25083/index.php  
On public server here: http://professorpotatis.000webhostapp.com/index.php

## Installation
1. Download and install a MAMP, WAMP or LAMP.
2. Download the repository.
3. Put the repository in your MAMP/WAMP/LAMP folder htdocs.
4. Start the MAMP/WAMP/LAMP.
5. Go to the MAMP/WAMP/LAMP phpMyAdmin and create a mySQL database:

### Create a mySQL database
Add table namned Users.  
The table should contain three columns.  
First column: username, varchar(20), utf8mb4_unicode_ci.  
Second column: password, varchar(255), utf8mb4_unicode_ci.  
Third column: cookie, varchar(255), utf8mb4_unicode_ci.

6. In your MAMP/WAMP/LAMP folder htdocs, go to the model folder.
7. Create a DBConfig.php file and save it to the model folder:

### Create a DBConfig.php file
Add the information for your mySQL database.  
```php
<?php

$db_host = 'localhost';
$db_user = 'username';
$db_password = 'password';
$db_name = 'dbname';
```

8. In your browser, go to localhost:8888/sk222uf-1dv610-L3/index.php.
9. DONE!


# Extra functionality
A game that I have named "Dinosaur Move Boxes".

## Extra use cases
Building upon the use cases presented here:  
https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/UseCases.md

### UC5 Play a game
#### Preconditions
A user is authenticated. Ex. UC1, UC3.
#### Main scenario
1. Starts when a user wants to play a game.
2. The system present a play game choice.
3. User tells the system he/she wants to play a game.
4. The system present a game.

### UC6 Continue to play a game
#### Preconditions
A user is authenticated. Ex. UC1, UC3.  
A user has started to play a game. Ex. UC5.
#### Main scenario
1. Starts when a user wants to continue to play a game.
2. The system present a play game choice.
3. User tells the system he/she wants to play a game.
4. The system present the previously started game.

### UC7 Reset a game
#### Preconditions
A user is authenticated. Ex. UC1, UC3.  
A user has started to play a game. Ex. UC5, UC6.
#### Main scenario
1. Starts when a user wants to reset the game.
2. The system present a reset game choice.
3. User tells the system he/she wants to reset the game.
4. The system present a reset game.

## Play "Dinosaur Move Boxes"
When logged in to the system:  
1. Click link "Play game".
2. Follow the instructions on the page.
