<?php

namespace App\Legacy;

class DB {

    private static $pdo;
    private static $db;

    public static function getPDOConnection()
    {
        if (!static::$pdo) {
            static::$pdo = \DB::connection()->getPdo();
        }
        return static::$pdo;
    }

    public static function getDB()
    {
        if (!static::$db) {
            static::$db = new static();
        }
        return static::$db;
    }

    private $_db;

    public function __construct() {
        $this->_db = static::getPDOConnection();
    }

    /* -------------------------------------------------------------------------
      SELECT
      ------------------------------------------------------------------------- */

    public function _query($query) {
        $stmt = $this->_db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function _select($table, $columns = "*", $where = "", $order = "ORDER BY date DESC", $limit = "", $op = "=") {
        try {

            if (is_array($where)) {
                $filter = array();
                foreach ($where as $key => $row) {
                    $filter[] = "{$key} $op (:{$key})";
                }
                $filter = implode(' AND ', $filter);
                $filter = 'WHERE ' . $filter;
            } else {
                $filter = '';
            }

            $stmt = $this->_db->prepare("SELECT $columns FROM $table $filter ORDER BY DATE DESC $limit");

            if (is_array($where)) {
                foreach ($where as $key => $row) {
                    $stmt->bindValue(":{$key}", $row);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      SELECT
      ------------------------------------------------------------------------- */

    public function _join($tableA, $tableB, $columnsA, $columnsB, $on = array("1" => "1"), $where = array("1" => "1"), $order = "ORDER BY date DESC", $limit = '') {
        try {
            $filter1 = array();
            $filter2 = array();
            foreach ($on as $key => $row) {
                $filter1[] = "{$key}={$row}";
            }
            foreach ($where as $key => $row) {
                $filter2[] = "{$key}=:{$key}";
            }
            $filter1 = implode(' AND ', $filter1);
            $filter2 = implode(' AND ', $filter2);

            $_columnsA = str_replace("$tableA.", '', $columnsA);
            $_columnsB = str_replace("$tableB.", '', $columnsB);

            $stmt = $this->_db->prepare("
            SELECT $_columnsA,$_columnsB FROM
            (SELECT $columnsA,$columnsB
            FROM $tableA LEFT JOIN $tableB ON $filter1
            ) sel WHERE $filter2 $order $limit");

            foreach ($where as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }

            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      Insert
      ------------------------------------------------------------------------- */

    public function _insert($table, $params) {
        try {
            //Prepare Statement
            $cols = $vals = array();
            foreach ($params as $key => $row) {
                $cols[] = $key;
                $vals[] = ":{$key}";
            }
            $cols = implode(',', $cols);
            $vals = implode(',', $vals);
            $stmt = $this->_db->prepare("INSERT INTO $table ($cols) VALUES ($vals)");
            //echo "<br>INSERT INTO $table ($cols) VALUES ($vals)<br>";
            //var_dump($params);
            //Bind Params
            foreach ($params as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            //Execute
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      Delete
      ------------------------------------------------------------------------- */

    public function _delete($table, $where = array("1" => "1")) {
        try {
            $filter = array();
            foreach ($where as $key => $row) {
                $filter[] = "{$key}=:{$key}";
            }
            $filter = implode(' AND ', $filter);
            $stmt = $this->_db->prepare("DELETE FROM $table WHERE $filter");
            foreach ($where as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      UPDATE
      ------------------------------------------------------------------------- */

    public function _update($table, $params, $where = array("1" => "1")) {
        try {
            //Build filter
            $filter = array();
            foreach ($where as $key => $row) {
                $filter[] = "{$key}=:{$key}";
            }
            //Prepare Statement
            $cols = $vals = array();
            foreach ($params as $key => $row) {
                $cols[] = "{$key}=:{$key}";
            }
            $cols = implode(',', $cols);
            $filter = implode(' AND ', $filter);
            $stmt = $this->_db->prepare("UPDATE $table SET $cols WHERE $filter");
            //Bind Params
            foreach ($params as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            foreach ($where as $key => $row) {
                $stmt->bindValue(":{$key}", $row);
            }
            //Execute
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      Normalization
      ------------------------------------------------------------------------- */

    function normalizeResultFromSelect(&$result)
    {
        foreach ($result as &$row) {
            $this->normalizeRowFromSelect($row);
        }
    }

    function normalizeRowFromSelect(&$row)
    {
        if (array_has($row, 'date')) {
            $date = new \DateTime((string) $row['date'], new \DateTimeZone('UTC'));
            $humanTimeZone = new \DateTimeZone(config('abhayagiri.human_timezone'));
            $date->setTimezone($humanTimeZone);
            $row['date'] = $date->format('Y-m-d H:i:s');
        }
    }

    function normalizeColumnsForUpdate(&$columns, $currentUser)
    {
        if (array_has($columns, 'User')) {
            $columns['user'] = $columns['User'];
            unset($columns['User']);
        }
        if (!array_has($columns, 'user')) {
            $columns['user'] = $currentUser->id;
        }

        $dateString = (string) array_get($columns, 'date');
        $humanTimeZone = new \DateTimeZone(config('abhayagiri.human_timezone'));
        try {
            $date = new \DateTime($dateString, $humanTimeZone);
        } catch (Exception $e) {
            logger()->error("User input invalid date: $dateString");
            $date = new \DateTime('now');
        }
        $date->setTimezone(new \DateTimeZone('UTC'));
        $columns['date'] = $date->format('Y-m-d H:i:s');
    }

    /* -------------------------------------------------------------------------
      LOG
      ------------------------------------------------------------------------- */

    public function _log($activity, $page, $title, $user) {
        $verb;
        $preposition;
        switch ($activity) {
            case "insert":
                $verb = "Added";
                $preposition = "to";
                break;
            case "update":
                $verb = "Updated";
                $preposition = "in";
                break;
            case "delete":
                $verb = "Deleted";
                $preposition = "from";
        }
        $title = $verb . ' "' . ucfirst($title) . '" ' . $preposition . ' ' . $page;
        $this->_insert('logs', array('title' => $title, 'date' => date("Y-m-d H:i:s"), 'user' => $user));
    }

    /* -------------------------------------------------------------------------
      Alter
      ------------------------------------------------------------------------- */

    public function _alter($action, $table, $column, $type = '', $column2 = '') {
        $unique = ($column == 'url_title') ? "unique" : "";
        if ($column2 == '') {
            $column = 'column ' . $column;
        }
        try {
            $stmt = $this->_db->prepare("ALTER table $table $action $column $column2 $type");
            echo "ALTER table $table $action $unique ($column) $column2 $type";
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      Drop
      ------------------------------------------------------------------------- */

    public function _drop($table) {
        try {
            $stmt = $this->_db->prepare("DROP TABLE $table");
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      Drop
      ------------------------------------------------------------------------- */

    public function _rename($old_title, $new_title) {
        try {
            $stmt = $this->_db->prepare("RENAME TABLE $old_title TO $new_title");
            echo "RENAME TABLE $old_title TO $new_title";
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      Alter
      ------------------------------------------------------------------------- */

    public function _create($table) {
        try {
            $stmt = $this->_db->prepare("CREATE TABLE `$table` (
        id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY
        )");
            $stmt->execute();
        } catch (\PDOException $e) {
            $this->handleError($e);
        }
    }

    /* -------------------------------------------------------------------------
      LOGIN
      ------------------------------------------------------------------------- */

    public function login($email) {
        $stmt = $this->_db->prepare("SELECT id FROM mahaguild WHERE email=:email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if ($row = $stmt->fetch()) {
            return $row['id'];
        } else {
            return false;
        }
    }

    /* -------------------------------------------------------------------------
      LOGOUT
      ------------------------------------------------------------------------- */

    public function _logout($user) {
        session_destroy();
    }

    public function _last_insert_id() {
        return $this->_db->lastInsertId();
    }

    protected function handleError($e)
    {
        throw $e;
    }

    public static function logError($msg)
    {
        error_log("Database Error: " . $msg);
    }

}
