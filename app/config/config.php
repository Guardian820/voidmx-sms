<?php

class Conf
{
    static $databases = array(
        'default' => array(
            'host'        => '127.0.0.1',
            'database'    => 'sms',
            'login'        => 'sms',
            'password'    => '',
            'prefix'    => ''
        )
    );

    static $tm4b = array(
        'username' => '',
        'password' => ''
    );

    static $cache = array(
        'path'               => 'cache',
        'default_expires' => 0
    );

    static $security = array(
        'rc4' => "InsertSuperRC4KeyHere"
    );
}

define('USER_LEVEL', 0);
define('MEMB_LEVEL', 1);
define('ADMIN_LEVEL', 2);
define('OP_LEVEL', 3);

Router::prefix('op','op');
Router::prefix('admin','admin');
Router::prefix('user','user');
Router::prefix('user','user');

Router::connect('','home/index');
Router::connect('admin','home/index');
