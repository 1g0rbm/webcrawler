<?php

namespace Ig0rbm\Webcrawler\Core;

/**
 * @package Ig0rbm\Webcrawler\Core
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
        $DBALConfig = new \Doctrine\DBAL\Configuration();

        $connectionParams = [
            'dbname' => 'parser_test',
            'user' => 'root',
            'password' => 'MIAroot_mysql',
            'host' => 'localhost',
            'driver' => 'pdo_mysql'
        ];

        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $DBALConfig);
    }
}