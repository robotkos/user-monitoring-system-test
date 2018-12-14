#Datamining Task Manager 

==========

##Installation



 Before installation need create database.


```git clone ...```

```cd <directory>```

```composer install```

```cp .env.dist .env```

Add database settings to .env

```bin/console doctrine:schema:update --force && bin/console doctrine:fixtures:load```

```bin/console server:run```

Go to http://127.0.0.1:8000/main and login

API http://127.0.0.1:8000/api/doc

Developed on Ubuntu 18 / PHP 7.2 / Mysql 5.7