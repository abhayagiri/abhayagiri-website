<?php

namespace Abhayagiri;

class Config {

    private static $_config;

    public static function getConfig()
    {
        if (static::$_config === null) {
            static::$_config = static::loadConfig();
        }
        return static::$_config;
    }

    public static function loadConfig()
    {
        require __DIR__ . '/../config/config.php';

        $config = array_merge(array(
            'default_timezone' => 'UTC',
        ), $config);

        $config['db'] = array_merge(array(
            'options' => array(\PDO::ATTR_PERSISTENT => true),
            'dsn' => 'mysql:dbname=' .
                $config['db']['database'] . ';host=' .
                $config['db']['host'] . ';charset=utf8',
        ), $config['db']);

        // TODO set w/ version control
        $config['stamp'] = '124';

        return $config;
    }
}

?>
