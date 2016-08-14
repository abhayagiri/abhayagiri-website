<?php

namespace Abhayagiri;

class SettingsException extends \Exception {};

class Settings {

    public static function get($key)
    {
        $db = DB::getPDOConnection();
        $stmt = $db->prepare('SELECT `value` FROM `settings` WHERE `key_` = :key');
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $count = count($result);
        if ($count == 1) {
            return json_decode($result[0]['value']);
        } elseif ($count == 0) {
            throw new SettingsException("No such key $key");
        } else {
            throw new SettingsException("Key $key returned multiple values");
        }
    }

    public static function set($key, $value)
    {
        $db = DB::getPDOConnection();
        $stmt = $db->prepare('UPDATE `settings` SET `value` = :value WHERE `key_` = :key');
        $stmt->bindValue(':key', $key);
        $stmt->bindValue(':value', json_encode($value));
        $stmt->execute();
        return true;
    }

}

?>
