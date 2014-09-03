<?php
/**
 * Created by PhpStorm.
 * User: kedar
 * Date: 8/25/14
 * Time: 2:46 PM
 */

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection(array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'arogita',
    'username'  => 'root',
    'password'  => 'can',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
));

$capsule->bootEloquent();

// documentation was missing this, connection was null without it
$capsule->setAsGlobal();