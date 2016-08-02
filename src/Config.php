<?php

namespace Abhayagiri;

class Config {

    private static $_config;

    public static function get()
    {
        if (static::$_config === null) {
            static::setConfig(static::loadConfig());
        }
        $config = static::$_config;
        $args = func_get_args();
        while (count($args)) {
            $key = $args[0];
            if (gettype($config) == 'array' && array_key_exists($key, $config)) {
                $config = $config[$key];
            } else {
                $config = null;
            }
            $args = array_slice($args, 1);
        }
        return $config;
    }

    public static function setConfig($config)
    {
        static::$_config = $config;
    }

    public static function resetConfig()
    {
        static::setConfig(null);
    }

    public static function loadConfig()
    {
        require __DIR__ . '/../config/config.php';

        $config = array_merge(array(
            'default_timezone' => 'UTC',
            'development' => true,
            'host' => 'localhost',
            'requireMahapanelSSL' => false,
            'requireSSL' => false,
        ), $config);

        $config['db'] = array_merge(array(
            'options' => array(\PDO::ATTR_PERSISTENT => true),
            'dsn' => 'mysql:dbname=' .
                $config['db']['database'] . ';host=' .
                $config['db']['host'] . ';charset=utf8',
        ), $config['db']);

        return $config;
    }
}

?>
