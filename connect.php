<?php

// Make sure you have Composer's autoload file included
require 'Query_builder/Query_builder/vendor/autoload.php';
$localhost="localhost";
$db= "test-soft";
$users="root";
$pass="";

// Create a connection, once only.

$config = array(
    'driver'    => 'mysql', // Db driver
    'host'      =>$localhost,
    'database'  =>$db,
    'username'  =>$users,
    'password'  =>$pass,
    'charset'   => 'utf8', // Optional
    'collation' => '', // Optional
    'prefix'    => '', // Table prefix, optional
    );

new \Pixie\Connection('mysql', $config, 'QB');


?>