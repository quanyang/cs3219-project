<?php

use Illuminate\Database\Capsule\Manager as Capsule;
/**
 * Configure the database and boot Eloquent
 */
$capsule = new Capsule;
$capsule->addConnection(array(
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'parser',
    'username'  => 'root',
    'password'  => getenv('MYSQL_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_bin',
    'prefix'    => ''
));
$capsule->setAsGlobal();
$capsule->bootEloquent();
// set timezone for timestamps etc
date_default_timezone_set('Asia/Singapore');
