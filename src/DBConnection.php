<?php

namespace Ig0rbm\Webcrawler;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;

/**
 * @package Ig0rbm\Webcrawler
 * @author 1g0rbm <m1g0rb89@gmail.com>
 */
class DBConnection
{
    private static $db;

    public static function get()
    {
        if (null === self::$db) {
            self::$db = self::connect();
        }

        return self::$db;
    }

    private static function connect()
    {
        $DBALConfig = new Configuration();

        $connectionParams = [
            'dbname' => 'parser_test',
            'user' => 'root',
            'password' => 'MIAroot_mysql',
            'host' => 'localhost',
            'driver' => 'pdo_mysql'
        ];

        return DriverManager::getConnection($connectionParams, $DBALConfig);
    }
}