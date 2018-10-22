# Summary

Here's a part of a quick Symfony project I did. It shows some entities, controllers, views and so on. You can test it by creating a new 'Order' entity.

# Requirements

- PHP >= 7.1.9 (http://php.net/downloads.php/)
- Composer (https://getcomposer.org/)
- Yarn (https://yarnpkg.com/lang/en/docs/install/)

# Installation

- git clone  https://github.com/pierrererot/symfonyExample.git 'yourNewFolder'
- cd 'yourNewFolder'
- configure the ".env" file according to your local database (the current .env is an exemple of MY personal configuration)
- composer install
- yarn run encore dev
- create and fill your local database (reminders in the "Tips" section below if you forgot some useful Symfony commands)
- php bin/console doctrine:fixtures:load --no-interaction (installing dataFixtures)
- php -S 127.0.0.1:8000 -t public
- enjoy the result and visit the page http://127.0.0.1:8000/ with your favorite browser ! :)

# Tips

- when you execute a PHP command, make sure your PHP interpreter is the correct one. A basic "php --version" can helps.
- only the admin user can access to the http://127.0.0.1:8000/admin/ page
- as you can see in the dataFixtures, these users are available for your tests :
- login : temp1 | password : temp1
- login : admin | password : admin
- uselful command N°1 : php bin/console doctrine:database:create (create your database)
- uselful command N°2 : php bin/console doctrine:migrations:diff (create the "Migration files")

# Easter egg
- When you are connected, you can create a new Order. The result into the data/xml/yourOrderId.xml ! 


ENJOY !
