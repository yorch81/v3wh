# V3ctor WareHouse #

## Description ##
V3ctor WareHouse Core for MongoDb and MySql

## Requirements ##
* [PHP 5.4.1 or higher](http://www.php.net/)
* [Medoo](http://medoo.in/)
* [MySQL](https://www.mysql.com/)
* [MongoDb](https://www.mongodb.com/)

## Developer Documentation ##
Execute phpdoc -d v3wh/

## Unit Test ##
For run Unit Test, complete information connection and execute the next commands:
> phpunit V3WareHouseTest.php

## Installation ##
Create file composer.json
~~~

{
    "require": {
    	"php": ">=5.4.0",
        "yorch/v3wh" : "dev-master",
        "monolog/monolog": "1.13.1",
        "catfan/medoo": "dev-master"
    }
}

~~~

Execute composer.phar install

## Example ##
~~~

$v3ctor = V3WareHouse::getInstance('v3Mongo', $hostname, $username, $password, $dbname, $key);

if (! $v3ctor->isConnected())
    die("Unable load V3ctor WareHouse");

~~~

## Notes ##
v3wh is a Core for MongoDb and needs php mongo module.

## References ##
http://es.wikipedia.org/wiki/Singleton

P.D. Let's go play !!!




