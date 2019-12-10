# Simple Blog System

I wrote the base for this in a couple of days, and slowly extended it more over time. I needed a progress blog for school to show what I have done in the class.
A few other students made an account here too.

## Features

* Register & login
* Email verification
* New Post 
* View Post
* View all of a user's posts
* Edit Posts 
* Delete Posts
* Recent post feed

## Libraries Used

To speed up the development process and to make it a lot easier to work with I utilized some PHP Libraries:

[bramus/router](https://github.com/bramus/router) - PHP Routing

[sergeytsalkov/meekrodb](https://github.com/SergeyTsalkov/meekrodb) - Super easy and simple database tool

[phpmailer/phpmailer](https://github.com/PHPMailer/PHPMailer) - Rich mail sender library

[twigphp/twig](https://github.com/twigphp/Twig) - Rich templating library

For post editing I used [CKEditor 4](https://github.com/ckeditor/ckeditor4)

## How-to setup

If you would like to use it for your own, you're welcome to.

Requirements:

* PHP 5.3+ Server with Apache (If you use NGIX you need to use [this web.config](https://gist.githubusercontent.com/bramus/5332525/raw/49e9658772c8342d6f64e50484ae9265cd37bb2c/web.config))
* MySQL Database (With 3 tables setup [with these structures](https://github.com/LittleBigBug/simple-blog-system/blob/master/dbstructure.sql))
* A mail server on your PHP server (Or, you can change lines 183 - 205 in [index.php](https://github.com/LittleBigBug/simple-blog-system/blob/master/index.php) to work with an external [SMTP](https://github.com/PHPMailer/PHPMailer/blob/master/examples/smtp.phps) server)

To set up what database to use as well as username and password, you need to create a new file in inc/ called dbsetup.php

For obvious reasons, I have removed mine. An Example of this file would be:

```php
<?php

require("_sec.php");

DB::$user = "username_here";
DB::$password = "password_here";
DB::$dbName = "database_name_here";

?>
```

At the moment, any account created will by default have rank 0. This means they _can not make new posts_ you need to manually edit their rank in the mysql database.

Rank 0 = User

Rank 1 = Poster

Rank 2 = Administrator (Can Delete & Edit other's posts)

## Security

For the most part I've tried to take out any <script> <style> or <link> tags in any posts. However I have not tested this 100% for security so it may be unideal for public use and may be prone to attacks.
Other than that, I have added a delay in which how fast a user can post or do any action. Right now it is 300 seconds for new posts and 20 for editing posts and you may change it on lines 325 (new posts) and 439 (editing posts) in [index.php](https://github.com/LittleBigBug/simple-blog-system/blob/master/index.php).
